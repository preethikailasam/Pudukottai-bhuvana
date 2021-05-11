<?php
/**
 * Title: Multi Gateway Payment Select
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   4.0.0
 */

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php echo $title; ?></title>

		<?php wp_print_styles( 'pronamic-pay-redirect' ); ?>

		<script>
		// Break out of iframe.
		if ( window.top.location !== window.location ) {
			window.top.location = window.location;
		}
		</script>
	</head>

	<?php

	$auto_submit = true;

	if ( PRONAMIC_PAY_DEBUG ) {
		$auto_submit = false;
	}

	$onload = $auto_submit ? 'document.forms[0].submit();' : '';

	?>

	<body onload="<?php esc_attr( $onload ); ?>">
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container">
				<h1><?php echo $title; ?></h1>

				<p>
					<?php echo $payment_description; ?>
				</p>

				<?php

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->get_form_html( $payment, $auto_submit );

				?>
			</div>
		</div>
	</body>
</html>
