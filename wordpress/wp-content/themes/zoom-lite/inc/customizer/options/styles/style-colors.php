<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_color_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_styles_section' , array(
	    'title'       => esc_html__( 'Colors', 'zoom-lite' ),
	    'priority'    => 2,
	    'description' => esc_html__( 'Define and set your site color elements', 'zoom-lite' ),
		'panel' => 'zoom_site_styles',
	) );
	
	// GENERAL ------------------------------------------------------------------------------------------------------- //
	
	// Header Area Background
    $wp_customize->add_setting( 'site_header_color', array(
        'default'           => $default_opt['site_header_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_header_color_control', array(
        'label'	   => esc_html__( 'Site Header Area', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'site_header_color',
    ) ) );
	
	// Title Color
    $wp_customize->add_setting( 'site_title_color', array(
        'default'           => $default_opt['site_title_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_title_color_control', array(
        'label'	   => esc_html__( 'Site Title', 'zoom-lite' ),
		'description' => esc_html__( 'This option to change text color of', 'zoom-lite' ).'<strong style="font-style:italic;text-decoration:underline;">&nbsp;'.esc_html( get_bloginfo( 'name' ) ).'</strong>',
        'section'  => 'site_styles_section',
        'settings' => 'site_title_color',
    ) ) );
	
	// Description Color
    $wp_customize->add_setting( 'site_desc_color', array(
        'default'           => $default_opt['site_desc_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_desc_color_control', array(
        'label'	   => esc_html__( 'Site Description', 'zoom-lite' ),
		'description' => esc_html__( 'This option to change text color of', 'zoom-lite' ).'<strong style="font-style:italic; text-decoration:underline;">&nbsp;'.esc_html( get_bloginfo( 'description' ) ).'</strong>',
        'section'  => 'site_styles_section',
        'settings' => 'site_desc_color',
    ) ) );

	
	// CONTENT ------------------------------------------------------------------------------------------------------- //
	
	// Content Background
    $wp_customize->add_setting( 'content_bg_color', array(
        'default'           => $default_opt['content_bg_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_bg_color_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'CONTENT', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Content Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'content_bg_color',
    ) ) );
	
	// Content Text Color
    $wp_customize->add_setting( 'content_text_color', array(
        'default'           => $default_opt['content_text_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_text_color_control', array(
        'label'	   => esc_html__( 'Content Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'content_text_color',
    ) ) );
	
	// Link Color
    $wp_customize->add_setting( 'link_color', array(
        'default'           => $default_opt['link_color'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color_control', array(
        'label'	   => esc_html__( 'Link and Highlight', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'link_color',
    ) ) );
	
	// Entry Meta Text Color
    $wp_customize->add_setting( 'post_meta_col', array(
        'default'           => $default_opt['post_meta_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_meta_col_control', array(
        'label'	   => esc_html__( 'Entry Meta Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'post_meta_col',
    ) ) );
	
	// Accent Color
    $wp_customize->add_setting( 'content_accent_col', array(
        'default'           => $default_opt['content_accent_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_accent_col_control', array(
        'label'	   => esc_html__( 'Accents', 'zoom-lite' ),
		'description' => esc_html__( 'Accents used for all borders &amp; lines on the site.', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'content_accent_col',
    ) ) );
	
	// NAVIGATION ----------------------------------------------------------------------------------------------------- //
	
	// Site Navigation Link Color
    $wp_customize->add_setting( 'site_nav_col', array(
        'default'           => $default_opt['site_nav_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_nav_col_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'NAVIGATION', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Navigation Text', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'site_nav_col',
    ) ) );
	
	
	// MENU ----------------------------------------------------------------------------------------------------------- //
	
	// Primary / Main Menu
    $wp_customize->add_setting( 'main_menu_bg', array(
        'default'           => $default_opt['main_menu_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_menu_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'MENU', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Menu Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'main_menu_bg',
    ) ) );
	
    $wp_customize->add_setting( 'main_menu_txt', array(
        'default'           => $default_opt['main_menu_txt'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_menu_txt_control', array(
        'label'	   => esc_html__( 'Menu Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'main_menu_txt',
    ) ) );	
	
	// Submenu
    $wp_customize->add_setting( 'sub_menu_bg', array(
        'default'           => $default_opt['sub_menu_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_bg_control', array(
        'label'	   => esc_html__( 'Submenu Background', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sub_menu_bg',
    ) ) );
	
    $wp_customize->add_setting( 'sub_menu_txt', array(
        'default'           => $default_opt['sub_menu_txt'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_txt_control', array(
        'label'	   => esc_html__( 'Submenu Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sub_menu_txt',
    ) ) );
	
	
	// WIDGET / SIDEBAR ----------------------------------------------------------------------------------------------- //
	
	// Sidebar Background Color
    $wp_customize->add_setting( 'sidebar_bg', array(
        'default'           => $default_opt['sidebar_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'SIDEBAR AREA', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Sidebar Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'sidebar_bg',	
	) ) );
	
	// Sidebar Title Color
    $wp_customize->add_setting( 'sidebar_ttl_col', array(
        'default'           => $default_opt['sidebar_ttl_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_ttl_col_control', array(
        'label'	   => esc_html__( 'Sidebar Title', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sidebar_ttl_col',	
	) ) );
	
	// Sidebar Text Color
    $wp_customize->add_setting( 'sidebar_txt_col', array(
        'default'           => $default_opt['sidebar_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_txt_col_control', array(
        'label'	   => esc_html__( 'Sidebar Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sidebar_txt_col',	
	) ) );
	
	// Sidebar Link Text Color
    $wp_customize->add_setting( 'sidebar_link_txt_col', array(
        'default'           => $default_opt['sidebar_link_txt_col'],
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_link_txt_col_control', array(
        'label'	   => esc_html__( 'Sidebar Link and Highlight', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sidebar_link_txt_col',	
	) ) );

	// Sidebar Show / Hide side border
    $wp_customize->add_setting( 'sidebar_bor', array(
        'default'           => $default_opt['sidebar_bor'],
        'sanitize_callback' => 'zoom_sanitize_checkbox',
    ) );
 
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'sidebar_bor_control', array(
		'type' => 'switch_option',
		'label'       => esc_html__( 'Show Sidebar Border', 'zoom-lite' ),
		'description' => esc_html__( 'You can show or hide sidebar border. Default: Show', 'zoom-lite' ),
		'section'     => 'site_styles_section',
		'settings'    => 'sidebar_bor',
		'priority'    => 10
	) ) );
	
	// Sidebar Border Type
    $wp_customize->add_setting( 'sidebar_bor_type', array(
        'default'           => $default_opt['sidebar_bor_type'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( 'sidebar_bor_type_control', array(
		'type' => 'select',
		'label'       => esc_html__( 'Sidebar Border Type', 'zoom-lite' ),
		'description' => esc_html__( 'Set the sidebar border type here. Default: Solid', 'zoom-lite' ),
		'section'     => 'site_styles_section',
		'settings'    => 'sidebar_bor_type',
		'choices' => array(
				'solid' => 'Solid',
				'dashed' => 'Dashed',
			),
		'priority'    => 10
	) );
	
	// Sidebar Border Width
    $wp_customize->add_setting( 'sidebar_bor_width', array(
        'default'           => $default_opt['sidebar_bor_width'],
        'sanitize_callback' => 'sanitize_text_field',
    ) );
 
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'sidebar_bor_width_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Sidebar Border Width', 'zoom-lite' ),
		'description' => esc_html__( 'Set the width of sidebar border here. Default: 5px', 'zoom-lite' ),
		'section'     => 'site_styles_section',
		'settings'    => 'sidebar_bor_width',
		'choices' => array(
				'min' => '0',
				'max' => '10',
				'step' => '1',
				'conver' => 'px',
			),
		'priority'    => 10
	) ) );
	
	// Sidebar Border Color
    $wp_customize->add_setting( 'sidebar_bor_col', array(
        'default'           => $default_opt['sidebar_bor_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_bor_col_control', array(
        'label'	   => esc_html__( 'Sidebar Border', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'sidebar_bor_col',	
	) ) );
	

	// FOOTER ------------------------------------------------------------------------------------------------------- //
	
	// Footer Background Color
    $wp_customize->add_setting( 'footer_bg', array(
        'default'           => $default_opt['footer_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'FOOTER AREA', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Footer Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'footer_bg',	
	) ) );
	
	// Footer Title Color
    $wp_customize->add_setting( 'footer_ttl_col', array(
        'default'           => $default_opt['footer_ttl_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_ttl_col_control', array(
        'label'	   => esc_html__( 'Footer Title', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'footer_ttl_col',	
	) ) );
	
	// Footer Text Color
    $wp_customize->add_setting( 'footer_txt_col', array(
        'default'           => $default_opt['footer_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_txt_col_control', array(
        'label'	   => esc_html__( 'Footer Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'footer_txt_col',	
	) ) );
	
	// Footer Link Text Color
    $wp_customize->add_setting( 'footer_link_txt_col', array(
        'default'           => $default_opt['footer_link_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_txt_col_control', array(
        'label'	   => esc_html__( 'Footer Link and Highlight', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'footer_link_txt_col',	
	) ) );
	

	// TOP BAR ---------------------------------------------------------------------------------------------------- //
	
	// Top Bar Background Color
    $wp_customize->add_setting( 'top_bar_bg', array(
        'default'           => $default_opt['top_bar_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'TOP BAR', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Top Bar Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'top_bar_bg',	
	) ) );
	
	// Top Bar Text Color
    $wp_customize->add_setting( 'top_bar_txt_col', array(
        'default'           => $default_opt['top_bar_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_txt_col_control', array(
        'label'	   => esc_html__( 'Top Bar Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'top_bar_txt_col',	
	) ) );
	
	// Top Bar Link Text Color
    $wp_customize->add_setting( 'top_bar_link_txt_col', array(
        'default'           => $default_opt['top_bar_link_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_link_txt_col_control', array(
        'label'	   => esc_html__( 'Top Bar Link and Highlight', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'top_bar_link_txt_col',	
	) ) );
	
	
	// BOTTOM BAR ---------------------------------------------------------------------------------------------------- //
	
	// Bottom Bar Background Color
    $wp_customize->add_setting( 'bottom_bar_bg', array(
        'default'           => $default_opt['bottom_bar_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_bar_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'BOTTOM BAR', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Bottom Bar Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'bottom_bar_bg',	
	) ) );
	
	// Bottom Bar Text Color
    $wp_customize->add_setting( 'bottom_bar_txt_col', array(
        'default'           => $default_opt['bottom_bar_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_bar_txt_col_control', array(
        'label'	   => esc_html__( 'Bottom Bar Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'bottom_bar_txt_col',	
	) ) );
	
	// Bottom Bar Link Text Color
    $wp_customize->add_setting( 'bottom_bar_link_txt_col', array(
        'default'           => $default_opt['bottom_bar_link_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_bar_link_txt_col_control', array(
        'label'	   => esc_html__( 'Bottom Bar Link and Highlight', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'bottom_bar_link_txt_col',	
	) ) );
	
	// Author Box ---------------------------------------------------------------------------------------------------- //
	
	// Author Box Background Color
    $wp_customize->add_setting( 'author_box_bg', array(
        'default'           => $default_opt['author_box_bg'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'author_box_bg_control', array(
        'label'	   => '<span class="menu-group-title">'.esc_html__( 'AUTHOR BOX', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Author Box Background', 'zoom-lite' ).'</span>',
        'section'  => 'site_styles_section',
        'settings' => 'author_box_bg',	
	) ) );
	
	// Author Box Text Color
    $wp_customize->add_setting( 'author_box_txt_col', array(
        'default'           => $default_opt['author_box_txt_col'],
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'author_box_txt_col_control', array(
        'label'	   => esc_html__( 'Author Box Text', 'zoom-lite' ),
        'section'  => 'site_styles_section',
        'settings' => 'author_box_txt_col',	
	) ) );


}

add_action( 'customize_register', 'zoom_theme_customize_color_options' );