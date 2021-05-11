<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_comments_settings( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();	
	
	
	// Post Excerpts
    $wp_customize->add_section( 'zoom_theme_comment_blog_section' , array(
	    'title'       => esc_html__( 'Post Comments', 'zoom-lite' ),
	    'priority'    => 21,
	    'description' => esc_html__( 'Change how this theme displays the comments', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );


	// Disable All comment form in Post / Page
    $wp_customize->add_setting( 'post_disable_all_comment_form', array(
        'default'           => $default_opt['post_disable_all_comment_form'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'post_disable_all_comment_form_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Disable Comment Form', 'zoom-lite' ),
		'description' => esc_html__( 'Use this option to disable the comment form from all Posts and Pages', 'zoom-lite' ),
		'section'     => 'zoom_theme_comment_blog_section',
		'settings'    => 'post_disable_all_comment_form',
		'priority'    => 1
	) ) );
	
	// Show/Hide Disable Comments Note
    $wp_customize->add_setting( 'post_hide_disable_comments_note', array(
        'default'           => $default_opt['post_hide_disable_comments_note'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'post_hide_disable_comments_note_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Hide Comments are closed Note?', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide the default note when the comments are closed.', 'zoom-lite' ),
		'section'     => 'zoom_theme_comment_blog_section',
		'settings'    => 'post_hide_disable_comments_note',
		'priority'    => 2
	) ) );
	
	// Author Badge Color
    $wp_customize->add_setting( 'post_author_badge_color', array(
        'default'           => $default_opt['post_author_badge_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_author_badge_color_control', array(
        'label'	   => esc_html__( 'Author Badge Color', 'zoom-lite' ),
		'description' => esc_html__( 'Use this option to change the badge color of comment author', 'zoom-lite' ),
        'section'  => 'zoom_theme_comment_blog_section',
        'settings' => 'post_author_badge_color',
		'priority'    => 3
    ) ) );
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_post_comments_settings' );