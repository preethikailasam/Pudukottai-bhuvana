<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_theme_customize_post_related( $wp_customize ) {
	
	$default_opt = zoom_default_theme_options();
	
	// Section
    $wp_customize->add_section( 'site_post_meta_section' , array(
	    'title'       => esc_html__( 'Post Information', 'zoom-lite' ),
	    'priority'    => 19,
	    'description' => esc_html__( 'Define and set your post meta with the following options', 'zoom-lite' ),
		'panel' => 'zoom_post_options',
	) );
	
	
	// Related Posts
	$wp_customize->add_setting(
		'post_related',
		array(
			'default' => $default_opt['post_related'],
			'sanitize_callback' => 'zoom_sanitize_choices'
		)
	);
	
	$wp_customize->add_control( 'post_related_control', array(
			'type' => 'select',
			'label' => esc_html__( 'Show Related Articles', 'zoom-lite' ),
			'description' => esc_html__( 'This option will display randomized related articles below the post', 'zoom-lite' ),
			'section' => 'site_post_meta_section',
			'settings' => 'post_related',
			'choices' => array(
				'disable' => esc_html__( 'Disable', 'zoom-lite' ),
				'by_cat' => esc_html__( 'Related by Category', 'zoom-lite' ),
				'by_tags' => esc_html__( 'Related by Tags', 'zoom-lite' ),
			),
			'priority' => 2
	) );

	
}

add_action( 'customize_register', 'zoom_theme_customize_post_related' );