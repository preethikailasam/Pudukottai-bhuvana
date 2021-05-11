<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

function zoom_theme_customize_custom_text( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_custom_text',
		array(
			'title' => esc_html__( 'Custom Text / Label', 'zoom-lite' ),
			'description' => esc_html__( 'Use the following options to change the default navigation text on your site', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 3,
			'panel' => 'zoom_misc_panel'
		)
	);

		
	// Newer posts
	$wp_customize->add_setting( 'misc_txt_np', array(
		'default'	        => $default_opt['misc_txt_np'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_np_control', array(
		'label'    => esc_html__( 'Next Post', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_np',
		'type'     => 'text',
	) ) );
	
	
	// Older posts
	$wp_customize->add_setting( 'misc_txt_op', array(
		'default'	        => $default_opt['misc_txt_op'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_op_control', array(
		'label'    => esc_html__( 'Previous Post', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_op',
		'type'     => 'text',
	) ) );
	
	
	// Next
	$wp_customize->add_setting( 'misc_txt_next', array(
		'default'	        => $default_opt['misc_txt_next'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_next_control', array(
		'label'    => esc_html__( 'Next Navigation', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_next',
		'type'     => 'text',
	) ) );
	
	
	// Previous
	$wp_customize->add_setting( 'misc_txt_prev', array(
		'default'	        => $default_opt['misc_txt_prev'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_prev_control', array(
		'label'    => esc_html__( 'Previous Navigation', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_prev',
		'type'     => 'text',
	) ) );
	
	
	// Pages
	$wp_customize->add_setting( 'misc_txt_pg', array(
		'default'	        => $default_opt['misc_txt_pg'],
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_pg_control', array(
		'label'    => esc_html__( 'Pagination', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_pg',
		'type'     => 'text',
	) ) );
	

	// Related Post Note
	$wp_customize->add_setting( 'misc_txt_rp', array(
		'default'	        => $default_opt['misc_txt_rp'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_rp_control', array(
		'label'    => esc_html__( 'Related Post Note', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_rp',
		'type'     => 'text',
	) ) );
	
	
	// Comment Note
	$wp_customize->add_setting( 'misc_txt_comment_note', array(
		'default'	        => $default_opt['misc_txt_comment_note'],
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_txt_comment_note_control', array(
		'label'    => esc_html__( 'Comment Form Note', 'zoom-lite' ),
		'section'  => 'zoom_theme_custom_text',
		'settings' => 'misc_txt_comment_note',
		'type'     => 'text',
	) ) );
	
	

}

add_action( 'customize_register', 'zoom_theme_customize_custom_text' );	