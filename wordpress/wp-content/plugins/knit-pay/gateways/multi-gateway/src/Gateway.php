<?php
namespace KnitPay\Gateways\MultiGateway;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Exception;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Core\Util as Core_Util;


/**
 * Title: Multi Gateway Gateway
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 4.0.0
 */
class Gateway extends Core_Gateway {

	/**
	 * Constructs and initializes an Multi gateway
	 *
	 * @param Config $config
	 *            Config.
	 */
	public function __construct( Config $config ) {

		parent::__construct( $config );

		$this->set_method( self::METHOD_HTML_FORM );
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
		$payment->set_transaction_id( $payment->key . '_' . $payment->get_id() );
		$payment->set_action_url( $payment->get_return_url() );
	}

	/**
	 * Update status of the specified payment.
	 *
	 * @param Payment $payment
	 *            Payment.
	 */
	public function update_status( Payment $payment ) {
		$child_config_id = 0;
		if ( ! isset( $_POST['child-config-id'] ) ) {
			return;
		}

		$child_config_id = $_POST['child-config-id'];
		$payment->set_config_id( $child_config_id );

		$gateway = Plugin::get_gateway( $child_config_id );

		if ( ! $gateway ) {
			return;
		}

		// Start payment at the gateway.
		// @see start_payment of /wp-pay/core/src/Plugin.php
		try {
			$gateway->start( $payment );

			// Add gateway errors as payment notes.
			$error = $gateway->get_error();

			if ( $error instanceof \WP_Error ) {
				$message = $error->get_error_message();
				$code    = $error->get_error_code();

				if ( ! \is_int( $code ) ) {
					$message = sprintf( '%s: %s', $code, $message );
					$code    = 0;
				}

				throw new \Exception( $message, $code );
			}
		} catch ( \Exception $error ) {
			$message = $error->getMessage();

			// Maybe include error code in message.
			$code = $error->getCode();

			if ( $code > 0 ) {
				$message = \sprintf( '%s: %s', $code, $message );
			}

			// Add note.
			$payment->add_note( $message );

			// Set payment status.
			$payment->set_status( PaymentStatus::FAILURE );
		}

		$payment->add_note( '"' . get_the_title( $child_config_id ) . '" Payment Gateway Configuration selected from Multiple Payment Gateway options.' );
		// Save payment.
		$payment->save();

		// Throw/rethrow exception.
		if ( $error instanceof \Exception ) {
			throw $error;
		}

		$gateway->redirect( $payment );
	}

	/**
	 * Redirect via HTML.
	 *
	 * @param Payment $payment The payment to redirect for.
	 * @return void
	 */
	public function redirect_via_html( Payment $payment ) {
		$title               = 'Payment Method';
		$payment_description = 'Select the payment method using which you want to make the payment.';
		if ( headers_sent() ) {
			parent::redirect_via_html( $payment );
		} else {
			Core_Util::no_cache();

			include 'views/redirect-via-html-select-gateway.php';
		}

		exit;
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
		$form_inner = '<hr>';

		foreach ( $this->config->enabled_payment_gateways as $config_id ) {
			$form_inner .= '<label for="' . $config_id . '"><input type="radio" id="' . $config_id . '" name="child-config-id" value="' . $config_id . '" onclick="document.pronamic_ideal_form.submit()"> ' . get_the_title( $config_id ) . '</label>';
		}
		$form_inner .= '<hr>';

		$action_url = $payment->get_action_url();

		if ( empty( $action_url ) ) {
			throw new \Exception( 'Action URL is empty, can not get form HTML.' );
		}

		return sprintf(
			'<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">%s</form>',
			esc_attr( $action_url ),
			$form_inner
		);

	}
}
