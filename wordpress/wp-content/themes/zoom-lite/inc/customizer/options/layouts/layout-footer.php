<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_site_footer_layout( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section( 'zoom_site_footer_layout', array(
		'title'    => esc_html__( 'Footer Layout', 'zoom-lite' ),
		'priority' => 29,
		'description'   => esc_html__( 'You can use the following options to manage widget in footer area', 'zoom-lite' ),
		'panel' => 'zoom_theme_layout_panel'
	));
	
	// Post Meta
    $wp_customize->add_setting(
        'footer_layout',
        array(
            'default'           => $default_opt['footer_layout'],
            'sanitize_callback' => 'zoom_sanitize_multiple_checkbox'
        )
    );


	$wp_customize->add_control( new Zoom_Multiple_Checkbox_Control( $wp_customize, 'footer_layout_control', array(
		'type' => 'checkbox_multiple',
		'settings'    => 'footer_layout',
		'section' => 'zoom_site_footer_layout',
        'label'   => esc_html__( 'Show/Hide Widget on footer area', 'zoom-lite' ),
		'choices' => array(
			'widget_left' => array(
				'label' => esc_html__( 'Show Left Widget', 'zoom-lite' ),
				),
			'widget_center' => array(
				'label' => esc_html__( 'Show Center Widget', 'zoom-lite' ),
				),
			'widget_right' => array(
				'label' => esc_html__( 'Show Right Widget', 'zoom-lite' ),
				)
                )
            )
        )
    );
	
}

add_action( 'customize_register', 'zoom_theme_customize_site_footer_layout' );