<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_button( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_site_button',
		array(
			'title' => esc_html__( 'Buttons', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 4,
			'panel' => 'zoom_site_styles'
		)
	);
	
	// Read More Button Color
	$wp_customize->add_setting(
		'button_readmore',
		array(
			'default' => $default_opt['button_readmore'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'zoom_sanitize_choices'
		)
	);
	
	$wp_customize->add_control( 'button_readmore_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Read More Button Color', 'zoom-lite' ),
			'description' => esc_html__( 'Default color for Read More button is : Blue', 'zoom-lite' ),
			'section' => 'zoom_theme_site_button',
			'settings' => 'button_readmore',
			'choices' => array(
				'defbtn' => esc_html__( 'Default', 'zoom-lite' ),
				'blue' => esc_html__( 'Blue', 'zoom-lite' ),
				'green' => esc_html__( 'Green', 'zoom-lite' ),
				'grey' => esc_html__( 'Grey', 'zoom-lite' ),
				'orange' => esc_html__( 'Orange', 'zoom-lite' ),
				'pink' => esc_html__( 'Pink', 'zoom-lite' ),
				'purple' => esc_html__( 'Purple', 'zoom-lite' ),
				'red' => esc_html__( 'Red', 'zoom-lite' ),
				'turq' => esc_html__( 'Turquoise', 'zoom-lite' ),
			),
			'priority' => 1
	) );
	
	// Read More Button Position
	$wp_customize->add_setting( 'button_readmore_pos', array(
		'default'	        => $default_opt['button_readmore_pos'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'zoom_sanitize_choices',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'button_readmore_pos_control', array(
		'label'    => esc_html__( 'Read More Button Position', 'zoom-lite' ),
		'section'  => 'zoom_theme_site_button',
		'settings' => 'button_readmore_pos',
		'type'     => 'radio',
		'choices'  => array(
			'left' => 'Left',
			'center' => 'Center',
			'right' => 'Right'
			),
		'priority' => 2
	) ) );
	
	// Site Navigation Button
	$wp_customize->add_setting(
		'button_nav',
		array(
			'default' => $default_opt['button_nav'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'zoom_sanitize_choices'
		)
	);
	
	$wp_customize->add_control( 'button_nav_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Navigation Button Color', 'zoom-lite' ),
			'description' => esc_html__( 'Navigation Button is for Next / Prev Posts, images and comments', 'zoom-lite' ),
			'section' => 'zoom_theme_site_button',
			'settings' => 'button_nav',
			'choices' => array(
				'defbtn' => esc_html__( 'Default', 'zoom-lite' ),
				'blue' => esc_html__( 'Blue', 'zoom-lite' ),
				'green' => esc_html__( 'Green', 'zoom-lite' ),
				'grey' => esc_html__( 'Grey', 'zoom-lite' ),
				'orange' => esc_html__( 'Orange', 'zoom-lite' ),
				'pink' => esc_html__( 'Pink', 'zoom-lite' ),
				'purple' => esc_html__( 'Purple', 'zoom-lite' ),
				'red' => esc_html__( 'Red', 'zoom-lite' ),
				'turq' => esc_html__( 'Turquoise', 'zoom-lite' ),
			),
			'priority' => 3
	) );
	
}

add_action( 'customize_register', 'zoom_theme_customize_button' );