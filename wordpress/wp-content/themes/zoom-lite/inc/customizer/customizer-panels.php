<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_panels( $wp_customize ) {
	
	// Layout
	if ( ! $wp_customize->get_panel( 'zoom_theme_layout_panel' ) ) {
		
		$wp_customize->add_panel( 'zoom_theme_layout_panel', array(
			'priority'       => 25,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Site Layout','zoom-lite' ),
			'description'    => '',
		));
	}

	// Site Styles
	if ( ! $wp_customize->get_panel( 'zoom_site_styles' ) ) {
		
		$wp_customize->add_panel( 'zoom_site_styles', array(
			'priority'       => 26,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Site Styles','zoom-lite' ),
			'description'    => '',
		));
		
	}
	
	// Site Branding
	if ( ! $wp_customize->get_panel( 'zoom_site_branding' ) ) {
		
		$wp_customize->add_panel( 'zoom_site_branding', array(
			'priority'       => 27,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Site Branding','zoom-lite' ),
			'description'    => '',
		));
		
	}
	
	// Posts Settings
	if ( ! $wp_customize->get_panel( 'zoom_post_options' ) ) {
		
		$wp_customize->add_panel( 'zoom_post_options', array(
			'priority'       => 28,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Posts Settings','zoom-lite' ),
			'description'    => '',
		));
		
	}
	
	// Miscellaneous Settings
	if ( ! $wp_customize->get_panel( 'zoom_misc_panel' ) ) {
		
		$wp_customize->add_panel( 'zoom_misc_panel', array(
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Miscellaneous','zoom-lite' ),
			'description'    => '',
		));
		
	}
	
}

add_action( 'customize_register', 'zoom_theme_panels' );