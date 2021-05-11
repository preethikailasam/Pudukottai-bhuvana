<?php
namespace KnitPay\Gateways\PayUmoney;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Exception;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;
use WP_Error;

/**
 * Title: PayUMoney Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.9.1
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {


	const NAME = 'payumoney';

	/**
	 * Client.
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * Constructs and initializes an PayUMoney gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );

		// Supported features.
		/*
		 $this->supports = array(
			'payment_status_request',
		); */

		// Client.
		$this->client = new Client( $config );

		if ( self::MODE_TEST === $config->mode ) {
			$this->client->set_payment_server_url( Client::TEST_URL );
			$this->client->set_bolt_url( Client::BOLT_TEST_URL );
			$this->client->set_api_url( Client::API_TEST_URL );
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
			PaymentMethods::PAYUMONEY,
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
			$currency_error = 'PayUMoney only accepts payments in Indian Rupees. If you are a store owner, kindly activate INR currency for ' . $payment->get_source() . ' plugin.';
			$this->error    = new WP_Error( 'payumoney_error', $currency_error );
			return;
		}

		$payment->set_transaction_id( $payment->key . '_' . $payment->get_id() );

		if ( Client::CHECKOUT_REDIRECT_MODE == $this->config->checkout_mode ) {
			$payment->set_action_url( $this->client->get_payment_server_url() . '/_payment' );
		}
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function update_status( Payment $payment ) {
		if ( Core_Statuses::OPEN !== $payment->get_status() ) {
			return;
		}

		if ( isset( $_SERVER['HTTP_PAYUMONEY_WEBHOOK'] ) ) {
			$post_array = json_decode( file_get_contents( 'php://input' ), true );
			$this->handle_webhook( $payment, $post_array );
			return;
		}

		// txnStatus only used in bolt
		if ( array_key_exists( 'txnStatus', $_POST ) && Statuses::CANCEL === $_POST['txnStatus'] ) {
			$payment->set_status( Statuses::transform( Statuses::CANCEL ) );
			return;
		}

		if ( empty( $_POST['key'] ) ) {
			$this->update_status_using_api( $payment );
			return;
		}

		$status      = $_POST['status'];
		$firstname   = $_POST['firstname'];
		$amount      = $_POST['amount'];
		$txnid       = $_POST['txnid'];
		$posted_hash = $_POST['hash'];
		$key         = $_POST['key'];
		$productinfo = $_POST['productinfo'];
		$email       = $_POST['email'];
		$payuMoneyId = $_POST['payuMoneyId'];
		$udf5        = $_POST['udf5'];

		$merchant_key  = $this->config->merchant_key;
		$merchant_salt = $this->config->merchant_salt;

		if ( ! ( $payuMoneyId === $payment->get_transaction_id() || $txnid === $payment->get_transaction_id() ) || $key !== $merchant_key ) {
			return;
		}

		if ( isset( $_POST['additionalCharges'] ) ) {
			$additionalCharges = $_POST['additionalCharges'];
			$retHashSeq        = $additionalCharges . '|' . $merchant_salt . '|' . $status . '||||||' . $udf5 . '|||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {
			$retHashSeq = $merchant_salt . '|' . $status . '||||||' . $udf5 . '|||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$hash = hash( 'sha512', $retHashSeq );

		if ( $hash != $posted_hash ) {
			$this->error = new WP_Error( 'payumoney_error', 'Invalid Transaction. Hash Missmatch.' );
		} else {
			$payment->set_status( Statuses::transform( $status ) );
			$payment->set_transaction_id( $payuMoneyId );
		}
	}

	private function update_status_using_api( Payment $payment ) {
		$auth_header = $this->config->auth_header;

		if ( empty( $auth_header ) ) {
			$this->error = new WP_Error( 'payumoney_error', 'Auth Header is empty. Payment Status Request is not supported. Kindly setup Auth Header in Configuration page.' );
			return;
		}

		try {
			$txn_status_response = $this->client->get_merchant_transaction_status( $payment->get_transaction_id() );
		} catch ( Exception $e ) {
			$this->error = new WP_Error( 'payumoney_error', $e->getMessage() );
			return;
		}

		if ( $txn_status_response->merchantTransactionId !== $payment->get_transaction_id() ) {
			return;
		}

		$payment->set_status( Statuses::transform( $txn_status_response->status ) );

		$log = 'PayUmoney Status: ' . $txn_status_response->status;
		if ( Core_Statuses::OPEN !== $payment->get_status() ) {
			$payment->set_transaction_id( $txn_status_response->paymentId );
		}
		$payment->add_note( $log );
	}

	/**
	 * Handle Webhook Call.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	private function handle_webhook( $payment, $post_array ) {
		if ( $_SERVER['HTTP_PAYUMONEY_WEBHOOK'] !== $this->config->authorization_header_value ) {
				$this->error = new WP_Error( 'payumoney_error', 'Invalid Transaction. Authorization Header Value Missmatch.' );
				return;
		}

		$status        = $post_array['status'];
		$firstname     = $post_array['customerName'];
		$amount        = $post_array['amount'];
		$txnid         = $post_array['merchantTransactionId'];
		$posted_hash   = $post_array['hash'];
		$productinfo   = $post_array['productInfo'];
		$email         = $post_array['customerEmail'];
		$payuMoneyId   = $post_array['paymentId'];
		$udf5          = $post_array['udf5'];
		$error_message = $post_array['error_Message'];

		if ( ! ( $payuMoneyId === $payment->get_transaction_id() || $txnid === $payment->get_transaction_id() ) ) {
			return;
		}

		$merchant_key  = $this->config->merchant_key;
		$merchant_salt = $this->config->merchant_salt;

		$received_status = $status;
		if ( Statuses::USER_CANCELED === $status || Statuses::FAILED === $status ) {
			$status = Statuses::FAILURE;
		}

		if ( isset( $_POST['additionalCharges'] ) ) {
			$additionalCharges = $_POST['additionalCharges'];
			$retHashSeq        = $additionalCharges . '|' . $merchant_salt . '|' . $status . '||||||' . $udf5 . '|||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $merchant_key;
		} else {
			$retHashSeq = $merchant_salt . '|' . $status . '||||||' . $udf5 . '|||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $merchant_key;
		}
		$hash = hash( 'sha512', $retHashSeq );

		if ( $hash != $posted_hash ) {
			$this->error = new WP_Error( 'payumoney_error', 'Invalid Transaction. Hash Missmatch.' );
		} else {
			if ( Statuses::SUCCESSFUL !== $received_status ) {
				$payment->add_note( 'error: ' . $error_message );
			}
			$payment->set_status( Statuses::transform( $received_status ) );
			$payment->set_transaction_id( $payuMoneyId );
		}
	}

	/**
	 * Redirect via HTML.
	 *
	 * @see Core_Gateway::get_output_fields()
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function get_output_fields( Payment $payment ) {

		$merchant_key  = $this->config->merchant_key;
		$merchant_salt = $this->config->merchant_salt;

		$txnid        = $payment->get_transaction_id();
		$amount       = $payment->get_total_amount()->format();
		$product_info = $payment->get_description();

		$customer = $payment->get_customer();
		if ( null !== $customer->get_name() ) {
			$first_name = $customer->get_name()->get_first_name();
			$last_name  = $customer->get_name()->get_last_name();
		}
		$email = $customer->get_email();

		$phone     = '';
		$address   = '';
		$address_2 = '';
		$city      = '';
		$state     = '';
		$country   = '';
		$zipcode   = '';

		$billing_address = $payment->get_billing_address();
		if ( null !== $billing_address ) {
			if ( ! empty( $billing_address->get_phone() ) ) {
				$phone = $billing_address->get_phone();
			}
			$address   = $billing_address->get_line_1();
			$address_2 = $billing_address->get_line_2();
			$city      = $billing_address->get_city();
			$state     = $billing_address->get_region();
			$country   = $billing_address->get_country();
			$zipcode   = $billing_address->get_postal_code();
		}

		$udf1 = null;
		$udf2 = null;
		$udf3 = null;
		$udf4 = null;
		$udf5 = $payment->get_method();

		$str = "{$merchant_key}|{$txnid}|{$amount}|{$product_info}|{$first_name}|{$email}|{$udf1}|{$udf2}|{$udf3}|{$udf4}|{$udf5}||||||{$merchant_salt}";

		$hash = strtolower( hash( 'sha512', $str ) );

		$return_url = $payment->get_return_url();
		$cancel_url = $payment->get_return_url();

		return array(
			'key'              => $merchant_key,
			'txnid'            => $txnid,
			'amount'           => $amount,
			'productinfo'      => $product_info,
			'firstname'        => $first_name,
			'lastname'         => $last_name,
			'address1'         => $address,
			'address2'         => $address_2,
			'city'             => $city,
			'state'            => $state,
			'country'          => $country,
			'zipcode'          => $zipcode,
			'email'            => $email,
			'phone'            => $phone,
			'surl'             => $return_url,
			'furl'             => $cancel_url,
			'hash'             => $hash,
			'service_provider' => 'payu_paisa',
			'udf5'             => $udf5,
		);
	}

	/**
	 * Get form HTML.
	 *
	 * @see Core_Gateway::redirect_via_html()
	 *
	 * @param Payment $payment
	 *            The payment to redirect for.
	 * @return void
	 */
	public function redirect_via_html( Payment $payment ) {
		if ( Client::CHECKOUT_BOLT_MODE == $this->config->checkout_mode ) {

			// TODO LOGO
			if ( empty( $bolt_logo ) ) {
				$bolt_logo = 'https://plugins.svn.wordpress.org/knit-pay/assets/icon.svg';
			}

			// TODO Color
			$bolt_color = '';

			include 'views/redirect-via-html-bolt.php';

			exit();
		}

		if ( Client::CHECKOUT_BOLT_MODE !== $this->config->checkout_mode ) {
			parent::redirect_via_html( $payment );
		}
	}

	/**
	 * Get form HTML.
	 *
	 * @see Core_Gateway::redirect_via_html()
	 *
	 * @param Payment $payment
	 *            Payment to get form HTML for.
	 * @param bool    $auto_submit
	 *            Flag to auto submit.
	 * @return string
	 * @throws \Exception When payment action URL is empty.
	 */
	public function get_form_html( Payment $payment, $auto_submit = false ) {
		if ( Client::CHECKOUT_BOLT_MODE != $this->config->checkout_mode ) {
			return parent::get_form_html( $payment, $auto_submit );
		}

		$form_inner = $this->get_output_html( $payment );

		$form_inner .= sprintf( '<input class="pronamic-pay-btn" type="submit" name="pay" onclick="launchBOLT(jQuery); return false;" value="%s" />', __( 'Pay', 'pronamic_ideal' ) );

		$html = $form_inner;

		if ( $auto_submit ) {
			$html .= '<script type="text/javascript">launchBOLT(jQuery);</script>';
		}

		return $html;
	}
}
