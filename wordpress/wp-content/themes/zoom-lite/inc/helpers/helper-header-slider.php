<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_header_slider() {
	
	$ids_array = get_theme_mod( 'header_slider' );
	
	ob_start();
	
	if ( is_array( $ids_array ) && ! empty( $ids_array ) ) {

		echo '<div class="zoom-slider-wrapper slider-wrapper theme-default"><div class="ribbon"></div><div id="slider" class="nivoSlider zoom-image-slider">';
		foreach ( $ids_array as $id ) {
			
			$img = wp_get_attachment_image_src( $id, 'zoom-img-slider' );
			$slider_img = $img[0];
			?>
            <img src="<?php echo esc_url( $slider_img ); ?>" />
			<?php
            }
		echo '</div></div>';
	
	} else {
		$t_url = get_template_directory_uri().'/assets/images/header/defaut-slider/';
		echo '<div class="zoom-slider-wrapper slider-wrapper theme-default"><div class="ribbon"></div><div id="slider" class="nivoSlider zoom-image-slider">';
		echo '<img src="'.esc_url( $t_url.'slider1.jpg' ).'" /><img src="'.esc_url( $t_url.'slider2.jpg' ).'" /><img src="'.esc_url( $t_url.'slider3.jpg' ).'" />';
		echo '</div></div>';
	}
	
	$header_slider = ob_get_clean();
	return $header_slider;
	
}