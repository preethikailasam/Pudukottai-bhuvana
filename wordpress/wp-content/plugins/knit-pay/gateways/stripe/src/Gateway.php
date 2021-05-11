<?php
namespace KnitPay\Gateways\Stripe;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Payments\FailureReason;
use WP_Error;


/**
 * Title: Stripe Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 3.1.0
 */
class Gateway extends Core_Gateway {


	const NAME = 'stripe';

	/**
	 * Constructs and initializes an Stripe gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
		);

		\Stripe\Stripe::setAppInfo( 'Knit Pay', null, 'https://www.knitpay.org/' );
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::ALIPAY,
			PaymentMethods::CREDIT_CARD,
			PaymentMethods::IDEAL,
			PaymentMethods::BANCONTACT,
			PaymentMethods::GIROPAY,
			PaymentMethods::EPS,
			PaymentMethods::SOFORT,
			PaymentMethods::DIRECT_DEBIT,
			PaymentMethods::AFTERPAY,
			PaymentMethods::STRIPE,
		);
	}

	/**
	 * Get available payment methods.
	 *
	 * @return array<int, string>
	 * @see Core_Gateway::get_available_payment_methods()
	 */
	public function get_available_payment_methods() {
		if ( ! empty( $this->config->enabled_payment_methods ) ) {
			$this->config->enabled_payment_methods[] = 'stripe';
		}
		return $this->config->enabled_payment_methods;
	}

	/**
	 * Start.
	 *
	 * @see Core_Gateway::start()
	 *
	 * @param Payment $payment Payment.
	 */
	public function start( Payment $payment ) {
		if ( self::MODE_LIVE === $payment->get_mode() && ! $this->config->is_live_set() ) {
			$this->error = new WP_Error( 'stripe_error', 'Stripe is not connected in Live mode.' );
			return;
		}

		if ( self::MODE_TEST === $payment->get_mode() && ! $this->config->is_test_set() ) {
			$this->error = new WP_Error( 'stripe_error', 'Stripe is not connected in Test mode.' );
			return;
		}

		$this->stripe_session_id = $payment->get_meta( 'stripe_session_id' );

		// Return if session_id already exists for this payments.
		if ( $this->stripe_session_id ) {
			return;
		}

		$secret_key = $this->config->get_secret_key();

		$stripe = new \Stripe\StripeClient(
			$secret_key
		);

		$session_data = $this->create_session_data( $payment );

		$stripe_session = $stripe->checkout->sessions->create( $session_data );

		$payment->set_meta( 'stripe_session_id', $stripe_session->id );
		$this->stripe_session_id = $stripe_session->id;

		$payment->set_transaction_id( $stripe_session->payment_intent );

		if ( self::MODE_LIVE === $payment->get_mode() && 'https' !== wp_parse_url( $payment->get_pay_redirect_url() )['scheme'] ) {
			$this->error = new WP_Error( 'stripe_error', 'Live Stripe.js integrations must use HTTPS. For more information: https://stripe.com/docs/security/guide#tls' );
			return;
		}

		$payment->set_action_url( $payment->get_pay_redirect_url() );
	}

	protected function create_session_data( $payment ) {
		$customer = $payment->get_customer();

		$payment_amount   = $this->get_payment_amount( $payment );
		$payment_currency = $this->get_payment_currency( $payment );

		$payment_method_types = PaymentMethods::transform( $payment->get_method(), $this->config->enabled_payment_methods );

		$session_data = array(
			'success_url'          => $payment->get_return_url(),
			'client_reference_id'  => $payment->id,
			'customer_email'       => $customer->get_email(),
			'cancel_url'           => $payment->get_return_url(),
			'payment_method_types' => $payment_method_types,
			'line_items'           => array(
				array(
					'price_data' => array(
						'currency'     => $payment_currency,
						'unit_amount'  => $payment_amount,
						'product_data' => array(
							'name' => $payment->get_description(),
						),
					),
					'quantity'   => 1,
				),
			),
			'mode'                 => 'payment',
			'metadata'             => array( 'knitpay_payment_id' => $payment->id ),
		);
		// TODO: improve  line items.

		return $session_data;
	}

	protected function get_payment_amount( Payment $payment ) {
		$stripe_payment_currency = $this->config->payment_currency;
		$exchange_rate           = $this->config->exchange_rate;

		$payment_amount   = $payment->get_total_amount()->format() * 100;
		$payment_currency = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		if ( ! empty( $stripe_payment_currency ) && $stripe_payment_currency !== $payment_currency ) {
			$payment_amount = $exchange_rate * $payment_amount;
		}
		return round( $payment_amount );
	}

	private function get_payment_currency( Payment $payment ) {
		$stripe_payment_currency = $this->config->payment_currency;
		$payment_currency        = $payment->get_total_amount()->get_currency()->get_alphabetic_code();

		if ( ! empty( $stripe_payment_currency ) && $stripe_payment_currency !== $payment_currency ) {
			$payment_currency = $stripe_payment_currency;
		}

		return $payment_currency;
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

		$publishable_key         = $this->config->get_publishable_key();
		$this->stripe_session_id = $payment->get_meta( 'stripe_session_id' );

		$form_inner = $this->get_output_html( $payment );
		$form_inner = '<button class="pronamic-pay-btn" id="checkout-button">Checkout</button>';

		$form_inner .= '<script src="https://js.stripe.com/v3/">

        </script><script type="text/javascript">
	    // Create an instance of the Stripe object with your publishable API key
	    var stripe = Stripe("' . $publishable_key . '");
	    var checkoutButton = document.getElementById("checkout-button");
        result = stripe.redirectToCheckout({sessionId: "' . $this->stripe_session_id . '"});
	    
	    </script>';
		return $form_inner;

	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		if ( PaymentStatus::SUCCESS === $payment->get_status() ) {
			return;
		}

		$secret_key = $this->config->get_secret_key();
		$stripe     = new \Stripe\StripeClient( $secret_key );

		$stripe_payment_intents = $stripe->paymentIntents->retrieve( $payment->get_transaction_id(), array() );

		// Return if payment not attempted yet.
		if ( empty( $stripe_payment_intents->charges->total_count ) ) {
			// Mark Payment as cancelled if it's return status check.
			if ( filter_has_var( INPUT_GET, 'key' ) && filter_has_var( INPUT_GET, 'payment' ) ) {
				$payment->set_status( PaymentStatus::CANCELLED );
			}
			return;
		}

		$payment->set_status( Statuses::transform( $stripe_payment_intents->status ) );
		$note = 'Stripe Charge ID: ' . $stripe_payment_intents->charges->data[0]->id . '<br>Stripe Payment Status: ' . $stripe_payment_intents->status;

		if ( isset( $stripe_payment_intents->last_payment_error ) ) {
			$failure_reason = new FailureReason();
			$failure_reason->set_message( $stripe_payment_intents->last_payment_error->message );
			$failure_reason->set_code( $stripe_payment_intents->last_payment_error->code );
			$payment->set_failure_reason( $failure_reason );
			$payment->set_status( PaymentStatus::FAILURE );
			$note .= '<br>Error Message: ' . $stripe_payment_intents->last_payment_error->message;
		}
		$payment->add_note( $note );

	}
}
