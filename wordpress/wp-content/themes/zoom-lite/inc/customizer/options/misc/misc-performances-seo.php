<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_site_performances( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_site_performances',
		array(
			'title' => esc_html__( 'Site Performances &amp; SEO', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 2,
			'panel' => 'zoom_misc_panel'
		)
	);
	
	// Minify Stylesheet
	$wp_customize->add_setting(
		'misc_min_stylesheet',
		array(
			'default' => $default_opt['misc_min_stylesheet'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'misc_min_stylesheet_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Use a Minified Stylesheet', 'zoom-lite' ),
			'description' => esc_html__( 'Disable this option is not recommended. Minifying css stylesheets improves performance for your website overall by decreasing the load time.', 'zoom-lite' ),
			'section' => 'zoom_theme_site_performances',
			'settings' => 'misc_min_stylesheet',
			'priority' => 1
	) ) );
	
	// Image LazyLoad
	$wp_customize->add_setting(
		'misc_image_lazyload',
		array(
			'default' => $default_opt['misc_image_lazyload'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'misc_image_lazyload_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Use Image LazyLoad', 'zoom-lite' ),
			'description' => esc_html__( 'Check this option to boost your site speed performances by reducing the weight of long pages that include many images.', 'zoom-lite' ),
			'section' => 'zoom_theme_site_performances',
			'settings' => 'misc_image_lazyload',
			'priority' => 2
	) ) );
	
	// Structured Data Markup
	$wp_customize->add_setting(
		'misc_struct_data',
		array(
			'default' => $default_opt['misc_struct_data'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'misc_struct_data_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Use Structured Data Markup', 'zoom-lite' ),
			'description' => esc_html__( 'Structured data markup is a standard way to annotate your content so machines can understand it. Implementing it will help your website rank higher in search engines.', 'zoom-lite' ),
			'section' => 'zoom_theme_site_performances',
			'settings' => 'misc_struct_data',
			'priority' => 3
	) ) );
	
}

add_action( 'customize_register', 'zoom_theme_customize_site_performances' );