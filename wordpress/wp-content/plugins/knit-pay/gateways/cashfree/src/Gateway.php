<?php
namespace KnitPay\Gateways\Cashfree;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Exception;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use WP_Error;

require_once 'lib/API.php';

/**
 * Title: Cashfree Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 2.4
 */
class Gateway extends Core_Gateway {


	const NAME = 'cashfree';

	const LIVE_URL = 'https://www.cashfree.com/checkout/post/submit';

	const TEST_URL = 'https://test.cashfree.com/billpay/checkout/post/submit';

	/**
	 * Constructs and initializes an Instamojo gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
		);

		$this->test_mode = 0;
		if ( self::MODE_TEST === $config->mode ) {
			$this->test_mode = 1;
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
			PaymentMethods::CASHFREE,
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
		$api_id     = $this->config->api_id;
		$secret_key = $this->config->secret_key;

		$api = new API( $api_id, $secret_key, $this->test_mode );

		try {
			$this->cashfree_order_id = $payment->key . '_' . $payment->get_id();
			$order_link              = $api->create_order_link( $this->get_payment_data( $payment ) );

			$payment->set_transaction_id( $this->cashfree_order_id );
			$payment->set_action_url( $order_link );
		} catch ( Exception $e ) {
			$this->error = new WP_Error( 'cashfree_error', $e->getMessage() );
		}
	}

	/**
	 * Get Payment Data.
	 *
	 * @param Payment $payment
	 *            Payment.
	 *
	 * @return array
	 */
	private function get_payment_data( Payment $payment ) {

		$customer        = $payment->get_customer();
		$billing_address = $payment->get_billing_address();
		$customer_phone  = '0';
		if ( ! empty( $billing_address ) && ! empty( $billing_address->get_phone() ) ) {
			$customer_phone = $billing_address->get_phone();
		}

		$order_id       = $this->cashfree_order_id;
		$order_amount   = $payment->get_total_amount()->format();
		$order_currency = $payment->get_total_amount()->get_currency()->get_alphabetic_code();
		$order_note     = $payment->get_description();
		$customer_name  = substr( trim( ( html_entity_decode( $customer->get_name(), ENT_QUOTES, 'UTF-8' ) ) ), 0, 20 );
		$customer_email = $customer->get_email();
		$return_url     = $payment->get_return_url();
		$notify_url     = add_query_arg( 'kp_cashfree_webhook', '', home_url( '/' ) );

		return array(
			'orderId'       => $order_id,
			'orderAmount'   => $order_amount,
			'orderCurrency' => $order_currency,
			'orderNote'     => $order_note,
			'customerName'  => $customer_name,
			'customerPhone' => $customer_phone,
			'customerEmail' => $customer_email,
			'returnUrl'     => $return_url,
			'notifyUrl'     => $notify_url,
		);
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

		$api_id     = $this->config->api_id;
		$secret_key = $this->config->secret_key;

		$api = new API( $api_id, $secret_key, $this->test_mode );

		try {
			$order_status = $api->get_order_status( $payment->get_transaction_id() );

			if ( isset( $order_status->orderExpiryTime ) ) {
				$expiry_date = new \DateTime( $order_status->orderExpiryTime, new \DateTimeZone( 'Asia/Kolkata' ) );
				$payment->set_expiry_date( $expiry_date );
			}

			if ( isset( $_POST['txStatus'] ) && Statuses::CANCELLED === $_POST['txStatus'] ) {
				$payment->set_status( PaymentStatus::CANCELLED );
				return;
			}

			if ( isset( $order_status->txStatus ) ) {
				$order_txn_status = $order_status->txStatus;

				if ( Statuses::SUCCESS === $order_txn_status ) {
					$payment->set_transaction_id( $order_status->referenceId );
				}

				$payment->set_status( Statuses::transform( $order_txn_status ) );
				$payment->add_note( 'Cashfree Transaction Status: ' . $order_txn_status . '<br>Order Status: ' . $order_status->orderStatus . '<br>Transaction reference ID: ' . $order_status->referenceId );
			}
		} catch ( Exception $e ) {
			$this->error = new WP_Error( 'cashfree_error', $e->getMessage() );
		}
	}
}
