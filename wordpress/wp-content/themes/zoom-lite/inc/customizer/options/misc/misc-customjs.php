<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

function zoom_theme_customize_custom_js( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_custom_js',
		array(
			'title' => esc_html__( 'Additional JS', 'zoom-lite' ),
			'description' => esc_html__( 'Make sure your code inside <script> and </script>. For example: <script>alert("aaa");</script>', 'zoom-lite' ),
			'capability' => 'edit_css',
			'priority' => 3,
			'panel' => 'zoom_misc_panel'
		)
	);

		
	// Custom JS
	$wp_customize->add_setting( 'misc_custom_js', array(
		'default'	        => $default_opt['misc_custom_js'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_html',
	) );

	$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'misc_custom_js_control', array(
		'label'    => '',
		'section'  => 'zoom_theme_custom_js',
		'settings' => 'misc_custom_js',
		'code_type'   => 'text/text'
	) ) );

}

add_action( 'customize_register', 'zoom_theme_customize_custom_js' );	