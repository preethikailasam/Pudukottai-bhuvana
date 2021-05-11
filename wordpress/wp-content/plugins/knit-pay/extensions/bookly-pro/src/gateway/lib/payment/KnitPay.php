<?php
namespace BooklyKnitPay\Lib\Payment;

use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Money\Currency;
use Pronamic\WordPress\Money\TaxedMoney;
use Pronamic\WordPress\Pay\Payments\Payment;
use Bookly\Lib as BooklyLib;
use BooklyKnitPay\Lib\ProxyProviders\Shared;
use KnitPay\Extensions\BooklyPro\Helper;

/**
 * Class TwoCheckout
 */
class KnitPay {

	// Array for cleaning 2Checkout request
	public static $remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg' );

	public static function renderForm( $form_id, $page_url, $payment_method ) {

		$userData = new BooklyLib\UserBookingData( $form_id );
		if ( $userData->load() ) {
			$replacement = array(
				'%form_id%'      => $form_id,
				'%gateway%'      => $payment_method,
				'%response_url%' => esc_attr( $page_url ),
				'%back%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
				'%next%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_step_payment_button_next' ),
				'%align_class%'  => get_option( 'bookly_app_align_buttons_left' ) ? 'bookly-left' : 'bookly-right',
			);
			$form        = '<form method="post" class="bookly-%gateway%-form" onsubmit="return disableKnitPayButton();">
                <input type="hidden" name="bookly_fid" value="%form_id%"/>
                <input type="hidden" name="bookly_action" value="%gateway%-checkout"/>
                <input type="hidden" name="response_url" value="%response_url%"/>
                <button class="bookly-back-step bookly-js-back-step bookly-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40"><span class="ladda-label">%back%</span></button>
                <div class="%align_class%">
                    <button class="bookly-next-step bookly-btn ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">%next%</span></button>
                </div>
             </form>';
			return strtr( $form, $replacement );
		}
	}

	/**
	 * Redirect to Knit Pay Payment page.
	 *
	 * @param $form_id
	 * @param BooklyLib\UserBookingData $userData
	 * @param string                    $page_url
	 */
	public static function paymentPage( $form_id, BooklyLib\UserBookingData $userData, $page_url, $payment_method ) {
		$config_id = get_option( 'bookly_' . $payment_method . '_config_id' );

		// Use default gateway if no configuration has been set.
		if ( '' === $config_id ) {
			$config_id = get_option( 'pronamic_pay_config_id' );
		}

		$gateway = Plugin::get_gateway( $config_id );

		if ( ! $gateway ) {
			return false;
		}

		$cart_info = $userData->cart->getInfo( $payment_method );

		$coupon = $userData->getCoupon();

		$bookly_payment = new BooklyLib\Entities\Payment();
		$bookly_payment
		->setType( $payment_method )
		->setCartInfo( $cart_info )
		->setStatus( BooklyLib\Entities\Payment::STATUS_PENDING )
		->save();

		$order = $userData->save( $bookly_payment );
		if ( $coupon ) {
			$coupon->claim();
			$coupon->save();
		}
		$bookly_payment
		->setDetailsFromOrder( $order, $cart_info )
		->save();

		$gateway->set_payment_method( $payment_method );

		/**
		 * Build payment.
		 */
		$payment = new Payment();

		$payment->source    = 'bookly-pro';
		$payment->source_id = $bookly_payment->getId();
		$payment->order_id  = $bookly_payment->getId();

		$payment->description = Helper::get_description( $payment_method, $form_id, $userData, $bookly_payment );

		$payment->title = Helper::get_title( $userData );

		// Customer.
		$payment->set_customer( Helper::get_customer( $userData ) );

		// Address.
		$payment->set_billing_address( Helper::get_address( $userData ) );

		// Currency.
		$currency = Currency::get_instance( \get_option( 'bookly_pmt_currency' ) );

		// Amount.
		$payment->set_total_amount( new TaxedMoney( $cart_info->getGatewayAmount(), $currency ) );

		// Method.
		$payment->method = $payment_method;

		// Configuration.
		$payment->config_id = $config_id;

		try {
			$payment = Plugin::start_payment( $payment );

			$error = $gateway->get_error();

			if ( is_wp_error( $error ) ) {
				throw new \Exception( $error->get_error_message() );
			}

			$userData->setPaymentStatus( $payment_method, BooklyLib\Entities\Payment::STATUS_PENDING );
			$userData->sessionSave();

			$payment->set_meta( 'bookly_form_id', $form_id );
			$payment->set_meta( 'booking_form_url', $page_url );
			wp_safe_redirect( $payment->get_pay_redirect_url() );
			exit();
		} catch ( \Exception $e ) {
			self::_deleteAppointments( $order );
			if ( $bookly_payment !== null ) {
				$bookly_payment->delete();
			}
			$userData->setPaymentStatus( $payment_method, 'error', $e->getMessage() );
			$userData->sessionSave();
			@wp_redirect( remove_query_arg( self::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
			exit;
		}
	}

	/**
	 * @param BooklyLib\DataHolders\Booking\Order $order
	 */
	private static function _deleteAppointments( BooklyLib\DataHolders\Booking\Order $order ) {
		foreach ( $order->getFlatItems() as $item ) {
			$item->getCA()->deleteCascade( true );
		}
	}

}
