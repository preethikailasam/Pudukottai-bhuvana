<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_top_bar_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
    $wp_customize->add_section( 'zoom_theme_t_bar_section' , array(
	    'title'       => esc_html__( 'Top Bar', 'zoom-lite' ),
	    'priority'    => 19,
	    'description' => esc_html__( 'You can set your Top Bar with the following fields', 'zoom-lite' ),
		'panel' => 'zoom_site_branding',
	) );
	
	// Top Bar Enable / Disable
	$wp_customize->add_setting(
		'top_bar_active',
		array(
			'default' => $default_opt['top_bar_active'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'top_bar_active_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Enable Top Bar', 'zoom-lite' ),
			'description' => esc_html__( 'This option used to enable / disable the Top Bar', 'zoom-lite' ),
			'section' => 'zoom_theme_t_bar_section',
			'settings' => 'top_bar_active',
			'priority' => 1
	) ) );
	
	// Top Bar Enable / Disable on Mobile
	$wp_customize->add_setting(
		'top_bar_mobile',
		array(
			'default' => $default_opt['top_bar_mobile'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'top_bar_mobile_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Hide Top Bar on Mobile', 'zoom-lite' ),
			'description' => esc_html__( 'This option used to Show / Hide the Top Bar on mobile view', 'zoom-lite' ),
			'section' => 'zoom_theme_t_bar_section',
			'settings' => 'top_bar_mobile',
			'priority' => 2
	) ) );

	// Email information
	$wp_customize->add_setting( 'top_bar_email', array(
		'default'           => $default_opt['top_bar_email'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_email_control', array(
		'label'    => esc_html__( 'Email Address', 'zoom-lite' ),
		'type'		=> 'text',
		'description' => esc_html__( 'You can use this field to display any text you want', 'zoom-lite' ),
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_email',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'mail@your-domain.com', 'zoom-lite' )
		),
	) ) );
	
	// Working Hours information
	$wp_customize->add_setting( 'top_bar_w_hours', array(
		'default'           => $default_opt['top_bar_w_hours'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_w_hours_control', array(
		'label'    => esc_html__( 'Working hours', 'zoom-lite' ),
		'type'		=> 'text',
		'description' => esc_html__( 'You can use this field to display any text you want', 'zoom-lite' ),
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_w_hours',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'Monday - Friday 8AM - 5PM', 'zoom-lite' )
		),
	) ) );
	
	// Social Link - Facebook
	$wp_customize->add_setting( 'top_bar_sos_facebook', array(
		'default'           => $default_opt['top_bar_sos_facebook'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_facebook_control', array(
		'label'    => esc_html__( 'Facebook', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_facebook',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://www.facebook.com/you-url', 'zoom-lite' )
		),
	) ) );
	
	// Social Link - Twitter
	$wp_customize->add_setting( 'top_bar_sos_twitter', array(
		'default'           => $default_opt['top_bar_sos_twitter'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_twitter_control', array(
		'label'    => esc_html__( 'Twitter', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_twitter',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://www.twitter.com/you-username', 'zoom-lite' )
		),
	) ) );
	
	// Social Link - Google+
	$wp_customize->add_setting( 'top_bar_sos_googleplus', array(
		'default'           => $default_opt['top_bar_sos_googleplus'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_googleplus_control', array(
		'label'    => esc_html__( 'Google +', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_googleplus',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://plus.google.com/your-id', 'zoom-lite' )
		),
	) ) );	
	
	// Social Link - Youtube
	$wp_customize->add_setting( 'top_bar_sos_youtube', array(
		'default'           => $default_opt['top_bar_sos_youtube'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_youtube_control', array(
		'label'    => esc_html__( 'Youtube', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_youtube',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://www.youtube.com/your-username', 'zoom-lite' )
		),
	) ) );	
	
	// Social Link - Instagram
	$wp_customize->add_setting( 'top_bar_sos_instagram', array(
		'default'           => $default_opt['top_bar_sos_instagram'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_instagram_control', array(
		'label'    => esc_html__( 'Instagram', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_instagram',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://www.instagram.com/your-username', 'zoom-lite' )
		),
	) ) );	
	
	// Social Link - Pinterest
	$wp_customize->add_setting( 'top_bar_sos_pinterest', array(
		'default'           => $default_opt['top_bar_sos_pinterest'],
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'top_bar_sos_pinterest_control', array(
		'label'    => esc_html__( 'Pinterest', 'zoom-lite' ),
		'type'		=> 'text',
		'section'  => 'zoom_theme_t_bar_section',
		'settings' => 'top_bar_sos_pinterest',
		'input_attrs' => array(
			'placeholder' => esc_attr__( 'https://www.pinterest.com/your-username', 'zoom-lite' )
		),
	) ) );	
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_top_bar_options' );