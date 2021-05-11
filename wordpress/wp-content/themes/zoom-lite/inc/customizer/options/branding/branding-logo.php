<?php

// No direct access, please

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function zoom_theme_customize_logo_options( $wp_customize )
{

    $default_opt = zoom_default_theme_options();

    // Logo Section
    $wp_customize->add_section( 'zoom_theme_logo_section', array(
        'title'       => esc_html__( 'Logo', 'zoom-lite' ),
        'priority'    => 18,
        'description' => esc_html__( 'Upload a logo for available spot below.', 'zoom-lite' ),
        'panel'       => 'zoom_site_branding',
    ) );

    // Main Logo on Menu
    $wp_customize->add_setting(
        'main_logo_on_nav',
        array(
            'default'           => $default_opt['main_logo_on_nav'],
            'sanitize_callback' => 'zoom_sanitize_checkbox',
        )
    );

    $wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'main_logo_on_nav_control', array(
        'type'        => 'switch_option',
        'label'       => esc_html__( 'Show Main Logo in Menu', 'zoom-lite' ),
        'description' => esc_html__( 'This option will show your main logo in navigation.', 'zoom-lite' ),
        'section'     => 'zoom_theme_logo_section',
        'settings'    => 'main_logo_on_nav',
        'priority'    => 1,
	) ) );
	
	// Main Logo on Menu
    $wp_customize->add_setting(
        'main_logo_on_mobile_nav',
        array(
            'default'           => $default_opt['main_logo_on_mobile_nav'],
            'sanitize_callback' => 'zoom_sanitize_checkbox',
        )
    );

    $wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'main_logo_on_mobile_nav_control', array(
        'type'        => 'switch_option',
        'label'       => esc_html__( 'Show Main Logo in Mobile Menu', 'zoom-lite' ),
        'description' => esc_html__( 'This option will show your main logo in mobile navigation.', 'zoom-lite' ),
        'section'     => 'zoom_theme_logo_section',
        'settings'    => 'main_logo_on_mobile_nav',
    ) ) );

    // Bottom Bar Logo
    $wp_customize->add_setting( 'bottom_logo', array(
        'default'           => $default_opt['bottom_logo'],
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bottom_logo_control', array(
        'label'       => esc_html__( 'Bottom Bar Logo', 'zoom-lite' ),
        'description' => esc_html__( 'This logo will be displayed in the bottom bar area. We recommend you to use image with minimal 100px of width', 'zoom-lite' ),
        'section'     => 'zoom_theme_logo_section',
        'settings'    => 'bottom_logo',
    ) ) );

}

add_action( 'customize_register', 'zoom_theme_customize_logo_options' );