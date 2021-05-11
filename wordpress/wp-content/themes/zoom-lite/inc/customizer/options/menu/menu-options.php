<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_menu_layout( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	/* We move below options to default menu section
	$wp_customize->add_section(
		'zoom_theme_layout_navigation',
		array(
			'title' => esc_html__( 'Menu Position', 'zoom-lite' ),
			'capability' => 'edit_theme_options',
			'priority' => 6,
			'panel' => 'nav_menus'
		)
	); */
	
	// Menu Position
	$wp_customize->add_setting(
		'menu_pos',
		array(
			'default' => $default_opt['menu_pos'],
			'sanitize_callback' => 'esc_attr'
		)
	);
	
	$wp_customize->add_control( 'menu_pos_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Position', 'zoom-lite' ),
			'section' => 'menu_locations',
			'settings' => 'menu_pos',
			'choices' => array(
				'nav-before-header' => esc_html__( 'Before Header', 'zoom-lite' ),
				'nav-after-header' => esc_html__( 'After Header', 'zoom-lite' ),
				'' => esc_html__( 'No Menu', 'zoom-lite' )
			),
			'priority' => 20
	) );
	
	
	// Menu Align
	$wp_customize->add_setting(
		'menu_align',
		array(
			'default' => $default_opt['menu_align'],
			'sanitize_callback' => 'esc_attr'
		)
	);
	
	$wp_customize->add_control( 'menu_align_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Menu Alignment', 'zoom-lite' ),
			'section' => 'menu_locations',
			'settings' => 'menu_align',
			'choices' => array(
				'left' => esc_html__( 'Left', 'zoom-lite' ),
				'center' => esc_html__( 'Center', 'zoom-lite' ),
				'right' => esc_html__( 'Right', 'zoom-lite' )
			),
			'priority' => 21
	) );
	
	
	// Floating Menu on Scroll
	$wp_customize->add_setting( 'menu_floating', array(
		'default'	        => $default_opt['menu_floating'],
		'sanitize_callback' => 'zoom_sanitize_checkbox',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'menu_floating', array(
		'label'    => esc_html__( 'Floating Menu on Scroll', 'zoom-lite' ),
		'description' => esc_html__( 'This option will stick the Main menu to the top of the window on scroll so your visitor will easier to navigate your site.', 'zoom-lite' ),
		'section'  => 'menu_locations',
		'settings' => 'menu_floating',
		'type'     => 'checkbox',
		'priority' => 22
	) ) );
	

	// Search on Menu
	$wp_customize->add_setting( 'menu_search', array(
		'default'	        => $default_opt['menu_search'],
		'sanitize_callback' => 'zoom_sanitize_checkbox',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'menu_search', array(
		'label'    => esc_html__( 'Search Form on Menu', 'zoom-lite' ),
		'description' => esc_html__( 'This option will show the search form on your main menu.', 'zoom-lite' ),
		'section'  => 'menu_locations',
		'settings' => 'menu_search',
		'type'     => 'checkbox',
		'priority' => 23
	) ) );
	
	
	// Home Button
	$wp_customize->add_setting( 'menu_home_btn', array(
		'default'	        => $default_opt['menu_home_btn'],
		'sanitize_callback' => 'zoom_sanitize_checkbox',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'menu_home_btn', array(
		'label'    => esc_html__( 'Add Home Button', 'zoom-lite' ),
		'description' => esc_html__( 'This option will auto add Home button with icon in your main menu.', 'zoom-lite' ),
		'section'  => 'menu_locations',
		'settings' => 'menu_home_btn',
		'type'     => 'checkbox',
		'priority' => 24
	) ) );
	
	// Home Button Label
	$wp_customize->add_setting( 'menu_home_btn_txt', array(
		'default'	        => $default_opt['menu_home_btn_txt'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'menu_home_btn_txt', array(
		'label'    => esc_html__( 'Home Button Label', 'zoom-lite' ),
		'description' => esc_html__( 'You can change the default Home button label here', 'zoom-lite' ),
		'section'  => 'menu_locations',
		'settings' => 'menu_home_btn_txt',
		'type'     => 'text',
		'priority' => 25
	) ) );
	

	
}

add_action( 'customize_register', 'zoom_theme_customize_menu_layout' );