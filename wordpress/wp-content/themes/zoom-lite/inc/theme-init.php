<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'OT_Loader' ) ) {
	
    add_filter( 'ot_theme_mode', '__return_true' );
	/* ------------------------------------------------------------------------- *
	 *  OptionTree framework integration: Use in theme mode
	/* ------------------------------------------------------------------------- */
	add_filter( 'ot_show_pages', '__return_false' );
	add_filter( 'ot_show_new_layout', '__return_false' );
	add_filter( 'ot_theme_mode', '__return_true' );
	
	
	/* ------------------------------------------------------------------------- *
	 *  OptionTree options moved to the customizer
	/* ------------------------------------------------------------------------- */
	//disable all ot options : page, etc...
	add_filter( 'ot_use_theme_options',  '__return_false' );
	
    load_template( get_template_directory() . '/option-tree/ot-loader.php' );
	add_filter( 'ot_upload_text', 'zoom_replace_media_upload_button' );
	
}

require get_template_directory() . '/inc/theme-setup.php';
require get_template_directory() . '/inc/theme-functions.php';
require get_template_directory() . '/inc/extensions/ext-jetpack-infinite-scroll.php';
require get_template_directory() . '/inc/theme-structured-data.php';
require get_template_directory() . '/inc/theme-scripts.php';
require get_template_directory() . '/inc/theme-loop.php';
require get_template_directory() . '/inc/theme-comments.php';
require get_template_directory() . '/inc/theme-frontend-hooks.php';
require get_template_directory() . '/inc/theme-frontend-maker.php';
require get_template_directory() . '/inc/helpers/helper-post.php';
require get_template_directory() . '/inc/helpers/helper-top-bar.php';
require get_template_directory() . '/inc/helpers/helper-nav.php';
require get_template_directory() . '/inc/helpers/helper-header.php';
require get_template_directory() . '/inc/helpers/helper-layout.php';
require get_template_directory() . '/inc/helpers/helper-bottom-bar.php';

if ( is_admin() ) {
	
	require_once get_template_directory() . '/inc/admin/zoom-about-page/class-zoom-about-page.php';
	require_once get_template_directory() . '/inc/admin/zoom-about-page/about-config.php';
	require get_template_directory() . '/inc/admin/admin-init.php';
	require get_template_directory() . '/inc/admin/tgm-plugin/tgm-hook.php';
	require get_template_directory() . '/inc/admin/extensions/ot-metaboxes.php';
	
}

if ( zoom_is_wprepo_demo() ) {
	
	require get_template_directory() . '/inc/theme-preview.php';
	
}

if ( is_customize_preview() ) {

	require get_template_directory() . '/inc/customizer/zoom-customizer.php';
	
}