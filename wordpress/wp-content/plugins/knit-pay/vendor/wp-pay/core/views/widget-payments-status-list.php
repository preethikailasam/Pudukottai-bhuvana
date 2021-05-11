<?php
/**
 * Widget Payment Status List
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<div class="pronamic-pay-status-widget">
	<ul class="pronamic-pay-status-list">

		<?php foreach ( $states as $payment_status => $label ) : ?>

			<li class="<?php echo esc_attr( 'payment_status-' . $payment_status ); ?>">
				<a href="<?php echo esc_attr( add_query_arg( 'post_status', $payment_status, $url ) ); ?>">
					<?php

					$count = isset( $counts->$payment_status ) ? $counts->$payment_status : 0;

					printf(
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$label,
						'<strong>' . sprintf(
							/* translators: %s: Number payments */
							esc_html( _n( '%s payment', '%s payments', $count, 'pronamic_ideal' ) ),
							esc_html( number_format_i18n( $count ) )
						) . '</strong>'
					);

					?>
				</a>
			</li>

		<?php endforeach; ?>

	</ul>
</div>
