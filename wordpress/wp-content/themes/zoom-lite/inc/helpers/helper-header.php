<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'zoom_theme_header_function' ) ) :
/**
 * Generate the header container based on settings
 * @since 1.0
 */
add_action( 'zoom_theme_header', 'zoom_theme_header_function', 5 );
function zoom_theme_header_function() {

	$header_type = ( get_theme_mod( 'header_type' ) ? esc_attr( get_theme_mod( 'header_type' ) ) : ( is_customize_preview() || zoom_is_wprepo_demo() ? esc_attr( 'slider' ) : esc_attr( 'title_only' ) ) );
	
	zoom_theme_generate_header( $header_type );
	
}
endif;


if ( ! function_exists( 'zoom_theme_generate_header' ) ) :

function zoom_theme_generate_header( $model ) {
	
	ob_start();	
	
	switch ( $model ) {
		
		// Image Slider
		case 'slider':
	
			if ( zoom_slider_display_in() ) {
			
				get_template_part( 'inc/helpers/helper', 'header-slider' ); // helper-header-slider.php
				echo wp_kses_post( zoom_theme_header_slider() );
			
			}

		break;
			
		// Image Background
		case 'image': 
			
			echo '<div class="image-header-container"><img class="header-img" src="'.esc_url( zoom_theme_get_header_image() ).'"></div>';

		break;
			
		// Image Background, Title & Tagline
		case 'image_title':
			
			echo '<div class="site-header image-title"><img class="header-img" src="'.esc_url( zoom_theme_get_header_image() ).'"><div class="title-holder">'.( ! is_customize_preview() ? '<a class="home-link" href="'.esc_url( home_url() ).'" title="'.esc_attr( get_bloginfo( 'name' ) ).'" rel="home">' : '').'<h1 class="site-title">'.esc_attr( get_bloginfo( 'name' ) ).'</h1><h2 class="site-description">'.esc_attr( get_bloginfo( 'description' ) ).'</h2>'.( ! is_customize_preview() ? '</a>' : '' ).'</div></div>';

		break;
		
		// Image Background, Logo, Title & Tagline
		case 'image_title_logo':
			
			echo '<div class="site-header logo-title-mode image-header-container-with-logo-title"><div class="logo-title-holder">'.( ! is_customize_preview() ? '<a class="link-holder" href="'.esc_url( home_url() ).'" title="'.esc_attr( get_bloginfo( 'name' ) ).'" rel="home">' : '' ).'<div class="site-logo">'.wp_kses_post( zoom_theme_brand_logo( 'main' ) ).'</div><div class="site-identity"><h1 class="site-title">'.esc_attr( get_bloginfo( 'name' ) ).'</h1><h2 class="site-description">'.esc_attr( get_bloginfo( 'description' ) ).'</h2></div>'.( ! is_customize_preview() ? '</a>' : '' ).'</div></div>';

		break;
		
		// Logo, Title & Tagline
		case 'logo_title':
			
			echo '<div class="site-header logo-title-mode"><div class="logo-title-holder">'.( ! is_customize_preview() ? '<a class="link-holder" href="'.esc_url( home_url() ).'" title="'.esc_attr( get_bloginfo( 'name' ) ).'" rel="home">' : '' ).'<div class="site-logo">'.wp_kses_post( zoom_theme_brand_logo( 'main' ) ).'</div><div class="site-identity"><h1 class="site-title">'.esc_attr( get_bloginfo( 'name' ) ).'</h1><h2 class="site-description">'.esc_attr( get_bloginfo( 'description' ) ).'</h2></div>'.( ! is_customize_preview() ? '</a>' : '' ).'</div></div>';

		break;
		
		// Title & Tagline
		case 'title_only':
			
			echo '<div class="site-header logo-title-mode title-only"><div class="logo-title-holder">'.( ! is_customize_preview() ? '<a class="link-holder" href="'.esc_url( home_url() ).'" title="'.esc_attr( get_bloginfo( 'name' ) ).'" rel="home">' : '' ).'<div class="site-identity"><h1 class="site-title">'.esc_attr( get_bloginfo( 'name' ) ).'</h1><h2 class="site-description">'.esc_attr( get_bloginfo( 'description' ) ).'</h2></div>'.( ! is_customize_preview() ? '</a>' : '' ).'</div></div>';

		break;
		
		case 'none':
		
		return;

		break;
		
		case 'rev_slider':
		
			if ( class_exists( 'RevSlider' ) ) {
				
				$revSlAlias = esc_html( get_theme_mod( 'header_rev_slider' ) );
				
				echo '<div class="rev-slider-container">'.( get_theme_mod( 'header_rev_slider_homepage' ) ? putRevSlider( $revSlAlias, 'homepage' ) : putRevSlider( $revSlAlias ) ).'</div>';
				
			} else {
				
				echo '<div style="padding: 10px;" class="rev-slider-container">'.esc_html__( 'Please activate Revolution Slider plugin first.', 'zoom-lite' ).'</div>';
				
			}

		break;

		default:

	} 
	
	$header = ob_get_clean();
	
	if ( $model == 'rev_slider' ) {
		
		echo $header;
		
	} else {
		
		echo wp_kses_post( $header );
		
	}
	
}

endif;

if ( ! function_exists( 'zoom_theme_get_header_image' ) ) :
function zoom_theme_get_header_image() {

    if ( has_header_image() ) {
        return get_header_image();
    } else {
        return get_theme_support( 'custom-header', 'default-image' );
		
    }
	
}
endif;