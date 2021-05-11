<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_settings( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();	
	
	
	// Post Excerpts
    $wp_customize->add_section( 'zoom_theme_blog_section' , array(
	    'title'       => esc_html__( 'Post Excerpts', 'zoom-lite' ),
	    'priority'    => 20,
	    'description' => esc_html__( 'Change how Zoom displays posts', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );

	$wp_customize->add_setting( 'post_content', array(
		'default'	        => $default_opt['post_content'],
		'sanitize_callback' => 'zoom_sanitize_choices',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'post_content_control', array(
		'label'    => esc_html__( 'Post content', 'zoom-lite' ),
		'section'  => 'zoom_theme_blog_section',
		'settings' => 'post_content',
		'type'     => 'radio',
		'choices'  => array(
			'excerpts' => 'Excerpts',
			'full' => 'Full content',
			),
	) ) );
	

	$wp_customize->add_setting( 'excerpts_text', array(
		'default'	        => $default_opt['excerpts_text'],
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'excerpts_text_control', array(
		'label'    => esc_html__( 'Excerpts Text', 'zoom-lite' ),
		'description' => esc_html__( 'Sets the link text like Read More or Continue Reading...', 'zoom-lite' ),
		'section'  => 'zoom_theme_blog_section',
		'settings' => 'excerpts_text',
		'type'     => 'text',
	) ) );
	

	$wp_customize->add_setting( 'excerpt_length', array(
		'default'	        => $default_opt['excerpt_length'],
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'excerpt_length_control', array(
		'label'    => esc_html__( 'Excerpt Length', 'zoom-lite' ),
		'description' => esc_html__( 'The number of words for excerpts. When that number is reached the post will be interrupted by Excerpts Text above.', 'zoom-lite' ),
		'section'  => 'zoom_theme_blog_section',
		'settings' => 'excerpt_length',
		'type'     => 'text',
	) ) );
	
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_post_settings' );