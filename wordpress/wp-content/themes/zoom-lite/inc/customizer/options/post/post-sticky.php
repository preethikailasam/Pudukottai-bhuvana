<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_sticky_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_post_sticky_section' , array(
	    'title'       => esc_html__( 'Post Sticky ( Featured Post )', 'zoom-lite' ),
	    'priority'    => 23,
	    'description' => esc_html__( 'Define and set your featured post with the following options', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );
	

	// Sticky Background Color
    $wp_customize->add_setting( 'sticky_bg_col', array(
        'default'           => $default_opt['sticky_bg_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sticky_bg_col_control', array(
        'label'	   => esc_html__( 'Background Color', 'zoom-lite' ),
        'section'  => 'site_post_sticky_section',
        'settings' => 'sticky_bg_col',	
	) ) );
	
	// Sticky border
    $wp_customize->add_setting( 'sticky_bor', array(
        'default'           => $default_opt['sticky_bor'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'sticky_bor_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Border', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide sticky box border. Default: Show', 'zoom-lite' ),
		'section'     => 'site_post_sticky_section',
		'settings'    => 'sticky_bor',
	) ) );
	
	// Sticky Border Type
    $wp_customize->add_setting( 'sticky_bor_type', array(
        'default'           => $default_opt['sticky_bor_type'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( 'sticky_bor_type_control', array(
		'type' => 'select',
		'label'       => esc_html__( 'Border Type', 'zoom-lite' ),
		'description' => esc_html__( 'Set the border type here. Default: Dashed', 'zoom-lite' ),
		'section'     => 'site_post_sticky_section',
		'settings'    => 'sticky_bor_type',
		'choices' => array(
				'solid' => 'Solid',
				'dashed' => 'Dashed',
			),
	) );
	
	// Sticky Border Width
    $wp_customize->add_setting( 'sticky_bor_width', array(
        'default'           => $default_opt['sticky_bor_width'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'sticky_bor_width_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Border Width', 'zoom-lite' ),
		'description' => esc_html__( 'Set the width of border here. Default: 2px', 'zoom-lite' ),
		'section'     => 'site_post_sticky_section',
		'settings'    => 'sticky_bor_width',
		'choices' => array(
				'min' => '0',
				'max' => '10',
				'step' => '1',
				'conver' => 'px',
			),
	) ) );
	
	// Sticky Border Color
    $wp_customize->add_setting( 'sticky_bor_col', array(
        'default'           => $default_opt['sticky_bor_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sticky_bor_col_control', array(
        'label'	   => esc_html__( 'Border Color', 'zoom-lite' ),
        'section'  => 'site_post_sticky_section',
        'settings' => 'sticky_bor_col',	
	) ) );
	
	// Sticky Ribbon
    $wp_customize->add_setting( 'sticky_ribbon', array(
        'default'           => $default_opt['sticky_ribbon'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'sticky_ribbon_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Ribbon', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide sticky corner ribbon. Default: Show', 'zoom-lite' ),
		'section'     => 'site_post_sticky_section',
		'settings'    => 'sticky_ribbon',
	) ) );
	
	// Sticky Ribbon Text
	$wp_customize->add_setting( 'sticky_ribbon_txt', array(
		'default'	        => $default_opt['sticky_ribbon_txt'],
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sticky_ribbon_txt_control', array(
		'label'    => esc_html__( 'Ribbon Label', 'zoom-lite' ),
		'description' => esc_html__( 'The label of your sticky ribbon', 'zoom-lite' ),
		'section'  => 'site_post_sticky_section',
		'settings' => 'sticky_ribbon_txt',
		'type'     => 'text',
	) ) );
	
	// Sticky Ribbon Color
    $wp_customize->add_setting( 'sticky_ribbon_col', array(
        'default'           => $default_opt['sticky_ribbon_col'],
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sticky_ribbon_col_control', array(
        'label'	   => esc_html__( 'Ribbon Color', 'zoom-lite' ),
        'section'  => 'site_post_sticky_section',
        'settings' => 'sticky_ribbon_col',	
	) ) );

	
}

add_action( 'customize_register', 'zoom_theme_customize_post_sticky_options' );