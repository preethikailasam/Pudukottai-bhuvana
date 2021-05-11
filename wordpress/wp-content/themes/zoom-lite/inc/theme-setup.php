<?php
/*
 * Theme Setup
 *
 * @package zoom
 * @subpackage Functions
*/

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since 1.0
 */
 
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! isset( $content_width ) ) {
	$content_width = get_theme_mod( 'site_maxwidth', '1200' ).'px';
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}

}
 
function zoom_theme_setup() {
	
	// Copy current Settings from Parent theme to any child themes on first activate ( in child side )
	if ( is_child_theme() ) {
		
		$child_slug = get_stylesheet();
		$child_mod = 'theme_mods_'.$child_slug;
		$first_install = get_option( $child_slug.'_first_install' );

		if ( ! $first_install ) {

			$parent_opt = get_option( 'theme_mods_'.get_template() );

			if ( ! empty( $parent_opt ) ) {

				update_option( $child_mod, $parent_opt );
				update_option( $child_slug.'_first_install', true );
				
			}
			
		}
		
	}
	
	/**
	 * This theme styles the visual editor with editor-style.css to match the theme style.
	 */
	add_editor_style( 'inc/admin/assets/css/editor-style.css' );
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'zoom-lite', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Add support for background color and images
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => '3f3f3f',
	) );
	
	/**
	 * Add support for HeaderCustom Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 200,
		'width'       => 200,
		'flex-width' => true,
		'flex-height' => true,
	) );
	
	/**
	 * Filter Zoom Lite custom-header support arguments.
	 */
	add_theme_support( 'custom-header', apply_filters( 'zoom_lite_custom_header_args', array(
		'default-image'      => get_template_directory_uri().'/assets/images/header/default.jpg',
		'width'              => 1200,
		'header-text'        => false,
		'height'             => 350,
		'flex-height'        => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header/default.jpg',
			'thumbnail_url' => '%s/assets/images/header/default.jpg',
			'description'   => esc_html__( 'Default Header Image', 'zoom-lite' ),
		),
	) );
	
	/**
	 * Enable support for JetPack Infinite Scroll
	 */
	add_theme_support( 'infinite-scroll', array(
		'container' => 'zoom-masonry-mode',
		'render' => 'zoom_infinite_scroll_render',
		'wrapper' => false,
		'footer' => false,
		'posts_per_page' => get_theme_mod( 'misc_jpis_per_page', 7 )
	) );

	/**
	 * Add image sizes
	 */
	 
    // Thumbnail sizes
	add_image_size( 'zoom-img-thumbnail', 200, 200, true );
    add_image_size( 'zoom-img-medium', 320, 320, true );
    add_image_size( 'zoom-img-mlarge', 520, 245, true );
    add_image_size( 'zoom-img-large', 720, 340, true );
	
	// Slider
	add_image_size( 'zoom-img-slider', 1200 ); // No Hard Crop
	
	// Set the default Featured Image (formerly Post Thumbnail) dimensions.
	 set_post_thumbnail_size( 200, 200, array( 'center', 'center')  );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'zoom-lite' )
	) );
	
	// Make theme woocommerce ready
	add_theme_support( 'woocommerce' );
	
	// Enable post format support
	add_theme_support( 'post-formats', array( 'audio', 'video', 'gallery' ) );
	
}
add_action( 'after_setup_theme', 'zoom_theme_setup' );


/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since 1.0
 */
function zoom_theme_widgets_init() {
	register_sidebar( array(
		'name' => esc_html__( 'Primary Sidebar', 'zoom-lite' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Zoom Footer ( Left )', 'zoom-lite' ),
		'id' => 'footer-left',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Zoom Footer ( Center )', 'zoom-lite' ),
		'id' => 'footer-center',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Zoom Footer ( Right )', 'zoom-lite' ),
		'id' => 'footer-right',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'zoom_theme_widgets_init' );