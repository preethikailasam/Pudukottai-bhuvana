<?php
/**
 * The template for displaying search forms
 *
 * @package Zoom
 * @since 1.0
 */
?>
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php esc_html_e( 'Search', 'zoom-lite' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php echo esc_attr_e( 'Search', 'zoom-lite' ); ?>&hellip;" />
		<input type="submit" class="submit searchsubmit" name="submit" value="<?php echo esc_attr_e( 'Search', 'zoom-lite' ); ?>" />
	</form>
