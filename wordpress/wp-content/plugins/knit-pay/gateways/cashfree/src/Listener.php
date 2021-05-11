<?php
namespace KnitPay\Gateways\Cashfree;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: Cashfree Webhook Listner
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.0.0
 * @since 2.4
 */
class Listener {


	public static function listen() {
		if ( ! filter_has_var( INPUT_GET, 'kp_cashfree_webhook' ) || ! filter_has_var( INPUT_POST, 'orderId' ) ) {
			return;
		}

		$payment = get_pronamic_payment_by_transaction_id( $_POST['orderId'] );

		if ( null === $payment ) {
			return;
		}

		// Add note.
		$note = sprintf(
		/* translators: %s: Cashfree */
			__( 'Webhook requested by %s.', 'knit-pay' ),
			__( 'Cashfree', 'knit-pay' )
		);

		$payment->add_note( $note );

		// Log webhook request.
		do_action( 'pronamic_pay_webhook_log_payment', $payment );

		// Update payment.
		Plugin::update_payment( $payment, false );
		exit;
	}
}
