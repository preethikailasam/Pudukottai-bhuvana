<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Zoom
 * @since 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 9]>
<html id="unsupported" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
wp_body_open();
?>
<?php if ( get_theme_mod( 'effect_screen_preload', false ) == true ) zoom_screen_preload(); ?>
<div id="page" class="site entirely-page zoom-site">
	<?php do_action( 'zoom_theme_top_bar' ); ?>
	<header id="masthead" class="site-header <?php echo esc_attr( get_theme_mod( 'menu_pos', 'nav-after-header' ) ); ?>" role="banner">
	<?php do_action( 'zoom_theme_before_header' ); ?>
    <?php do_action( 'zoom_theme_header' ); ?>
	<?php do_action( 'zoom_theme_after_header' ); ?>
	</header><!-- #masthead .site-header -->
	<div id="zoom-theme-main">