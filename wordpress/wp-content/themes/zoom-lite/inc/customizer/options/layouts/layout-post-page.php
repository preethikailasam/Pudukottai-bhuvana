<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_page_layout( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	$wp_customize->add_section( 'zoom_blog_layout', array(
		'title'    => esc_html__( 'Blog &amp; Page', 'zoom-lite' ),
		'priority' => 29,
		'panel' => 'zoom_theme_layout_panel'
	));
	
	// Post Layout on Blog page
	$wp_customize->add_setting( 'post_layout', array(
		'default'           => $default_opt['post_layout'],
		'sanitize_callback' => 'sanitize_key'
	));
	
	$wp_customize->add_control( new Zoom_Layout_Select_Control( $wp_customize, 'post_layout_control', array(
		'type' => 'radio_image',
		'label'       => esc_html__( 'Blog Layout', 'zoom-lite' ),
		'description' => esc_html__( 'Choose layout for your blog page.', 'zoom-lite' ),
		'section'     => 'zoom_blog_layout',
		'settings'    => 'post_layout',
		'choices'     => array(
			'list' => array(
				'label' => esc_html__( 'List', 'zoom-lite' ),
				'url'   => '%spost-list.jpg'
			),
			'grid'    => array(
				'label' => esc_html__( 'Grid', 'zoom-lite' ),
				'url'   => '%spost-grid.jpg'
			)
		),
		'priority'    => 1,
	) ) );
	

	// Row
	$wp_customize->add_setting(
		'post_layout_cols',
		array(
			'default' => $default_opt['post_layout_cols'],
			'sanitize_callback' => 'esc_attr'
		)
	);
	
	$wp_customize->add_control( 'post_layout_cols_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Columns', 'zoom-lite' ),
			'description' => esc_html__( 'This option only if you choose GRID post layout. Default: 2', 'zoom-lite' ),
			'section' => 'zoom_blog_layout',
			'settings' => 'post_layout_cols',
			'choices' => array(
				'two' => '2',
				'three' => '3',
				'four' => '4'
			),
			'priority' => 2
	) );
	
	// Option to show / hide shadow on each grid
	$wp_customize->add_setting( 'post_cols_shadow', array(
		'default'	        => $default_opt['post_cols_shadow'],
		'sanitize_callback' => 'zoom_sanitize_checkbox',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'post_cols_shadow_apply', array(
		'label'    => esc_html__( 'Use shadow effect on each column', 'zoom-lite' ),
		'section'  => 'zoom_blog_layout',
		'settings' => 'post_cols_shadow',
		'type'     => 'checkbox',
		'priority'    => 3
	) ) );
	
	// Post Layout
	$wp_customize->add_setting( 'blog_layout', array(
		'default'           => $default_opt['blog_layout'],
		'sanitize_callback' => 'sanitize_key'
	));
	
	$wp_customize->add_control( new Zoom_Layout_Select_Control( $wp_customize, 'blog_layout_control', array(
		'type' => 'radio_image',
		'label'       => esc_html__( 'Blog and Post Layout', 'zoom-lite' ),
		'description' => esc_html__( 'Choose a layout for the blog or posts.', 'zoom-lite' ),
		'section'     => 'zoom_blog_layout',
		'settings'    => 'blog_layout',
		'choices'     => array(
			'left' => array(
				'label' => esc_html__( 'Left Sidebar', 'zoom-lite' ),
				'url'   => '%sleft-sidebar.png'
			),
			'right'    => array(
				'label' => esc_html__( 'Right Sidebar', 'zoom-lite' ),
				'url'   => '%sright-sidebar.png'
			),
			'none'    => array(
				'label' => esc_html__( 'No Sidebar', 'zoom-lite' ),
				'url'   => '%sno-sidebar.png'
			)
		),
		'priority'    => 4
	) ) );

	// Page Layout
	$wp_customize->add_setting( 'page_layout', array(
		'default'           => $default_opt['page_layout'],
		'sanitize_callback' => 'sanitize_key'
	));
	
	$wp_customize->add_control( new Zoom_Layout_Select_Control( $wp_customize, 'page_layout_control', array(
		'type' => 'radio_image',
		'label'       => esc_html__( 'Page Layout', 'zoom-lite' ),
		'description' => esc_html__( 'Choose a layout for the page posts.', 'zoom-lite' ),
		'section'     => 'zoom_blog_layout',
		'settings'    => 'page_layout',
		'choices'     => array(
			'left' => array(
				'label' => esc_html__( 'Left Sidebar', 'zoom-lite' ),
				'url'   => '%sleft-sidebar.png'
			),
			'right'    => array(
				'label' => esc_html__( 'Right Sidebar', 'zoom-lite' ),
				'url'   => '%sright-sidebar.png'
			),
			'none'    => array(
				'label' => esc_html__( 'No Sidebar', 'zoom-lite' ),
				'url'   => '%sno-sidebar.png'
			)
		),
		'priority'    => 5
	) ) );
	
	
	$wp_customize->add_setting( 'content_padding', array(
		'default'           => $default_opt['content_padding'],
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control( new Zoom_Slider_Option_Control( $wp_customize, 'content_padding_control', array(
		'type' => 'slider_option',
		'label'       => esc_html__( 'Content Area Padding', 'zoom-lite' ),
		'description' => esc_html__( 'Set site content area padding. Default : 5%', 'zoom-lite' ),
		'section'     => 'zoom_blog_layout',
		'settings'    => 'content_padding',
		'choices' => array(
				'min' => '3',
				'max' => '15',
				'step' => '1',
				'conver' => '%',
				'separator' => '20'
			),
		'priority'    => 6
	) ) );
	
}

add_action( 'customize_register', 'zoom_theme_customize_post_page_layout' );