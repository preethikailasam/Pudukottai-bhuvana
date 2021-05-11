<?php
/*
 * Theme Enqueue scripts and styles
 *
 * @package zoom
 * @subpackage Functions
*/

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function zoom_theme_scripts() {
	
	/* Theme Version */
	$zoom_version = wp_get_theme()->get( 'Version' );
	/* RTL Mode */
	$is_rtl = ( is_rtl() ? '-rtl' : '' );
	
	wp_enqueue_style( 'zoom-theme-style', get_stylesheet_uri(), array(), $zoom_version );
	wp_enqueue_style( 'zoom-theme-main-style', get_template_directory_uri() . '/assets/css/main'.( get_theme_mod( 'misc_min_stylesheet', true ) == true ? '.min' : '' ).$is_rtl.'.css', array(), $zoom_version );
	wp_enqueue_style( 'zoom-theme-mobile-nav', get_template_directory_uri() . '/assets/css/menu-mobile'.$is_rtl.'.css', array(), $zoom_version );
	wp_enqueue_style( 'zoom-theme-media-queries', get_template_directory_uri() . '/assets/css/media-queries'.$is_rtl.'.css', array(), $zoom_version );
	wp_enqueue_style( 'zoom-font-awesome', get_template_directory_uri() . '/assets/css/iconfonts/fontawesome/font-awesome'.( get_theme_mod( 'misc_min_stylesheet' ) == true ? '.min' : '' ).'.css', array(), '4.7.0' );
	wp_register_script( 'jquery-nivo-slider', get_template_directory_uri() . '/assets/lib/bower/nivo-slider/jquery.nivo.slider.js', array ( 'jquery' ), false, false );
	
	/* Default Scripts Included and Registered by WordPress */
	wp_enqueue_script( 'jquery-masonry' );
	
	/* Image LazyLoad */
	if ( get_theme_mod( 'misc_image_lazyload', true ) ) {
		
		wp_enqueue_script( 'zoom-image-lazyload', get_template_directory_uri() . '/assets/js/zoom-lazyload.js', array( 'jquery', 'zoom-jquery-sonar' ), $zoom_version, true );
		wp_enqueue_script( 'zoom-jquery-sonar', get_template_directory_uri() . '/assets/lib/bower/plugins/sonar/jquery.sonar.min.js', array( 'jquery' ), $zoom_version, true );
		
	}
	
	/* Custom JetPack infinity.js */
	if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) {
		
		wp_enqueue_script( 'zoom-the-neverending-homepage', get_template_directory_uri() . '/inc/extensions/js/infinity.js', array( 'jquery' ) );
		
		wp_localize_script( 'zoom-the-neverending-homepage', 'jpkis_opt',
			array(
			'button_class' => esc_attr( get_theme_mod( 'button_nav', 'green' ) ),
				)
		);
		
	}
	
	wp_register_script( 'jquery-scrollup', get_template_directory_uri() . '/assets/lib/bower/plugins/jquery.scrollup.js', array ( 'jquery' ), false, false );
	wp_register_style( 'nivo-slider', get_template_directory_uri() . '/assets/lib/bower/nivo-slider/nivo-slider.css' );
	wp_register_style( 'nivo-slider-theme-default', get_template_directory_uri() . '/assets/lib/bower/nivo-slider/themes/default/default.css' );
	wp_enqueue_script( 'zoom-theme-scripts', get_template_directory_uri() . '/assets/js/zoom-theme.js', array( 'jquery' ) );
	
	/* IE Conditional */
	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . "/assets/js/html5shiv.js", array(), false, false );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	
	/* Header Slider Conditional */
	$is_slider = ( get_theme_mod( 'header_type' ) ? get_theme_mod( 'header_type' ): ( is_customize_preview() || zoom_is_wprepo_demo() ? 'slider' : 'title_only' ) );
	
	/* Main JS localize_script */
	wp_localize_script( 'zoom-theme-scripts', 'zoom_opt',
			array(
				'floating_nav' => esc_html( get_theme_mod( 'menu_floating', true ) ),
				'zoom_is_mobile' => wp_is_mobile(),
				'zoom_effect_preload' => esc_html( get_theme_mod( 'effect_screen_preload', false ) ),
				'zoom_effect_preload_bg' => esc_html( get_theme_mod( 'effect_screen_preload_bg', '#17486E' ) ),
				'zoom_effect_stt' => esc_html( get_theme_mod( 'effect_stt', true ) ),
				'zoom_effect_stt_speed' => esc_html( get_theme_mod( 'effect_stt_speed', '1000' ) ),
				'header_type' => esc_html( get_theme_mod( 'header_type', $is_slider ) ),
				'slider_intv' => esc_html( get_theme_mod( 'header_slider_p_time', 3 ) * 1000 ),
				'slider_max_h' => esc_html( get_theme_mod( 'header_slider_is_max_height', true ) ),
				'slider_effect' => esc_html( get_theme_mod( 'header_slider_effect', 'fade' ) ),
				'slider_script' => esc_html( zoom_slider_display_in() ),
				'zoom_is_adminbar' => esc_html( ( is_admin_bar_showing() ? true : false ) ),
				'in_customizer' => esc_html( ( is_customize_preview() ? true : false ) ),
				'sidebar_width' => esc_html( get_theme_mod( 'sidebar_width', '30' ) ),
				'is_infinite_scroll' => esc_html( ( zoom_jetpack_active_module( 'infinite-scroll' ) ? true : false ) ),
				'is_rtl' => is_rtl(),
				'is_home' => is_home()
			)
		);

	/* Enqueue by Conditions */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	if ( get_theme_mod( 'effect_stt', true ) == true ) {
		wp_enqueue_script( 'jquery-scrollup' );
	}

	// We will use this when users install our recommended plugins to support header image slider
	if ( get_theme_mod( 'header_type' ) == 'slider' || zoom_is_wprepo_demo() ) {
		
		if ( zoom_slider_display_in() ) {
			
			wp_enqueue_script( 'jquery-nivo-slider' );
			wp_enqueue_style( 'nivo-slider' );
			wp_enqueue_style( 'nivo-slider-theme-default' );
		
		}
		
	}
	
	// Only for Gallery Post Type
	if ( has_post_format( 'gallery' ) ) {
		
		wp_enqueue_style( 'zoom-flexslider-style', get_template_directory_uri() . '/assets/lib/bower/flexslider/flexslider.css', array(), $zoom_version );
		wp_enqueue_script( 'zoom-flexslider', get_template_directory_uri() . '/assets/lib/bower/flexslider/jquery.flexslider-min.js', array( 'jquery' ) );

	}
	
	
}
add_action( 'wp_enqueue_scripts', 'zoom_theme_scripts' );

function zoom_hook_custom_javascript() {

	$cnt_txt = get_theme_mod( 'misc_custom_js', '' );
	echo "<!-- Zoom Lite Custom JS - START -->\n";
	echo wp_specialchars_decode( $cnt_txt, ENT_QUOTES );
	echo "\n<!-- Zoom Lite Custom JS - END -->\n";
	
}
add_action( 'wp_head', 'zoom_hook_custom_javascript' );