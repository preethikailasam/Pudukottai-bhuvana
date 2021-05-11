<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_sidebar_layout( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section( 'zoom_sidebar_layout', array(
		'title'    => esc_html__( 'Sidebar Layout', 'zoom-lite' ),
		'priority' => 29,
		'panel' => 'zoom_theme_layout_panel'
	));
	
	// Sidebar Width
	$wp_customize->add_setting( 'sidebar_width', array(
		'default'           => $default_opt['sidebar_width'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'sidebar_width_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Sidebar Width', 'zoom-lite' ),
		'description' => esc_html__( 'Set the width of Sidebar area. Default : 30%', 'zoom-lite' ),
		'section'     => 'zoom_sidebar_layout',
		'settings'    => 'sidebar_width',
		'choices' => array(
				'min' => '25',
				'max' => '50',
				'step' => '1',
				'conver' => '%',
				'separator' => '50',
			),
		'priority'    => 16
	) ) );
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_sidebar_layout' );