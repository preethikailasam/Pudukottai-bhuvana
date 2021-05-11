<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Theme Customizer settings.
 *
 * @package Zoom 
 * @since 1.0 
 */

/**
 * Theme customizer settings with real-time update
 * Very helpful: http://ottopress.com/2012/theme-customizer-part-deux-getting-rid-of-options-pages/
 *
 * @since 1.5
 */

if ( is_customize_preview() ) {
	
	// Load Customize Core
	require_once get_template_directory() . '/inc/customizer/customizer-default-options.php';
	require_once get_template_directory() . '/inc/customizer/customizer-functions.php';
	require_once get_template_directory() . '/inc/customizer/customizer-sanitize.php';
		
	// Load Customize Panels
	require_once get_template_directory() . '/inc/customizer/customizer-panels.php';
	
	// Load Customize Theme Info Section
	require_once get_template_directory() . '/inc/customizer/info/info-loader.php';
	
	// Zoom Custom Customize Control
	require_once get_template_directory() . '/inc/customizer/controls/image-slider-control/image-slider-control.php';
	require_once get_template_directory() . '/inc/customizer/controls/layout/layout-control.php';
	require_once get_template_directory() . '/inc/customizer/controls/slider/slider-control.php';
	require_once get_template_directory() . '/inc/customizer/controls/switch/switch-control.php';
	require_once get_template_directory() . '/inc/customizer/controls/multiple-checkbox/multiple-checkbox.php';
	
	// Zoom All Customize Options
	require_once get_template_directory() . '/inc/customizer/options/header/header-settings.php';
	require_once get_template_directory() . '/inc/customizer/options/layouts/layout-post-page.php';
	require_once get_template_directory() . '/inc/customizer/options/layouts/layout-site.php';
	require_once get_template_directory() . '/inc/customizer/options/layouts/layout-sidebar.php';
	require_once get_template_directory() . '/inc/customizer/options/layouts/layout-footer.php';
	require_once get_template_directory() . '/inc/customizer/options/post/post-excerpts.php';
	require_once get_template_directory() . '/inc/customizer/options/post/post-images.php';
	require_once get_template_directory() . '/inc/customizer/options/post/post-meta.php';
	require_once get_template_directory() . '/inc/customizer/options/post/post-related.php';	
	require_once get_template_directory() . '/inc/customizer/options/post/post-sticky.php';
	require_once get_template_directory() . '/inc/customizer/options/post/post-comments.php';
	require_once get_template_directory() . '/inc/customizer/options/styles/style-colors.php';
	require_once get_template_directory() . '/inc/customizer/options/styles/style-button.php';
	require_once get_template_directory() . '/inc/customizer/options/styles/style-color-scheme.php';
	require_once get_template_directory() . '/inc/customizer/options/branding/branding-logo.php';
	require_once get_template_directory() . '/inc/customizer/options/branding/topbar/top-bar.php';
	require_once get_template_directory() . '/inc/customizer/options/branding/bottombar/bottom-bar.php';
	require_once get_template_directory() . '/inc/customizer/options/menu/menu-options.php';
	require_once get_template_directory() . '/inc/customizer/options/misc/misc-effects.php';
	require_once get_template_directory() . '/inc/customizer/options/misc/misc-performances-seo.php';
	require_once get_template_directory() . '/inc/customizer/options/misc/misc-customjs.php';
	require_once get_template_directory() . '/inc/customizer/options/misc/misc-text.php';
	require_once get_template_directory() . '/inc/customizer/options/misc/misc-admin.php';
	
	// 3rd-Party Plugins Integration
	
	if ( class_exists( 'RevSlider' ) ) {
		
		require get_template_directory() . '/inc/customizer/mods/zoom-mods-revslider.php';
		
	}
	
}