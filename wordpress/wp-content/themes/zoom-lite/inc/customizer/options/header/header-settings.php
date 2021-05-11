<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_header_settings( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
    // Header Type
    $wp_customize->add_section( 'zoom_theme_header_section' , array(
	    'title'       => esc_html__( 'Header', 'zoom-lite' ),
		'panel' 	  => 'zoom_site_styles',
	    'priority'    => 1,
	) );

	$wp_customize->add_setting( 'header_type', array(
		'default'           => $default_opt['header_type'],
		'sanitize_callback' => 'zoom_sanitize_choices',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_type_control', apply_filters( 'zoom_header_type_list', array(
		'label'    => esc_html__( 'Header Type', 'zoom-lite' ),
		'section'  => 'zoom_theme_header_section',
		'settings' => 'header_type',
		'type'     => 'radio',
		'choices'  => array(
			'slider' => esc_html__( 'Image Slider', 'zoom-lite' ),
			'image' => esc_html__( 'Image Background', 'zoom-lite' ),
			'image_title' => esc_html__( 'Image Background, Title &amp; Tagline', 'zoom-lite' ),
			'image_title_logo' => esc_html__( 'Image Background, Logo, Title &amp; Tagline', 'zoom-lite' ),
			'logo_title' => esc_html__( 'Logo, Title &amp; Tagline', 'zoom-lite' ),
			'title_only' => esc_html__( 'Title &amp; Tagline', 'zoom-lite' ),
			'none' => esc_html__( 'None', 'zoom-lite' ),
			),
	'priority'    => 1
	) ) ) );
	
	// Header Slider
	$wp_customize->add_setting( 'header_slider', array(
		'default'           => $default_opt['header_slider'],
		'sanitize_callback' => 'wp_parse_id_list',
	) );

    $wp_customize->add_control( new Zoom_Customize_Slider_Control(
        $wp_customize,
        'header_slider_control',
        array(
            'label'    => esc_html__( 'Slider Images', 'zoom-lite' ),
            'section'  => 'zoom_theme_header_section',
            'settings' => 'header_slider',
            'type'     => 'image_slider',
			'choices' => array(
				'notes'     => esc_html__( 'Just drag and drop to ordering of images', 'zoom-lite' ),
			),
			'priority' => 1
        )
    ) );
	
	
	// Option to manage image height
	$wp_customize->add_setting(
		'header_slider_is_max_height',
		array(
			'default' => $default_opt['header_slider_is_max_height'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'header_slider_is_max_height_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Enable Max Height for Slider', 'zoom-lite' ),
			'description' => esc_html__( 'The max-height option is used to set the maximum height of your image slider. You can turn it Off to keep your original image height, but we strongly recommend to set it On.', 'zoom-lite' ),
			'section' => 'zoom_theme_header_section',
			'settings' => 'header_slider_is_max_height',
			'choices' => array(
				'action' => 'yes',
				'actionid' => 'header_slider_max_height_control',
			),
			'priority' => 2
	) ) );
	
	// Slider Settings ( duration )
	$wp_customize->add_setting( 'header_slider_max_height', array(
		'default'           => $default_opt['header_slider_max_height'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'header_slider_max_height_control', array(
		'type' => 'slider_option',
		'description' => esc_html__( 'Default Max-Height : 350px', 'zoom-lite' ),
		'section'     => 'zoom_theme_header_section',
		'settings'    => 'header_slider_max_height',
		'choices' => array(
				'min' => '200',
				'max' => '500',
				'step' => '1',
				'conver' => 'px',
				'separator' => '20',
			),
		'priority' => 3
	) ) );
	
	// Slider Homepage only
	$wp_customize->add_setting(
		'header_slider_hp_only',
		array(
			'default' => $default_opt['header_slider_hp_only'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'header_slider_hp_only_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Show the Slider only in Homepage', 'zoom-lite' ),
			'description' => esc_html__( 'Enable this option if you want to show your slider only in homepage', 'zoom-lite' ),
			'section' => 'zoom_theme_header_section',
			'settings' => 'header_slider_hp_only',
			'priority' => 4
	) ) );

	// Slider Settings ( duration )
	$wp_customize->add_setting( 'header_slider_p_time', array(
		'default'           => $default_opt['header_slider_p_time'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'header_slider_p_time_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Slider Interval', 'zoom-lite' ),
		'description' => esc_html__( 'This option used to set how long each slide will show. Default : per 3 seconds', 'zoom-lite' ),
		'section'     => 'zoom_theme_header_section',
		'settings'    => 'header_slider_p_time',
		'choices' => array(
				'min' => '1',
				'max' => '10',
				'step' => '1',
				'conver' => 'sec',
			),
		'priority' => 5
	) ) );
		
	// Slider Settings ( effect )
	$wp_customize->add_setting( 'header_slider_effect', array(
		'default'           => $default_opt['header_slider_effect'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( 'header_slider_effect_control', array(
		'type' => 'select',
		'label' => esc_html__( 'Slider Effect', 'zoom-lite' ),
		'description' => esc_html__( 'Specify set the slider effect. The effect parameter can be any of the following', 'zoom-lite' ),
		'section' => 'zoom_theme_header_section',
		'settings' => 'header_slider_effect',
		'choices' => array(
			'random' => esc_html__( 'Random', 'zoom-lite' ),
			'sliceDown' => esc_html__( 'sliceDown', 'zoom-lite' ),
			'sliceDownLeft' => esc_html__( 'sliceDownLeft', 'zoom-lite' ),
			'sliceUp' => esc_html__( 'sliceUp', 'zoom-lite' ),
			'sliceUpLeft' => esc_html__( 'sliceUpLeft', 'zoom-lite' ),
			'sliceUpDown' => esc_html__( 'sliceUpDown', 'zoom-lite' ),
			'sliceUpDownLeft' => esc_html__( 'sliceUpDownLeft', 'zoom-lite' ),
			'fold' => esc_html__( 'Fold', 'zoom-lite' ),
			'fade' => esc_html__( 'Fade', 'zoom-lite' ),
			'slideInRight' => esc_html__( 'slideInRight', 'zoom-lite' ),
			'slideInLeft' => esc_html__( 'slideInLeft', 'zoom-lite' ),
			'boxRandom' => esc_html__( 'boxRandom', 'zoom-lite' ),
			'boxRain' => esc_html__( 'boxRain', 'zoom-lite' ),
			'boxRainReverse' => esc_html__( 'boxRainReverse', 'zoom-lite' ),
			'boxRainGrow' => esc_html__( 'boxRainGrow', 'zoom-lite' ),
			'boxRainGrowReverse' => esc_html__( 'boxRainGrowReverse', 'zoom-lite' ),
		),
		'priority' => 6
	) );

	// If Revolution Slider active
	if ( class_exists( 'RevSlider' ) ) {

		// Slider List
		$wp_customize->add_setting(
			'header_rev_slider',
			array(
				'default' => 'noslider',
				'sanitize_callback' => 'zoom_sanitize_choices'
			)
		);
		
		$wp_customize->add_control( 'header_rev_slider_control', array(
				'type' => 'select',
				'label' => esc_html__( 'Revolution Slider', 'zoom-lite' ),
				'description' => esc_html__( 'Choose available Slider', 'zoom-lite' ),
				'section' => 'zoom_theme_header_section',
				'settings' => 'header_rev_slider',
				'choices' => zoom_add_revslider_option_list(),
				'priority' => 7
		) );
	
		
		// Slider on Homepage
		$wp_customize->add_setting(
			'header_rev_slider_homepage',
			array(
				'default' => false,
				'sanitize_callback' => 'zoom_sanitize_checkbox'
			)
		);
		
		$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'header_rev_slider_homepage_control', array(
				'type' => 'switch_option',
				'label' => esc_html__( 'Show the Slider only in Homepage', 'zoom-lite' ),
				'description' => esc_html__( 'Enable this option if you want to show your slider only in homepage', 'zoom-lite' ),
				'section' => 'zoom_theme_header_section',
				'settings' => 'header_rev_slider_homepage',
				'priority' => 8
		) ) );
		

	}
	
		
}

add_action( 'customize_register', 'zoom_theme_customize_header_settings' );