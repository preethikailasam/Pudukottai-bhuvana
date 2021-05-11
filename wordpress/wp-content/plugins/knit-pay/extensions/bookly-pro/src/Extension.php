<?php

namespace KnitPay\Extensions\BooklyPro;

use Pronamic\WordPress\Pay\AbstractPluginIntegration;
use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;
use Pronamic\WordPress\Pay\Core\Util;
use Pronamic\WordPress\Pay\Payments\Payment;
use Bookly\Lib as BooklyLib;

/**
 * Title: Bookly Pro extension
 * Description:
 * Copyright: 2020-2021 Knit Pay
 * Company: Knit Pay
 *
 * @author  knitpay
 * @since   3.4
 */
class Extension extends AbstractPluginIntegration {
	/**
	 * Slug
	 *
	 * @var string
	 */
	const SLUG = 'bookly-pro';

	/**
	 * Constructs and initialize Bookly Pro extension.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name' => __( 'Bookly Pro', 'knit-pay' ),
			)
		);

		// Dependencies.
		$dependencies = $this->get_dependencies();

		$dependencies->add( new BooklyProDependency() );
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

		add_action( 'plugins_loaded', array( $this, 'init_gateway' ) );
	}

	/**
	 * Initialize Gateway
	 */
	public static function init_gateway() {
		require_once 'gateway/autoload.php';
		require_once 'Helper.php';
		\BooklyKnitPay\Lib\Plugin::init();
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
		if ( Core_Statuses::ON_HOLD === $payment->get_status() ) {
			return $url;
		}

		$remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg' );

		$booking_form_url = $payment->get_meta( 'booking_form_url' );

		if ( ! $booking_form_url ) {
			return $url;
		}

		return remove_query_arg( $remove_parameters, $booking_form_url );
	}

	/**
	 * Update the status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public static function status_update( Payment $payment ) {

		$source_id      = (int) $payment->get_source_id();
		$bookly_form_id = $payment->get_meta( 'bookly_form_id' );

		$userData       = new BooklyLib\UserBookingData( $bookly_form_id );
		$bookly_payment = new BooklyLib\Entities\Payment();

		if ( ! $bookly_form_id ) {
			return;
		}

		if ( ! $userData->load() || ! $bookly_payment->load( $source_id ) ) {
			return;
		}

		switch ( $payment->get_status() ) {
			case Core_Statuses::CANCELLED:
			case Core_Statuses::EXPIRED:
			case Core_Statuses::FAILURE:
				$userData->setPaymentStatus( $payment->get_method(), 'cancelled' );
				foreach ( BooklyLib\Entities\CustomerAppointment::query()->where( 'payment_id', $userData->getPaymentId() )->find() as $ca ) {
					BooklyLib\Utils\Log::deleteEntity( $ca, __METHOD__ );
					$ca->deleteCascade();
				}

				$bookly_payment->delete();

				break;
			case Core_Statuses::SUCCESS:
				$bookly_payment->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )->save();

				if ( $order = BooklyLib\DataHolders\Booking\Order::createFromPayment( $bookly_payment ) ) {
					BooklyLib\Notifications\Cart\Sender::send( $order );
				}
				foreach (
					BooklyLib\Entities\Appointment::query( 'a' )
					->leftJoin( 'CustomerAppointment', 'ca', 'a.id = ca.appointment_id' )
					->where( 'ca.payment_id', $bookly_payment->getId() )->find() as $appointment
					) {
						BooklyLib\Proxy\Pro::syncGoogleCalendarEvent( $appointment );
						BooklyLib\Proxy\OutlookCalendar::syncEvent( $appointment );
				}

					$userData->setPaymentStatus( $payment->get_method(), 'success' );

				break;
			case Core_Statuses::OPEN:
			default:
				$userData->setPaymentStatus( $payment->get_method(), BooklyLib\Entities\Payment::STATUS_PENDING );

				break;
		}
		$userData->sessionSave();
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
		$text = __( 'Bookly', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->source_id ),
			/* translators: %s: source id */
			sprintf( __( 'Payment %s', 'pronamic_ideal' ), $payment->source_id )
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
		return __( 'Bookly Payment', 'pronamic_ideal' );
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
