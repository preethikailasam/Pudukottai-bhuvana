<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php publishers_schema_type(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="hfeed">
<header id="header" role="banner">
<div id="header-mobile" class="mobile">
<nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<span class="looper before" tabindex="0"></span>
<button type="button" class="menu-toggle first"><span class="menu-text screen-reader-text"><?php esc_html_e( ' Menu', 'publishers' ); ?></span><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/images/menu.svg" alt="Menu" class="menu-icon closed svg" /><span class="menu-icon open">×</span></button>
<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
<div id="search"><form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url() ); ?>/"><label><span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'publishers' ); ?></span><input type="search" class="search-field last" placeholder="<?php esc_attr_e( 'Search …', 'publishers' ); ?>" value="" name="s"><span></span></label><input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'publishers' ); ?>"></form></div>
<span class="looper after" tabindex="0"></span>
</nav>
<nav id="account" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<?php wp_nav_menu( array( 'theme_location' => 'account-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>', 'fallback_cb' => false ) ); ?>
<?php $author = wp_get_current_user(); ?>
<style>#account ul.menu > li > a:before{background:url(<?php echo get_avatar_url( $author -> user_email, array( 'size' => 64 ) ); ?>);background-size:32px 32px}</style>
</nav>
<?php get_template_part( 'site-title' ); ?>
</div>
<a href="<?php echo esc_url( home_url() ); ?>/wp-admin/post-new.php" id="write-now" class="button mobile"><?php _e( 'Write Now!', 'publishers' ); ?></a>
<div id="header-desktop" class="desktop">
<?php get_template_part( 'site-title' ); ?>
<a href="<?php echo esc_url( home_url() ); ?>/wp-admin/post-new.php" id="write-now" class="button"><?php _e( 'Write Now!', 'publishers' ); ?></a>
<div id="navigation">
<nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
</nav>
<div id="search"><form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url() ); ?>/"><label><span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'publishers' ); ?></span><input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search …', 'publishers' ); ?>" value="" name="s"><span></span></label><input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'publishers' ); ?>"></form></div>
<nav id="account" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<?php wp_nav_menu( array( 'theme_location' => 'account-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>', 'fallback_cb' => false ) ); ?>
</nav>
</div>
</div>
</header>