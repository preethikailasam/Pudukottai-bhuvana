<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_site_effects( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section(
		'zoom_theme_site_effects',
		array(
			'title' => esc_html__( 'Site Effects', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 1,
			'panel' => 'zoom_misc_panel'
		)
	);
	
	// Page preload
	$wp_customize->add_setting(
		'effect_screen_preload',
		array(
			'default' => $default_opt['effect_screen_preload'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'effect_screen_preload_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Enable Preloading Screen', 'zoom-lite' ),
			'description' => esc_html__( 'This option will displays a loading animation until the browser fetched the whole web content', 'zoom-lite' ),
			'section' => 'zoom_theme_site_effects',
			'settings' => 'effect_screen_preload',
			'priority' => 1
	) ) );
	
	// Link Color
    $wp_customize->add_setting( 'effect_screen_preload_bg', array(
        'default'           => $default_opt['effect_screen_preload_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'effect_screen_preload_bg_control', array(
        'label'	   => esc_html__( 'Preload Background Color', 'zoom-lite' ),
        'section'  => 'zoom_theme_site_effects',
        'settings' => 'effect_screen_preload_bg',
		'priority' => 2
    ) ) );
	
	// Scroll to Top effect
	$wp_customize->add_setting(
		'effect_stt',
		array(
			'default' => $default_opt['effect_stt'],
			'sanitize_callback' => 'zoom_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'effect_stt_control', array(
			'type' => 'switch_option',
			'label' => esc_html__( 'Scroll to Top', 'zoom-lite' ),
			'description' => esc_html__( 'This option will help your visitor to go back to the top of the page easily', 'zoom-lite' ),
			'section' => 'zoom_theme_site_effects',
			'settings' => 'effect_stt',
			'priority' => 3
	) ) );
	
	// Scroll Time
    $wp_customize->add_setting( 'effect_stt_speed', array(
        'default'           => $default_opt['effect_stt_speed'],
        'sanitize_callback' => 'esc_attr',
    ) );
 
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'effect_stt_speed_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Scroll to Top Speed', 'zoom-lite' ),
		'description' => esc_html__( 'Set scroll speed when back to top. Default: 1000ms (millisecond)', 'zoom-lite' ),
		'section'     => 'zoom_theme_site_effects',
		'settings'    => 'effect_stt_speed',
		'choices' => array(
				'min' => '500',
				'max' => '3000',
				'step' => '100',
				'conver' => 'ms',
			),
		'priority'    => 4
	) ) );
	
	// JetPack Infinite Scroll Posts per Page
	if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) {
		
		$wp_customize->add_setting(
			'misc_jpis_per_page',
			array(
				'default' => $default_opt['misc_jpis_per_page'],
				'sanitize_callback' => 'esc_attr'
			)
		);
		
		
		$ppage = array();
		
		foreach (range(1, 20) as $i) {
			$ppage[$i] = $i;
		}
		
		$wp_customize->add_control( 'misc_jpis_per_page_control', array(
				'type' => 'select',
				'label' => esc_html__( 'Infinite Scroll Posts per Page', 'zoom-lite' ),
				'description' => esc_html__( 'Set how many posts are loaded each time Infinite Scroll acts', 'zoom-lite' ),
				'section' => 'zoom_theme_site_effects',
				'settings' => 'misc_jpis_per_page',
				'choices' => $ppage,
				'priority' => 5
		) );
		
		// JetPack Infinite Scroll Order
		$wp_customize->add_setting(
			'misc_jpis_order',
			array(
				'default' => $default_opt['misc_jpis_order'],
				'sanitize_callback' => 'esc_attr'
			)
		);
		
		$wp_customize->add_control( 'misc_jpis_order_control', array(
				'type' => 'select',
				'label' => esc_html__( 'Infinite Scroll Posts Order', 'zoom-lite' ),
				'description' => esc_html__( 'You can set to ascending or descending order', 'zoom-lite' ),
				'section' => 'zoom_theme_site_effects',
				'settings' => 'misc_jpis_order',
				'choices' => array(
								'asc' => 'ASC',
								'desc' => 'DESC'
							),
				'priority' => 6
		) );
		
		
		if ( ! zoom_jetpack_active_module( 'infinite-scroll', 'infinite_scroll' ) ) {
			
			// Load More Text
			$wp_customize->add_setting( 'misc_jpis_load_more_txt', array(
				'default'	        => $default_opt['misc_jpis_load_more_txt'],
				'sanitize_callback' => 'esc_attr',
			) );
		
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'misc_jpis_load_more_txt_control', array(
				'label'    => esc_html__( 'Button Load More text', 'zoom-lite' ),
				'description' => esc_html__( 'Use this to change default Load More text', 'zoom-lite' ),
				'section'  => 'zoom_theme_site_effects',
				'settings' => 'misc_jpis_load_more_txt',
				'type'     => 'text',
				'priority' => 7
			) ) );
			
			
		}
		
		
	
	}
	
	
}

add_action( 'customize_register', 'zoom_theme_customize_site_effects' );