<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add Custom Render for JetPack Infinite Scroll
 * @return Posts
 */
function zoom_infinite_scroll_render() {
    
   get_template_part( 'contents/content', 'infinitescroll' );
   
}


/**
 * Custom Order
 */
function zoom_jetpack_infinite_scroll_query_args( $args ) {
	
	$args['order'] = ''.esc_html( strtoupper( get_theme_mod( 'misc_jpis_order', 'DESC' ) ) ).'';
	$args['orderby'] = 'date';
 
    return $args;
	
}
add_filter( 'infinite_scroll_query_args', 'zoom_jetpack_infinite_scroll_query_args', 100 );


/**
 * Custom JS Settings
 */
function zoom_filter_jetpack_infinite_scroll_js_settings( $settings ) {
	
	$settings['text'] = esc_html( get_theme_mod( 'misc_jpis_load_more_txt', 'Load More...' ) ); 
	
	return $settings;
	
}
add_filter( 'infinite_scroll_js_settings', 'zoom_filter_jetpack_infinite_scroll_js_settings', 100 );


/**
 * Remove default JetPack infinity.js and replace with custom infinity.js
 */
function zoom_dequeue_defaul_jpack_infinity_script() {
	
	if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) {
		
		wp_dequeue_script( 'the-neverending-homepage' );
	
	}
	
}
add_action( 'wp_print_scripts', 'zoom_dequeue_defaul_jpack_infinity_script', 100 );