<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_images_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_styles_image_section' , array(
	    'title'       => esc_html__( 'Post Images', 'zoom-lite' ),
	    'priority'    => 22,
	    'description' => esc_html__( 'Define and set your post images style', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );

	
	// Show Featured Image on Posts List
    $wp_customize->add_setting( 'featured_image_on_post_list', array(
        'default'           => $default_opt['featured_image_on_post_list'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'featured_image_on_post_list_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Featured Image on blog List', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide post Featured Image in blog list', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'featured_image_on_post_list',
		'choices' => array(
			'action' => 'yes',
			'actionid' => json_encode( array( 'featured_image_placeholder_control', 'post_blog_thumb_size_control' ) ),
		),
		'priority'    => 1
	) ) );
	
	// Show Featured Image Placeholder
    $wp_customize->add_setting( 'featured_image_placeholder', array(
        'default'           => $default_opt['featured_image_placeholder'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'featured_image_placeholder_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Image Placeholder', 'zoom-lite' ),
		'description' => esc_html__( 'An image placeholder is a dummy image designed if there are no Featured Image in your Posts or Pages', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'featured_image_placeholder',
		'priority'    => 1
	) ) );
	
	//  Featured Image SIZE on Posts List ( Blog List )
    $wp_customize->add_setting( 'post_blog_thumb_size', array(
        'default'           => $default_opt['post_blog_thumb_size'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( 'post_blog_thumb_size_control', array(
		'type' => 'select',
		'label'       => esc_html__( 'Image Size', 'zoom-lite' ),
		'description' => esc_html__( 'Available size: thumbnail, medium, medium_large, large and full', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'post_blog_thumb_size',
		'choices' => array(
				'thumbnail' => esc_html__( 'Thumbnail', 'zoom-lite' ),
				'medium' => esc_html__( 'Medium', 'zoom-lite' ),
				'mlarge' => esc_html__( 'Medium Large', 'zoom-lite' ),
				'large' => esc_html__( 'Large', 'zoom-lite' ),
				'full' => esc_html__( 'Full', 'zoom-lite' ),
			),
		'priority'    => 2
	) );
	
	// Show Featured Image on Full Post or Page
    $wp_customize->add_setting( 'featured_image_on_single', array(
        'default'           => $default_opt['featured_image_on_single'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'featured_image_on_single_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Featured Image on Single Post / Page', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide post Featured Image in single post or page view', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'featured_image_on_single',
		'priority'    => 3
	) ) );
	
	//  Featured Image SIZE on Single Posts / Page
    $wp_customize->add_setting( 'post_single_thumb_size', array(
        'default'           => $default_opt['post_single_thumb_size'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( 'post_single_thumb_size_control', array(
		'type' => 'select',
		'label'       => esc_html__( 'Image Size', 'zoom-lite' ),
		'description' => esc_html__( 'Available size: thumbnail, medium, medium_large, large and full', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'post_single_thumb_size',
		'choices' => array(
				'thumbnail' => esc_html__( 'Thumbnail', 'zoom-lite' ),
				'medium' => esc_html__( 'Medium', 'zoom-lite' ),
				'mlarge' => esc_html__( 'Medium Large', 'zoom-lite' ),
				'large' => esc_html__( 'Large', 'zoom-lite' ),
				'full' => esc_html__( 'Full', 'zoom-lite' ),
			),
		'priority'    => 4
	) );
	
	// Image Border Radius
    $wp_customize->add_setting( 'post_thumb_radius', array(
        'default'           => $default_opt['post_thumb_radius'],
        'sanitize_callback' => 'esc_attr',
    ) );
 
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'post_thumb_radius_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Image Border Radius', 'zoom-lite' ),
		'description' => esc_html__( 'Set border radius for all featured Images in post / blog list', 'zoom-lite' ),
		'section'     => 'site_styles_image_section',
		'settings'    => 'post_thumb_radius',
		'choices' => array(
				'min' => '0',
				'max' => '50',
				'step' => '1',
				'conver' => '%',
				'separator' => '40',
				'note' => esc_html__( 'If you want to keep things perfectly round, use an image with the same width and height. Anything else will still be rounded, but you will end up with an elliptic shape which may or may not be desirable.', 'zoom-lite' ),
			),
		'priority'    => 6
	) ) );
	
}

add_action( 'customize_register', 'zoom_theme_customize_images_options' );