<?php
namespace KnitPay\Gateways\Razorpay;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;

use Pronamic\WordPress\Pay\Payments\Payment;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use WP_Error;

/**
 * Title: Razorpay Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since   1.7.0
 */
class Gateway extends Core_Gateway {
	const NAME = 'razorpay';

	/**
	 * Constructs and initializes an Razorpay gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
		);

		if ( defined( 'KNIT_PAY_RAZORPAY_SUBSCRIPTION' ) ) {
			$this->supports = wp_parse_args(
				$this->supports,
				array(
					'recurring_credit_card',
					'recurring',
				)
			);
		}

		$this->ENV = 'prod';
		if ( self::MODE_TEST === $config->mode ) {
			$this->ENV = 'test';
		}
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::BANK_TRANSFER,
			PaymentMethods::CREDIT_CARD,
			PaymentMethods::MAESTRO,
			PaymentMethods::RAZORPAY,
		);
	}

	/**
	 * Start.
	 *
	 * @see Core_Gateway::start()
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function start( Payment $payment ) {
		if ( PaymentStatus::SUCCESS === $payment->get_status() ) {
			return;
		}

		$payment_currency = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		$key_id     = $this->config->key_id;
		$key_secret = $this->config->key_secret;

		$api = new Api( $key_id, $key_secret );

		$customer = $payment->get_customer();

		// Recurring payment method.
		$subscription = $payment->get_subscription();

		$is_subscription_payment = ( $subscription && $this->supports( 'recurring' ) );

		if ( $is_subscription_payment ) {
			$this->create_razorpay_subscription( $api, $payment, $subscription, $customer, $payment_currency );
		} else {
			$this->create_razorpay_order( $api, $payment, $customer, $payment_currency );
		}

		$payment->set_transaction_id( $payment->key . '_' . $payment->get_id() );
		$payment->set_action_url( $payment->get_pay_redirect_url() );
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function update_status( Payment $payment ) {
		if ( PaymentStatus::SUCCESS === $payment->get_status() ) {
			return;
		}

		if ( null === $payment->get_transaction_id() ) {
			return;
		}

		$key_id     = $this->config->key_id;
		$key_secret = $this->config->key_secret;
		$action     = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		$api = new Api( $key_id, $key_secret );

		$razorpay_order_id = $payment->get_meta( 'razorpay_order_id' );
		if ( empty( $razorpay_order_id ) ) {
			return;
		}

		$razorpay_payments = $api->order->fetch( $razorpay_order_id )->payments();
		if ( empty( $razorpay_payments->count ) ) {
			if ( isset( $action ) && 'cancelled' === $action ) {
				$payment->set_status( Statuses::transform( $action ) );
				return;
			}

			$payment->add_note( 'No Payment Found for order_id: ' . $razorpay_order_id );
			return;
		}

		$razorpay_payment = $razorpay_payments->items[0];
		$this->update_payment_status( $payment, $razorpay_payment );
		return;
	}

	private function get_subscription_last_payment_id( $api, $razorpay_subscription_id, $retry = 3 ) {
		if ( empty( $retry ) ) {
			return '';
		}
		$razorpay_invoices  = $api->subs->get_subscription_invoices( $razorpay_subscription_id );
		$current_payment_id = $razorpay_invoices->items[0]->payment_id;
		if ( empty( $current_payment_id ) ) {
			$current_payment_id = $this->get_subscription_last_payment_id( $api, $razorpay_subscription_id, --$retry );
		}
		return $current_payment_id;
	}

	private function update_payment_status( $payment, $razorpay_payment, $razorpay_subscription_id = null ) {
		if ( floatval( $razorpay_payment->amount ) !== $payment->get_total_amount()->format() * 100 ) {
			return;
		}

		$razorpay_subscription_id = $payment->get_meta( 'razorpay_subscription_id' );

		$note = '<strong>Razorpay Parameters:</strong>';
		if ( ! empty( $razorpay_subscription_id ) ) {
			$note .= '<br>subscription_id: ' . $razorpay_subscription_id;
		}
		$note .= '<br>payment_id: ' . $razorpay_payment->id;
		$note .= '<br>order_id: ' . $razorpay_payment->order_id;
		if ( ! empty( $razorpay_payment->invoice_id ) ) {
			$note .= '<br>invoice_id: ' . $razorpay_payment->invoice_id;
		}
		$note .= '<br>Status: ' . $razorpay_payment->status;
		if ( ! empty( $razorpay_payment->error_description ) ) {
			$note .= '<br>error_description: ' . $razorpay_payment->error_description;
		}

		$payment->add_note( $note );
		$payment->set_status( Statuses::transform( $razorpay_payment->status ) );

		if ( PaymentStatus::SUCCESS === $payment->get_status() ) {
			$payment->set_transaction_id( $razorpay_payment->id );
		}
	}

	private function create_razorpay_order( $api, Payment $payment, $customer, $payment_currency ) {
		$this->razorpay_order_id = $payment->get_meta( 'razorpay_order_id' );

		// Return if order id already exists for this payments
		if ( $this->razorpay_order_id ) {
			return;
		}

		$orderData = array(
			'receipt'         => $payment->key . '_' . $payment->get_id(),
			'amount'          => $payment->get_total_amount()->format() * 100,
			'currency'        => $payment_currency,
			'notes'           => array(
				'knitpay_payment_id' => $payment->id,
			),
			'payment_capture' => 1, // TODO: 1 for auto capture. give admin option to set auto capture. do re-search to see if razorpay has deprecate it or not.
		);

		try {
			$razorpayOrder           = $api->order->create( $orderData );
			$this->razorpay_order_id = $razorpayOrder['id'];
			$payment->set_meta( 'razorpay_order_id', $this->razorpay_order_id );
		} catch ( BadRequestError $e ) {
			$this->error = new WP_Error( 'razorpay_error', $e->getMessage() );
		}
	}

	private function create_razorpay_subscription( $api, Payment $payment, Subscription $subscription, $customer, $payment_currency ) {

		$razorpay_subscription_id = $payment->get_meta( 'razorpay_subscription_id' );

		// Return if subscription already exists for this payments
		if ( $razorpay_subscription_id ) {
			return;
		}

		// Don't create new Razorpay subscription if this subscription has more than 1 payment.
		if ( 1 !== count( $subscription->get_payments() ) ) {
			return;
		}

		$payment_periods = $payment->get_periods();
		if ( is_null( $payment_periods ) ) {
			$this->error = new WP_Error( 'razorpay_error', 'Periods is not set.' );
			return;
		}
		$subscription_period = $payment_periods[0];

		$subscription_phase = $subscription_period->get_phase();

		switch ( substr( $subscription_phase->get_interval()->get_specification(), -1, 1 ) ) {
			case 'D':
				$period = 'daily';
				break;
			case 'W':
				$period = 'weekly';
				break;
			case 'M':
				$period = 'monthly';
				break;
			case 'Y':
				$period = 'yearly';
				break;
			default:
				return;
		}
		try {
			$plan_data     = array(
				'period'   => $period,
				'interval' => substr( $subscription_phase->get_interval()->get_specification(), -2, 1 ),
				'item'     => array(
					'name'     => $subscription->description,
					'amount'   => $subscription_phase->get_amount()->format() * 100,
					'currency' => $payment_currency,
				),
				'notes'    => array(
					'knitpay_subscription_id'  => $subscription->get_id(),
					'knitpay_payment_id'       => $payment->id,
					'knitpay_subscription_key' => $subscription->get_key(),
				),
			);
			$razorpay_plan = $api->plan->create( $plan_data );

			$total_count = $this->get_max_count_for_period( $period, $subscription_phase->get_total_periods() );

			// TODO: Bug, total periods not updated in subscription
			// $subscription_phase->set_total_periods($total_count);

			$subscription_data     = array(
				'plan_id'         => $razorpay_plan->id,
				'total_count'     => $total_count,
				'customer_notify' => 1,
				'addons'          => array(),
				'notes'           => array(
					'knitpay_subscription_id'  => $subscription->get_id(),
					'knitpay_payment_id'       => $payment->id,
					'knitpay_subscription_key' => $subscription->get_key(),
				),
				'notify_info'     => array(
					'notify_phone' => $payment->get_billing_address()->get_phone(),
					'notify_email' => $customer->get_email(),
				),
			);
			$razorpay_subscription = $api->subs->create( $subscription_data );
			$razorpay_invoices     = $api->subs->get_subscription_invoices( $razorpay_subscription->id );

			// Save Subscription and Plan ID
			$subscription->set_meta( 'razorpay_subscription_id', $razorpay_subscription->id );
			$subscription->set_meta( 'razorpay_plan_id,', $razorpay_plan->id );

			$payment->set_meta( 'razorpay_order_id', $razorpay_invoices->items[0]->order_id );
			$payment->set_meta( 'razorpay_subscription_id', $razorpay_subscription->id );
		} catch ( BadRequestError $e ) {
			$this->error = new WP_Error( 'razorpay_error', $e->getMessage() );
		}
	}

	private function get_max_count_for_period( $period, $total_count ) {
		switch ( $period ) {
			case 'daily':
				return min( $total_count, 36500 );
			case 'weekly':
				return min( $total_count, 5200 );
			case 'monthly':
				return min( $total_count, 1200 );
			case 'yearly':
				return min( $total_count, 100 );
			default:
				return;
		}
	}

	/**
	 * Get form HTML.
	 *
	 * @see Core_Gateway::get_form_html()
	 *
	 * @param Payment $payment     Payment to get form HTML for.
	 * @param bool    $auto_submit Flag to auto submit.
	 * @return string
	 * @throws \Exception When payment action URL is empty.
	 */
	public function get_form_html( Payment $payment, $auto_submit = false ) {
		if ( PaymentStatus::SUCCESS === $payment->get_status() ) {
			wp_safe_redirect( $payment->get_return_redirect_url() );
		}
		$data      = $this->get_output_fields( $payment );
		$data_json = json_encode( $data );
		// $return_url = $payment->get_return_url();
		require 'checkout/manual.php';

		if ( $auto_submit ) {
			$html = '<script type="text/javascript">document.getElementById("rzp-button1").click();</script>';
		}

		return $html;
	}

	/**
	 * Get output inputs.
	 *
	 * @param Payment $payment Payment.
	 *
	 * @see Core_Gateway::get_output_fields()
	 *
	 * @return array
	 * @since 2.8.1
	 */
	public function get_output_fields( Payment $payment ) {
		$customer                 = $payment->get_customer();
		$payment_currency         = $payment->get_total_amount()->get_currency()->get_alphabetic_code();
		$razorpay_order_id        = $payment->get_meta( 'razorpay_order_id' );
		$razorpay_subscription_id = $payment->get_meta( 'razorpay_subscription_id' );
		$billing_address          = $payment->get_billing_address();

		// @see https://razorpay.com/docs/payment-gateway/web-integration/standard/checkout-options/
		$data = array(
			'key'             => $this->config->key_id,
			'amount'          => $payment->get_total_amount()->format() * 100,
			'currency'        => $payment_currency,
			// "name"              => "DJ Tiesto", //TODO: Merchant name
			'description'     => $payment->get_description(),
			'image'           => 'https://cdn.razorpay.com/logo.svg', // TODO: add option to add merchant logo
			'order_id'        => $razorpay_order_id,
			'subscription_id' => $razorpay_subscription_id,
			'prefill'         => array(
				'name'  => substr( trim( ( html_entity_decode( $customer->get_name(), ENT_QUOTES, 'UTF-8' ) ) ), 0, 45 ),
				'email' => $customer->get_email(),
				// 'method' => 'netbanking', // TODO: payment method. https://razorpay.com/docs/payment-gateway/web-integration/standard/checkout-options/
			),
			'notes'           => array(
				'knitpay_payment_id' => $payment->id,
			),
			'theme'           => array(
				// 'color' => '#F37254', // TODO
				'backdrop_color' => '#f0f0f0',
			),
			'callback_url'    => $payment->get_return_url(),
			'timeout'         => 1200,
		);

		if ( isset( $billing_address ) && ! empty( $billing_address->get_phone() ) ) {
			$data['prefill']['contact'] = $billing_address->get_phone();
		}

		return $data;
	}
}
