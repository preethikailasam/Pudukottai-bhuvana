<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_bottom_bar_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
    // Header Logo
    $wp_customize->add_section( 'zoom_theme_b_bar_section' , array(
	    'title'       => esc_html__( 'Bottom Bar', 'zoom-lite' ),
	    'priority'    => 20,
		'description' => esc_html__( 'You can set your Bottom Bar with the following fields', 'zoom-lite' ),
		'panel' => 'zoom_site_branding',
	) );
	
	// Bottom Bar Enable / Disable
	$wp_customize->add_setting(
		'bottom_bar_active',
		array(
			'default' => $default_opt['bottom_bar_active'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'bottom_bar_active_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Enable Bottom Bar', 'zoom-lite' ),
			'description' => esc_html__( 'This option used to enable / disable the Bottom Bar', 'zoom-lite' ),
			'section' => 'zoom_theme_b_bar_section',
			'settings' => 'bottom_bar_active',
			'priority' => 1
	) ) );

	// Copyright information
	$wp_customize->add_setting( 'bottom_bar_copyright', array(
		'default'           => $default_opt['bottom_bar_copyright'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'zoom_sanitize_allowed_html',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'bottom_bar_copyright_control', array(
		'label'    => esc_html__( 'Copyright Information', 'zoom-lite' ),
		'type'		=> 'textarea',
		'description' => esc_html__( 'You can set Site Copyright in the bottom bar area with the following option. You also can change with another text to fit your needs', 'zoom-lite' ),
		'section'  => 'zoom_theme_b_bar_section',
		'settings' => 'bottom_bar_copyright',
	) ) );	
	
}

add_action( 'customize_register', 'zoom_theme_customize_bottom_bar_options' );