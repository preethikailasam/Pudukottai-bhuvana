<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_site_layout( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section( 'zoom_site_layout', array(
		'title'    => esc_html__( 'Main Layout', 'zoom-lite' ),
		'priority' => 28,
		'panel' => 'zoom_theme_layout_panel'
	));
	
	$wp_customize->add_setting( 'site_layout', array(
		'default'           => $default_opt['site_layout'],
		'sanitize_callback' => 'sanitize_key'
	));
	
	$wp_customize->add_control( new Zoom_Layout_Select_Control( $wp_customize, 'site_layout_control', array(
		'type' => 'radio_image',
		'label'       => esc_html__( 'Main Layout', 'zoom-lite' ),
		'description' => esc_html__( 'Choose a layout for your site. Please click Hide Controls button below to see how the layout actually looks.', 'zoom-lite' ),
		'section'     => 'zoom_site_layout',
		'settings'    => 'site_layout',
		'choices'     => array(
			'boxed' => array(
				'label' => esc_html__( 'Boxed', 'zoom-lite' ),
				'url'   => '%sboxed.png'
			),
			'wide'    => array(
				'label' => esc_html__( 'Wide', 'zoom-lite' ),
				'url'   => '%swide.png'
			)
		),
		'priority'    => 9,
	) ) );
	
	$wp_customize->add_setting( 'site_maxwidth', array(
		'default'           => $default_opt['site_maxwidth'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'site_maxwidth_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Site Maximum Width', 'zoom-lite' ),
		'description' => esc_html__( 'Set your site Max Width. This option only work when you choose Boxed layout type. Default : 1200px', 'zoom-lite' ),
		'section'     => 'zoom_site_layout',
		'settings'    => 'site_maxwidth',
		'choices' => array(
				'min' => '960',
				'max' => '1200',
				'step' => '1',
				'conver' => 'px',
				'separator' => '50',
			),
		'priority'    => 10
	) ) );	
	
}

add_action( 'customize_register', 'zoom_theme_customize_site_layout' );