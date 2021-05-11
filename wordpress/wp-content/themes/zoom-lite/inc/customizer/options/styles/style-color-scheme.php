<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_color_scheme( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_color_scheme_section' , array(
	    'title'       => esc_html__( 'Color Scheme', 'zoom-lite' ),
	    'priority'    => 3,
	    'description' => esc_html__( 'Define and set your site your site color scheme', 'zoom-lite' ),
		'panel' => 'zoom_site_styles',
	) );
	
	$wp_customize->add_setting( 'color_scheme', array(
		'default'           => $default_opt['color_scheme'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'zoom_sanitize_choices',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'color_scheme_control', array(
		'label'    => esc_html__( 'Site Color Scheme', 'zoom-lite' ),
		'section'  => 'site_color_scheme_section',
		'settings' => 'color_scheme',
		'type'     => 'radio',
		'choices'  => array(
			'default_scheme' => esc_html__( 'Default', 'zoom-lite' ),
			'dark_blue' => esc_html__( 'Dark Blue', 'zoom-lite' ),
			'dark_red' => esc_html__( 'Dark Red', 'zoom-lite' ),
			),
	'priority'    => 1
	) ) );
	
	
}
add_action( 'customize_register', 'zoom_theme_customize_color_scheme' );