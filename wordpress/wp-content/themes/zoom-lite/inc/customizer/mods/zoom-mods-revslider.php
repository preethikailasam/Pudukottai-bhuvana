<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_add_revslider_into_the_list( $slideropt ) {
	
	$extra_slider = array(
		'rev_slider' => esc_html__( 'Revolution Slider', 'zoom-lite' ),
	);

	$slideropt["choices"] = array_merge( $slideropt["choices"], $extra_slider );

	return $slideropt;
	
}

add_filter( 'zoom_header_type_list', 'zoom_add_revslider_into_the_list' );


// Get Slider List
function zoom_add_revslider_option_list() {
	
	$rev = new RevSlider();
	
	$arrSliders = $rev->getArrSliders();
	
	$rev_sliders['noslider'] = esc_html__( '--select--', 'zoom-lite' );
	
	if ( is_array( $arrSliders ) ) {
	
		foreach ( (array) $arrSliders as $revSlider ) { 
		
			$rev_sliders[ $revSlider->getAlias() ] = $revSlider->getTitle();
		
		}
	
	} else {
		
		return array();
		
	}
	
	return $rev_sliders;
	
}