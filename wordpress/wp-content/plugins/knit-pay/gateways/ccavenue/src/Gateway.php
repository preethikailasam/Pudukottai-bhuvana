<?php
namespace KnitPay\Gateways\CCAvenue;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use WP_Error;

require_once 'lib/Crypto.php';

/**
 * Title: CCAvenue Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 2.3.0
 */
class Gateway extends Core_Gateway {


	const NAME = 'ccavenue';

	const LIVE_URL = 'https://secure.ccavenue.com';

	const TEST_URL = 'https://test.ccavenue.com';

	/**
	 * Constructs and initializes an CCAvenue gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );

		$this->endpoint_url = self::LIVE_URL;
		if ( self::MODE_TEST === $config->mode ) {
			$this->endpoint_url = self::TEST_URL;
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
			PaymentMethods::CCAVENUE,
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
			$currency_error = 'CCAvenue only accepts payments in Indian Rupees. If you are a store owner, kindly activate INR currency for ' . $payment->get_source() . ' plugin.';
			$this->error    = new WP_Error( 'ccavenue_error', $currency_error );
			return;
		}

		$payment->set_transaction_id( $payment->get_id() );

		$payment->set_action_url( $this->endpoint_url . '/transaction/transaction.do?command=initiateTransaction' );
	}

	/**
	 * Redirect to the gateway action URL.
	 *
	 * @param Payment $payment The payment to redirect for.
	 * @return void
	 * @throws \Exception Throws exception when action URL for HTTP redirect is empty.
	 */
	public function redirect( Payment $payment ) {
		// CCAvenue not sending second parameter, hence removing it.
		$payment->key = null;
		update_post_meta( $payment->get_id(), '_pronamic_payment_key', $payment->key );

		parent::redirect( $payment );
	}

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
		$working_key = $this->config->working_key;
		$access_code = $this->config->access_code;

		$customer         = $payment->get_customer();
		$billing_address  = $payment->get_billing_address();
		$delivery_address = $payment->get_shipping_address();

		$data['merchant_id']  = $this->config->merchant_id;
		$data['order_id']     = $payment->get_id();
		$data['currency']     = $payment->get_total_amount()
			->get_currency()
			->get_alphabetic_code();
		$data['amount']       = $payment->get_total_amount()->format();
		$data['redirect_url'] = $payment->get_return_url();
		$data['cancel_url']   = $payment->get_return_url();
		$data['language']     = 'EN'; // TODO: change hardcode

		$data['billing_name']    = substr( trim( ( html_entity_decode( $customer->get_name(), ENT_QUOTES, 'UTF-8' ) ) ), 0, 50 );
		$data['billing_address'] = $billing_address->get_line_1();
		$data['billing_city']    = preg_replace( '/[^a-zA-Z\s]/', ' ', $billing_address->get_city() );
		$data['billing_state']   = preg_replace( '/[^a-zA-Z\s]/', ' ', $billing_address->get_region() );
		$data['billing_zip']     = preg_replace( '/[^a-zA-Z0-9\s]/', ' ', $billing_address->get_postal_code() );
		$data['billing_country'] = preg_replace( '/[^a-zA-Z\s]/', ' ', $billing_address->get_country_name() );
		$data['billing_tel']     = $billing_address->get_phone();
		$data['billing_email']   = $customer->get_email();

		if ( ! empty( $delivery_address ) ) {
			$data['delivery_name']    = substr( trim( ( html_entity_decode( $delivery_address->get_name(), ENT_QUOTES, 'UTF-8' ) ) ), 0, 50 );
			$data['delivery_address'] = $delivery_address->get_line_1();
			$data['delivery_city']    = preg_replace( '/[^a-zA-Z\s]/', ' ', $delivery_address->get_city() );
			$data['delivery_state']   = preg_replace( '/[^a-zA-Z\s]/', ' ', $delivery_address->get_region() );
			$data['delivery_zip']     = preg_replace( '/[^a-zA-Z0-9\s]/', ' ', $delivery_address->get_postal_code() );
			$data['delivery_country'] = preg_replace( '/[^a-zA-Z\s]/', ' ', $delivery_address->get_country_name() );
			$data['delivery_tel']     = $delivery_address->get_phone();
		}

		$data['tid'] = $payment->get_id();

		$merchant_data = '';
		foreach ( $data as $key => $value ) {
			$merchant_data .= $key . '=' . $value . '&';
		}

		$encrypted_data = encrypt( $merchant_data, $working_key ); // Method for encrypting the data.

		return array(
			'encRequest'  => $encrypted_data,
			'access_code' => $access_code,
		);
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function update_status( Payment $payment ) {
		$working_key   = $this->config->working_key;
		$received_data = null;

		if ( empty( $_POST['encResp'] ) || empty( $_POST['orderNo'] ) ) {
			return;
		}

		if ( $payment->get_transaction_id() !== $_POST['orderNo'] ) {
			return;
		}

		$encResponse = $_POST['encResp'];         // This is the response sent by the CCAvenue Server
		$rcvdString  = decrypt( $encResponse, $working_key );     // Crypto Decryption used as per the specified working key.
		parse_str( $rcvdString, $received_data );

		if ( $received_data['order_id'] !== $_POST['orderNo'] ) {
			return;
		}

		$payment->set_transaction_id( $received_data['tracking_id'] );
		$payment->set_status( Statuses::transform( $received_data['order_status'] ) );
		$payment->add_note( 'Order Status: ' . $received_data['order_status'] . '. Status Message: ' . $received_data['status_message'] );
	}
}
