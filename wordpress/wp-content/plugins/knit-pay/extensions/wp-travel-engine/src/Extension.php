<?php

namespace KnitPay\Extensions\WPTravelEngine;

use Pronamic\WordPress\Pay\AbstractPluginIntegration;
use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;
use Pronamic\WordPress\Pay\Core\Util;
use Pronamic\WordPress\Pay\Payments\Payment;
use WTE_Booking;

/**
 * Title: WP Travel Engine extension
 * Description:
 * Copyright: 2020-2021 Knit Pay
 * Company: Knit Pay
 *
 * @author  knitpay
 * @since   1.9
 */
class Extension extends AbstractPluginIntegration {
	/**
	 * Slug
	 *
	 * @var string
	 */
	const SLUG = 'wp-travel-engine';

	/**
	 * Constructs and initialize WP Travel Engine extension.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name' => __( 'WP Travel Engine', 'pronamic_ideal' ),
			)
		);

		// Dependencies.
		$dependencies = $this->get_dependencies();

		$dependencies->add( new WPTravelEngineDependency() );
	}

	/**
	 * Setup plugin integration.
	 *
	 * @return void
	 */
	public function setup() {
		add_filter( 'pronamic_payment_source_text_' . self::SLUG, array( $this, 'source_text' ), 10, 2 );
		add_filter( 'pronamic_payment_source_description_' . self::SLUG, array( $this, 'source_description' ), 10, 2 );
		add_filter( 'pronamic_payment_source_url_' . self::SLUG, array( $this, 'source_url' ), 10, 2 );

		// Check if dependencies are met and integration is active.
		if ( ! $this->is_active() ) {
			return;
		}

		add_filter( 'pronamic_payment_redirect_url_' . self::SLUG, array( $this, 'redirect_url' ), 10, 2 );
		add_action( 'pronamic_payment_status_update_' . self::SLUG, array( $this, 'status_update' ), 10 );

		require_once 'Helper.php';
		require_once 'Gateway.php';
		new Gateway();

		// TODO add customer phone fileld
		// wp_travel_engine_booking_fields_display

		add_filter( 'wp_travel_engine_available_payment_gateways', array( $this, 'add_payment_gateways' ) );
		add_filter( 'wpte_settings_get_global_tabs', array( $this, 'settings_get_global_tabs' ) );
		add_action( 'wp_travel_engine_before_billing_form', array( $this, 'wp_travel_engine_before_billing_form' ), 10 );
		// TODO add payment details box on booking page.
		// add_action( 'add_meta_boxes', array( $this, 'wpte_payu_add_meta_boxes' ) );
	}

	public static function wp_travel_engine_before_billing_form() {
		return wp_travel_engine_print_notices();
	}

	public static function add_payment_gateways( $gateways_list ) {
		$gateways_list['knit_pay'] = array(
			'label'        => __( 'Knit Pay', 'wp-travel-engine' ),
			'input_class'  => 'knit-pay-payment',
			'public_label' => 'Knit Pay',
			'icon_url'     => '',
			'info_text'    => __( 'Please check this to enable Knit Pay payment options for trip booking and fill the account info below.', 'wp-travel-engine' ),
		);

		return $gateways_list;
	}

	public static function settings_get_global_tabs( $global_tabs ) {
		$global_tabs['wpte-payment']['sub_tabs']['knit_pay'] = array(
			'label'        => __( 'Knit Pay Settings', 'wp-travel-engine' ),
			'content_path' => __DIR__ . '/admin_setting.php',
			'current'      => true,
		);

		return $global_tabs;
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @param string  $url     Redirect URL.
	 * @param Payment $payment Payment.
	 *
	 * @return string
	 */
	public static function redirect_url( $url, $payment ) {
		$booking_id = (int) $payment->get_source_id();
		$return_url = wp_travel_engine_get_booking_confirm_url();

		switch ( $payment->get_status() ) {
			case Core_Statuses::CANCELLED:
			case Core_Statuses::EXPIRED:
			case Core_Statuses::FAILURE:
				// TODO redirect to fail page
				return add_query_arg(
					array(
						'booking_id' => $booking_id,
						'booked'     => false,
						'status'     => 'cancel',
					),
					$return_url
				);

				break;

			case Core_Statuses::SUCCESS:
				return add_query_arg(
					array(
						'booking_id'  => $booking_id,
						'booked'      => true,
						'status'      => 'success',
						'wte_gateway' => $payment->get_method(),
					),
					$return_url
				);
				break;

			case Core_Statuses::RESERVED:
			case Core_Statuses::OPEN:
				return home_url( '/' );
		}

		return $url;
	}

	/**
	 * Update the status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public static function status_update( Payment $payment ) {
		// TODO WP Travel Engine and done mojor changes after versino 4.3.0. new version is still under development.
		// TODO Check the compatibility after July 2021.
		$booking_id     = (int) $payment->get_source_id();
		$wte_payment_id = $payment->get_meta( 'wte_payment_id' );

		$wte_payment = get_post( $wte_payment_id );
		if ( isset( $wte_payment->payable ) ) {
			$payable = $wte_payment->payable;
		}

		$booking_metas = get_post_meta( $booking_id, 'wp_travel_engine_booking_setting', true );
		$booking       = get_post( $booking_id );

		// payment completed.
		// Update booking status and Payment args.
		$booking_metas['place_order']['payment']['payment_gateway'] = $payment->get_method();
		$booking_metas['place_order']['payment']['payment_status']  = $payment->get_status();
		$booking_metas['place_order']['payment']['transaction_id']  = $payment->get_transaction_id();

		update_post_meta( $booking_id, 'wp_travel_engine_booking_setting', $booking_metas );

		// TODO: remove hardcoded
		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_gateway', 'Knit Pay' );

		/*
		 $payment_details = array(
			'knit_pay_payment_method' => array(
				'label' => __( 'Payment Method', 'wp-travel-engine' ),
				'value' => $payment->get_method(),
			),
			'knit_pay_payment_id'     => array(
				'label' => __( 'Knit Pay Payment ID', 'wp-travel-engine' ),
				'value' => $payment->get_id(),
			),
			'txn_id'                  => array(
				'label' => __( 'Transaction ID', 'wp-travel-engine' ),
				'value' => $payment->get_transaction_id(),
			),
		);

		update_post_meta( $booking_id, 'wp_travel_engine_booking_payment_details', $payment_details ); */

		switch ( $payment->get_status() ) {
			case Core_Statuses::CANCELLED:
			case Core_Statuses::EXPIRED:
				WTE_Booking::update_booking(
					$booking_id,
					array(
						'meta_input' => array(
							'wp_travel_engine_booking_payment_status' => 'cancelled',
							'wp_travel_engine_booking_status' => 'canceled',
						),
					)
				);
				update_post_meta( $wte_payment_id, 'payment_status', 'cancelled' );

				break;
			case Core_Statuses::FAILURE:
				WTE_Booking::update_booking(
					$booking_id,
					array(
						'meta_input' => array(
							'wp_travel_engine_booking_payment_status' => 'failed',
							'wp_travel_engine_booking_status' => 'pending',
						),
					)
				);
				update_post_meta( $wte_payment_id, 'payment_status', 'failed' );

				break;
			case Core_Statuses::SUCCESS:
				if ( empty( $booking->due_amount ) ) {
					return;
				}
				$payment_status = 'complete';
				$paid_amount    = $booking->paid_amount + $payable['amount'];
				$due_amount     = $booking->due_amount - $payable['amount'];
				if ( ! empty( $due_amount ) ) {
					$payment_status = 'partially-paid';
				}
				WTE_Booking::update_booking(
					$booking_id,
					array(
						'meta_input' => array(
							'paid_amount' => $paid_amount,
							'due_amount'  => $due_amount,
							'wp_travel_engine_booking_payment_status' => $payment_status,
							'wp_travel_engine_booking_status' => 'booked',
						),
					)
				);
				update_post_meta( $wte_payment_id, 'payment_status', $payment_status );

				break;
			case Core_Statuses::OPEN:
			default:
				WTE_Booking::update_booking(
					$booking_id,
					array(
						'meta_input' => array(
							'wp_travel_engine_booking_payment_status' => 'pending',
							'wp_travel_engine_booking_status' => 'pending',
						),
					)
				);
				update_post_meta( $wte_payment_id, 'payment_status', 'pending' );
				break;
		}
	}

	/**
	 * Source column
	 *
	 * @param string  $text    Source text.
	 * @param Payment $payment Payment.
	 *
	 * @return string $text
	 */
	public function source_text( $text, Payment $payment ) {
		$text = __( 'WP Travel Engine', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->source_id ),
			/* translators: %s: source id */
			sprintf( __( 'Booking #%s', 'pronamic_ideal' ), $payment->source_id )
		);

		return $text;
	}

	/**
	 * Source description.
	 *
	 * @param string  $description Description.
	 * @param Payment $payment     Payment.
	 *
	 * @return string
	 */
	public function source_description( $description, Payment $payment ) {
		return __( 'WP Travel Engine Booking', 'pronamic_ideal' );
	}

	/**
	 * Source URL.
	 *
	 * @param string  $url     URL.
	 * @param Payment $payment Payment.
	 *
	 * @return string
	 */
	public function source_url( $url, Payment $payment ) {
		return get_edit_post_link( $payment->source_id );
	}

}
