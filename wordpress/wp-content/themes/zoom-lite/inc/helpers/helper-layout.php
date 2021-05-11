<?php


// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


// Set Sidebar Position
function zoom_set_layout( $wgtin, $pos ) {
	
	if ( zoom_is_wprepo_demo() ) {
		
		get_sidebar( 'right' );
		
	} else {
		
		$widgetin = strtolower( $wgtin );
		$widgetpos = strtolower( $pos );
		
		if ( get_theme_mod( ''.$widgetin.'_layout', 'right' ) == $widgetpos ) {
			
			get_sidebar( $widgetpos );
			
		}

	}

}


// Set Layout class by sidebar position
function zoom_set_layout_class( $in = null ) {
	
	$lytclass = strtolower( get_theme_mod( ''.$in.'_layout', 'right' ) );
	
	if ( $lytclass == 'none' ) {
		echo esc_attr( ' nosidebar' );
	} else {
		echo ' '.esc_attr( $in ).'-with-sidebar-'.esc_attr( $lytclass );
	}

}

// Calculate any element width base on $percent
function zoom_calculate_width( $percent = null ) {
	
	$percent = 100 - $percent / 2;
	
	return $percent.'%';

}

// Set max_width for main layout
function zoom_site_max_width( $layout = null ) {
	
	if ( $layout == 'boxed' ) {
		
		$slayout = get_theme_mod( 'site_maxwidth', '1200' ).'px';
		
	} else {
		$slayout = '100%';
	}
	
	return esc_attr( $slayout );

}

// Add Scroll to Top effect
function zoom_effects_stt() {
	
	echo '<a id="scrollUp" href="#page"></a>';
	
}

// Add Screen Preloader
function zoom_screen_preload() {
	
	echo '<div id="zoom-preloader"><div id="status"><div class="zoom-bubblingG"><span id="zoom-bubblingG_1"></span><span id="zoom-bubblingG_2"></span><span id="zoom-bubblingG_3"></span></div></div></div>';
	
}