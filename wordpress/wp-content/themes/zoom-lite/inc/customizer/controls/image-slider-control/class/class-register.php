<?php
/**
 * Customize Image Slider Control Class
 *
 * @package Zoom_Customize_Slider_Control
 */

/**
 * Class Zoom_Slider_Control
 */
class Zoom_Slider_Control {
	
	/**
	 * Plugin constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		
		add_action( 'customize_register', array( $this, 'include_controls' ) );
		
	}

	/**
	 * Include Custom Controls
	 *
	 * Includes all our custom control classes.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function include_controls( $wp_customize ) {
		
		require_once get_template_directory() . '/inc/customizer/controls/image-slider-control/class/class-control.php';
		
		$wp_customize->register_control_type( 'Zoom_Customize_Slider_Control' );
		
	}


}