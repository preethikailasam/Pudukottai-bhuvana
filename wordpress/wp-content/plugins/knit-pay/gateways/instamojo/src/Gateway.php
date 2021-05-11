<?php
namespace KnitPay\Gateways\Instamojo;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Exception;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use WP_Error;
use Pronamic\WordPress\Pay\ContactName;
use Pronamic\WordPress\Pay\Address;
require_once 'lib/Instamojo.php';

/**
 * Title: Instamojo Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 1.0.0
 */
class Gateway extends Core_Gateway {

	// TODO use instead of NAME \get_post_meta( $config_id, '_pronamic_gateway_id', true );
	const NAME = 'instamojo';

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
			PaymentMethods::INSTAMOJO,
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

	private function createRequest( Payment $payment, $use_phone ) {
		$api_data    = array();
		$method_data = array();

		/*
		 * New transaction request.
		 */
		$customer = $payment->get_customer();

		$api_data['purpose'] = $payment->get_description();
		if ( empty( $api_data['purpose'] ) ) {
			$api_data['purpose'] = $payment->get_source();
		}

		$api_data['buyer_name'] = substr( trim( ( html_entity_decode( $customer->get_name(), ENT_QUOTES, 'UTF-8' ) ) ), 0, 20 );
		$api_data['email']      = $customer->get_email();
		if ( $use_phone ) {
			if ( empty( $payment->get_billing_address() ) ) {
				return $this->createRequest( $payment, false );
			}
			$api_data['phone'] = $payment->get_billing_address()->get_phone();
		}
		$api_data['amount']       = $payment->get_total_amount()->format();
		$api_data['redirect_url'] = $payment->get_return_url();
		if ( ! ( strpos( $api_data['redirect_url'], 'localhost' ) || strpos( $api_data['redirect_url'], '127.0.0.1' ) ) ) {
			$api_data['webhook'] = $payment->get_return_url();
		}

		try {
			$this->instamojo_api = new Instamojo( $this->config->client_id, $this->config->client_secret, $this->test_mode );

			$response = $this->instamojo_api->create_payment_request( $api_data );

			if ( isset( $response->id ) ) {
				$method_data['action'] = $response->longurl;
				$method_data['id']     = $response->id;
			}
		} catch ( ValidationException $e ) {
			// handle exceptions releted to response from the server.
			$method_data['errors'] = $e->getErrors();
			foreach ( $method_data['errors'] as $err ) {
				if ( stristr( $err, 'phone' ) ) {
					return $this->createRequest( $payment, false );
				}
				$this->error = new WP_Error( 'instamojo_error', $err );
			}
		} catch ( Exception $e ) {
			// handled common exception messages which will not caught above.
			$this->error = new WP_Error( 'instamojo_error', $e->getMessage() );
		}

		if ( isset( $this->error ) ) {
			return;
		}

		return $method_data;
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
			$currency_error = 'Instamojo only accepts payments in Indian Rupees. If you are a store owner, kindly activate INR currency for ' . $payment->get_source() . ' plugin.';
			$this->error    = new WP_Error( 'instamojo_error', $currency_error );
			return;
		}

		$method_data = $this->createRequest( $payment, true );
		if ( isset( $method_data['action'] ) ) {
			// Update gateway results in payment.
			$payment->set_transaction_id( $method_data['id'] );
			$payment->set_action_url( $method_data['action'] );
		}
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

		$payment_request_id = $payment->get_transaction_id();

		// Webhook action.
		if ( filter_has_var( INPUT_POST, 'mac' ) ) {
			// Add note.
			$note = sprintf(
				/* translators: %s: Instamojo */
				__( 'Webhook requested by %s.', 'knit-pay' ),
				__( 'Instamojo', 'pronamic_ideal' )
			);
			$payment->add_note( $note );

			// Log webhook request.
			do_action( 'pronamic_pay_webhook_log_payment', $payment );
		}

		try {
			$this->instamojo_api = new Instamojo( $this->config->client_id, $this->config->client_secret, $this->test_mode );

			$payment_request = $this->instamojo_api->get_payment_request_by_id( $payment_request_id );

			if ( empty( $payment_request->payments ) && isset( $_GET['payment_status'] ) && 'Failed' === $_GET['payment_status'] ) {
				$payment->set_status( PaymentStatus::CANCELLED );
				$payment->add_note( 'Instamojo Payment Status: ' . $_GET['payment_status'] . '<br>Payment Request ID: ' . $payment_request_id . '<br>Payment ID: ' . $_GET['payment_id'] );
				return;
			}

			if ( empty( $payment_request->payments ) ) {
				$payment->add_note( 'Payments not found for Payment Request ID: ' . $payment_request_id );
				return;
			}

			$payment_request_status = $payment_request->status;

			if ( isset( $payment_request_status ) ) {
				$payment_id = explode( '/', rtrim( reset( $payment_request->payments ), '/ ' ) );
				$payment_id = end( $payment_id );

				$payment_details = $this->instamojo_api->get_payment_by_id( $payment_id );

				if ( $payment_details->status ) {
					$this->update_missing_payment_details( $payment, $payment_details );
					$payment->set_transaction_id( $payment_id );
				}

				$payment->set_status( Statuses::transform_payment_status( $payment_details->status ) );
				$payment->add_note( 'Instamojo Payment Request Status: ' . $payment_request_status . '<br>Instamojo Payment Status: ' . var_export( $payment_details->status, true ) . '<br>Payment Request ID: ' . $payment_request_id . '<br>Payment ID: ' . $payment_id );
			}
		} catch ( Exception $e ) {
			$this->error = new WP_Error( 'instamojo_error', $e->getMessage() );
		}
	}

	private function update_missing_payment_details( Payment $payment, $payment_details ) {
		$customer = $payment->get_customer();
		$address  = $payment->get_billing_address();
		if ( ! isset( $address ) ) {
			$address = new Address();
		}
		if ( empty( $customer->get_name() ) ) {
			$name = new ContactName();
			$name->set_full_name( $payment_details->name );
			$address->set_name( $name );
			$customer->set_name( $name );
		}
		if ( empty( $customer->get_email() ) ) {
			$address->set_email( $payment_details->email );
			$customer->set_email( $payment_details->email );
			$payment->email = $payment_details->email;

			$user = get_user_by( 'email', $payment_details->email );
			if ( false !== $user ) {
				$payment->user_id = $user->ID;
			}
		}
		if ( empty( $customer->get_phone() ) ) {
			$address->set_phone( $payment_details->phone );
			$customer->set_phone( $payment_details->phone );
		}
		$payment->set_customer( $customer );
		$payment->set_billing_address( $address );
	}
}
