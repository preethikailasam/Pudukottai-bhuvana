<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function zoom_generate_rand_img() {
	
	$theme_img_path = '/assets/images/misc/demo/';
	$images  = glob( get_template_directory() . $theme_img_path . '*.{jpg,jpeg}', GLOB_BRACE );
	$randomImage = $images[array_rand( $images )];
	
	return get_template_directory_uri() . $theme_img_path . basename( $randomImage );

}