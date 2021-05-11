<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_meta_options( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_post_meta_section' , array(
	    'title'       => esc_html__( 'Post Information', 'zoom-lite' ),
	    'priority'    => 19,
	    'description' => esc_html__( 'Define and set your post meta with the following options', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );
	
	
	// Post Meta
    $wp_customize->add_setting(
        'post_meta',
        array(
            'default'           => $default_opt['post_meta'],
            'sanitize_callback' => 'zoom_sanitize_multiple_checkbox'
        )
    );


	$wp_customize->add_control( new Zoom_Multiple_Checkbox_Control( $wp_customize, 'post_meta_control', array(
		'type' => 'checkbox_multiple',
		'settings'    => 'post_meta',
		'section' => 'site_post_meta_section',
        'label'   => esc_html__( 'Post Meta Options', 'zoom-lite' ),
		'priority' => 1,
		'choices' => array(
			'meta_date' => array(
				'label' => esc_html__( 'Show Post Date', 'zoom-lite' ),
				),
			'meta_cat' => array(
				'label' => esc_html__( 'Show Categories', 'zoom-lite' ),
				),				
			'meta_tags' => array(
				'label' => esc_html__( 'Show Tags', 'zoom-lite' ),
				),				
			'meta_author' => array(
				'label' => esc_html__( 'Show Author', 'zoom-lite' ),
				),
			'meta_comments' => array(
				'label' => esc_html__( 'Show Comments', 'zoom-lite' ),
				),
			'breadcrumb' => array(
				'label' => esc_html__( 'Show Breadcrumbs ( Small Navigation )', 'zoom-lite' ),
				),
                )
            )
        )
    );
	
	
	// Next/Prev Post Link
    $wp_customize->add_setting(
        'post_next_prev',
        array(
            'default'           => $default_opt['post_next_prev'],
            'sanitize_callback' => 'zoom_sanitize_checkbox'
        )
    );
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'post_next_prev_control', array(
		'type' => 'switch_option',
        'label'   => esc_html__( 'Link to The Next Post', 'zoom-lite' ),
		'description' => esc_html__( 'Used on single post, enable this option will displays a link to the next post which exists in chronological order from the current post', 'zoom-lite' ),
		'section' => 'site_post_meta_section',
		'settings' => 'post_next_prev',
		'priority' => 3
	) ) );
	
	// Post Author Box
    $wp_customize->add_setting(
        'author_box',
        array(
            'default'           => $default_opt['author_box'],
            'sanitize_callback' => 'zoom_sanitize_checkbox'
        )
    );
	
	$wp_customize->add_control( new Zoom_Switch_Option_Control( $wp_customize, 'author_box_control', array(
		'type' => 'switch_option',
        'label'   => esc_html__( 'Author Box', 'zoom-lite' ),
		'description' => esc_html__( 'Use this option to show / hide the Author Box on every bottom of post', 'zoom-lite' ),
		'section' => 'site_post_meta_section',
		'settings' => 'author_box',
		'priority' => 4
	) ) );

	
}

add_action( 'customize_register', 'zoom_theme_customize_post_meta_options' );