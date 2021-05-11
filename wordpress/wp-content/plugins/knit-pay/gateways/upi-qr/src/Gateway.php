<?php
namespace KnitPay\Gateways\UPIQR;

use KnitPay\Gateways\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Core\Util;
use WP_Error;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;


/**
 * Title: UPI QR Gateway
 * Copyright: 2020 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 4.1.0
 */
class Gateway extends Core_Gateway {

	/**
	 * Constructs and initializes an UPI QR gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );

		$this->payment_page_title       = 'Payment Page';
		$this->payment_page_description = 'Scan the QR Code with any UPI apps like BHIM, Paytm, Google Pay, PhonePe, or any Banking UPI app to make payment for this order. After successful payment, enter the UPI Reference ID or Transaction Number submit the form. We will manually verify this payment against your 12-digits UPI Reference ID or Transaction Number (eg. 001422121258).';
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::UPI,
		);
	}

	/**
	 * Get available payment methods.
	 *
	 * @return array<int, string>
	 * @see Core_Gateway::get_available_payment_methods()
	 */
	public function get_available_payment_methods() {
		return $this->get_supported_payment_methods();
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
		$payment_currency = $payment->get_total_amount()
			->get_currency()
			->get_alphabetic_code();
		if ( isset( $payment_currency ) && 'INR' !== $payment_currency ) {
			$currency_error = 'UPI only accepts payments in Indian Rupees. If you are a store owner, kindly activate INR currency for ' . $payment->get_source() . ' plugin.';
			$this->error    = new WP_Error( 'upi_qr_error', $currency_error );
			return;
		}

		$payment->set_transaction_id( $payment->get_id() );

		$payment->set_action_url( $payment->get_pay_redirect_url() );
	}

	/**
	 * Get form HTML.
	 *
	 * @param Payment $payment     Payment to get form HTML for.
	 * @param bool    $auto_submit Flag to auto submit.
	 * @return string
	 * @throws \Exception When payment action URL is empty.
	 */
	public function get_form_html( Payment $payment, $auto_submit = false ) {

		// wp_enqueue_script( 'upiwc-qr-code' );
		$data    = $this->get_output_fields( $payment );
		$pay_uri = add_query_arg( $data, 'upi://pay' );
		$html    = '<hr>';
		// $html .= $pay_uri;
		$html .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
		$html .= '<script src="' . KNITPAY_URL . '/gateways/upi-qr/src/js/jquery.qrcode.min.js"></script>';
		$html .= '<div><strong>Scan the QR Code</strong></div><div class="qrcode"></div>';

		if ( wp_is_mobile() && ! stripos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ) {
			$html .= '<p>or</p><a class="pronamic-pay-btn" href="' . $pay_uri . '" style="font-size: 15px;">Click here to make the payment</a>';
		}

		$html .= '<hr>';

		$html .= '<script type="text/javascript">
                                        $(document).ready(function() {
                                            $(".qrcode").qrcode("' . $pay_uri . '");
                                        });
                                    </script>';

		$form_inner  = '<br><br><label for="transaction_id">Transaction ID:</label>
            <input required type="text" id="transaction_id" name="transaction_id"><br><br>';
		$form_inner .= sprintf(
			'<input class="pronamic-pay-btn" type="submit" name="pay" value="%s" />',
			__( 'Submit', 'pronamic_ideal' )
		);
		$form_inner .= '&nbsp;&nbsp;';
		$form_inner .= sprintf(
			'<input class="pronamic-pay-btn" type="submit" name="pay" value="%s" />',
			__( 'Cancel', 'pronamic_ideal' )
		);

		$html .= sprintf(
			'<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">%s</form>',
			esc_attr( $payment->get_return_url() ),
			$form_inner
		);

		return $html;

	}

	/**
	 * Redirect to the gateway action URL.
	 *
	 * @param Payment $payment The payment to redirect for.
	 * @return void
	 * @throws \Exception Throws exception when action URL for HTTP redirect is empty.
	 */
	/*
	 public function redirect( Payment $payment ) {

		parent::redirect( $payment );
	} */

	/**
	 * Get output inputs.
	 *
	 * @see Core_Gateway::get_output_fields()
	 *
	 * @param Payment $payment
	 *            Payment.
	 *
	 * @return array
	 */
	public function get_output_fields( Payment $payment ) {
		$vpa = $this->config->vpa;

		// @see https://developers.google.com/pay/india/api/web/create-payment-method
		$data['pa'] = $vpa;
		$data['pn'] = get_bloginfo();
		// $data['mc'] = '7531';// TODO
		$data['tr'] = $payment->get_id();
		// $data['url'] = ''; // Invoice/order details URL
		$data['am'] = $payment->get_total_amount()->format();
		$data['cu'] = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		// $data['tid'] = $payment->get_transaction_id();
		$data['tn'] = rawurlencode( $payment->get_description() );

		return $data;
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function update_status( Payment $payment ) {
		$transaction_id = filter_input( INPUT_POST, 'transaction_id', FILTER_SANITIZE_STRING );

		if ( empty( $transaction_id ) ) {
			$payment->add_note( 'Transaction ID not provided' );
			$payment->set_status( PaymentStatus::FAILURE );
			return;
		}

		$payment->set_transaction_id( $transaction_id );
		$payment->set_status( PaymentStatus::ON_HOLD );
	}
}
