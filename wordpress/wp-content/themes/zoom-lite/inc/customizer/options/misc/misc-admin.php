<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_admin_settings( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_admin_settings',
		array(
			'title' => esc_html__( 'Admin Settings', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 4,
			'panel' => 'zoom_misc_panel'
		)
	);
	
	// About Page
	$wp_customize->add_setting(
		'misc_admin_about',
		array(
			'default' => $default_opt['misc_admin_about'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'misc_admin_about_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Show the About Zoom in Appearance Menu', 'zoom-lite' ),
			'description' => esc_html__( 'This page is intended to provide important informations about the Zoom theme : changelog, release note, documentation link.', 'zoom-lite' ),
			'section' => 'zoom_theme_admin_settings',
			'settings' => 'misc_admin_about',
			'priority' => 1
	) ) );
	
	// Help Link
	$wp_customize->add_setting(
		'misc_admin_topbar',
		array(
			'default' => $default_opt['misc_admin_topbar'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'misc_admin_topbar_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Show Optimize Button in Admin Bar', 'zoom-lite' ),
			'description' => esc_html__( 'This button links to the About Zoom page.', 'zoom-lite' ),
			'section' => 'zoom_theme_admin_settings',
			'settings' => 'misc_admin_topbar',
			'priority' => 2
	) ) );
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_admin_settings' );