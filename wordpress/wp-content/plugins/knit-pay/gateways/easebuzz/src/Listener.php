<?php
namespace KnitPay\Gateways\Easebuzz;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: Easebuzz Webhook Listner
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 2.2.4.0
 */
class Listener {


	public static function listen() {
		if ( ! filter_has_var( INPUT_GET, 'easebuzz_webhook' ) ) {
			return;
		}

		$payment = get_pronamic_payment( $_POST['txnid'] );

		if ( null === $payment ) {
			return;
		}

		// Add note.
		$note = sprintf(
		/* translators: %s: Easebuzz */
			__( 'Webhook requested by %s.', 'knit-pay' ),
			__( 'Easebuzz', 'knit-pay' )
		);

		$payment->add_note( $note );

		// Log webhook request.
		do_action( 'pronamic_pay_webhook_log_payment', $payment );

		// Update payment.
		Plugin::update_payment( $payment, false );
		exit;
	}
}
