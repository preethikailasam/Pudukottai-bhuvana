<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add CSS in <head> for styles handled by the theme customizer
 *
 * @since 1.5
 */
function zoom_theme_frontend_properties() {
	
	$cnt_txt = get_theme_mod( 'content_text_color', '#333' );
	$menu_bg = get_theme_mod( 'main_menu_bg', '#228ed6' );
	$menu_txt = get_theme_mod( 'main_menu_txt', '#ffffff' );
	$sub_menu_bg = get_theme_mod( 'sub_menu_bg', '#54af1c' );
	$sub_menu_txt = get_theme_mod( 'sub_menu_txt', '#ffffff' );
	$content_pad = get_theme_mod( 'content_padding', '5' ) / 2;
	$site_max_w = zoom_site_max_width( get_theme_mod( 'site_layout', 'boxed' ) );
	$calibrate = 100 - ( $content_pad * 2 );
	$calibrate_mn = 100 - $content_pad;
	$tb_max_w = 50 - $content_pad;
	$footer_widget_w = ( 100 - $content_pad ) / 3;
	$sidebar_w = get_theme_mod( 'sidebar_width', '30' );
	$content_w = 100 - $sidebar_w;
	$color = get_theme_mod( 'link_color', '#1e73be' );
	$bradius = ( get_theme_mod( 'post_thumb_radius', '0' ) != '0' ? 'img.wp-post-image {
			-webkit-border-radius: '.get_theme_mod( 'post_thumb_radius', '50' ).'%'.';
			-moz-border-radius: '.get_theme_mod( 'post_thumb_radius', '50' ).'%'.';
			border-radius: '.get_theme_mod( 'post_thumb_radius', '50' ).'%'.';
		}' : '' );
	$bg_col = ( get_background_color() ? get_background_color() : get_theme_support( 'custom-background', 'default-color' ) );

		$custom_css = "";
		/* Body */
		$custom_css .= "body {
			background-color: #".esc_html( $bg_col ).";
		}";
		
		/* Layout */
		$custom_css .= ".zoom-site,
		.menu-box,
		.nav-holder { max-width:".esc_html( $site_max_w ).";}";
			
		$custom_css .= ".top-bar-left,
		.top-bar-right {max-width: 45%;
		}";
		
		// @since 1.0.0.51
		$custom_css .= ".nav-holder,
		.menu-box-mobile,
		.top-bar-holder,
		.title-holder,
		.logo-title-holder,
		.bottom-bar-holder,
		#secondary,
		#tertiary,
		#footer-container {
			padding-left: 2.5%;
			padding-right: 2.5%;
		}";
		
		$custom_css .= "#primary.blog-with-sidebar-left,
		#primary.blog-with-sidebar-right,
		#primary.page-with-sidebar-left,
		#primary.page-with-sidebar-right {	
			width: ".esc_html( $content_w )."%;
		}";
		$custom_css .= "#primary {
			padding: ".esc_html( $content_pad )."em;
		}";
		$custom_css .= "#secondary,
		#tertiary {
			width: ".esc_html( get_theme_mod( 'sidebar_width', '30' ) )."%;
		}";
		// NOTE: .link-holder padding removed @since 1.0.0.51
		$custom_css .= ".nosidebar {
			padding-left: ".esc_html( $content_pad )."%;
			padding-right: ".esc_html( $content_pad )."%;
		}";
		$custom_css .= ".error404 #primary {
			padding-left: 10%;
		}";
		
		/*resize footer widget base on content padding */
		$custom_css .= ".site-footer .widget-area {
				width: ".esc_html( $footer_widget_w )."%;
				min-width: ".esc_html( $footer_widget_w )."%;
			}";
		
		/* Coloring */
		$custom_css .= "a, a:visited {
			color: ".esc_html( $color ).";
			outline: none !important;
		}";
		
		/* Navigation Text */
		$custom_css .= ".blue.zoom-btn, .turq.zoom-btn, .green.zoom-btn, .red.zoom-btn, .grey.zoom-btn, .purple.zoom-btn, .orange.zoom-btn, .pink.zoom-btn {
			color: ".esc_html( get_theme_mod( 'site_nav_col', '#ffffff' ) ).";
		}";
		
		/* Header Area */
		$custom_css .= ".site-header {
			background-color: ".esc_html( get_theme_mod( 'site_header_color', '#228ed6' ) ).";
		}";
		
		$custom_css .= "#primary,
		.zoom-blog-default,
		.commentlist .comment-item {
			background-color: ".esc_html( get_theme_mod( 'content_bg_color', '#f5f5f5' ) ).";
		}";
		
		$custom_css .= ".image-title .site-title,
		.logo-title-mode .site-title {
			color: ".esc_html( get_theme_mod( 'site_title_color', '#1e73be' ) ).";
		}";
		
		$custom_css .= ".image-title .site-description,
		.logo-title-mode .site-description {
			color: ".esc_html( get_theme_mod( 'site_desc_color', '#e7e7e7' ) ).";
		}";
		
		// Title & Tagline container
		$custom_css .= ".site-logo,
		.site-identity {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}";
		
		// Set Title & Tagline container max-width
		$site_pad = $content_pad * 2;
		$title_w_minus_img = 100 - $content_pad;
		$custom_css .= ".site-identity {

		}";
		
		// Image slider height
		$custom_css .= ".zoom-slider-wrapper {
			max-height: ".( get_theme_mod( 'header_slider_is_max_height', true ) == true ? esc_html( get_theme_mod( 'header_slider_max_height', 350 ) ).'px' : '100%' ).";
		}";
		
		// Image slider height
		$custom_css .= ".theme-default .nivoSlider img {
			".( get_theme_mod( 'header_slider_is_max_height', true ) == true ? 'max-height: 100%' : 'height:auto' ).";
		}";
		
		if ( get_theme_mod( 'header_type' ) == 'image_title_logo' ) {
			$custom_css .= ".image-header-container-with-logo-title {
				background-image: url(".esc_url( zoom_theme_get_header_image() ).");
				background-size: cover;
				background-position: 50% 50%;
				background-repeat: no-repeat;
				position: relative;
			}";
		}
		
		$custom_css .= "#content,
		.zoom-pag-note,
		.commentlist:not(.bypostauthor) {
			color: ".esc_html( $cnt_txt ).";
		}";
		$custom_css .= ".bypostauthor {
			color: #404040;
		}";
		
		$custom_css .= ".entry-meta {
			color: ".esc_html( get_theme_mod( 'post_meta_col', '#aaa' ) ).";
		}";
		
		/* Accents Color */
		$custom_css .= ".zoom-blog-entry-content .entry-meta,
		#content .entry-meta,
		#content .site-navigation,
		.comment-navigation,
		.single .comments-title,
		#primary .zoom-blog-default,
		#authorbox,
		.share-buttons-cont,
		.zoom-page-pag,
		.commentlist .comment-item,
		.commentlist .perma-reply-edit {
			border-color: ".esc_html( get_theme_mod( 'content_accent_col', '#d3d3d3' ) ).";
		}";
		$custom_css .= ".comment-meta hr {
			background-color: ".esc_html( get_theme_mod( 'content_accent_col', '#d3d3d3' ) ).";
		}";
		
		/* Shadow */
		$custom_css .= "#primary .zoom-blog-default:not(.sticky) {".( get_theme_mod( 'post_cols_shadow', true ) ? "
			-webkit-box-shadow: 2px 3px 7px -1px rgba(50, 50, 50, 0.2);
			-moz-box-shadow:    2px 3px 7px -1px rgba(50, 50, 50, 0.2);
			box-shadow:         2px 3px 7px -1px rgba(50, 50, 50, 0.2);
			" : "" )."}";
		
		$custom_css .= "footer.entry-meta {
			border: none !important;
		}";
		
		/* Author Box */
		$custom_css .= "#authorbox {
			background-color: ".esc_html( get_theme_mod( 'author_box_bg', '#efefef' ) ).";
		}";
		
		$custom_css .= "#authorbox h4, #authorbox p {
			color: ".esc_html( get_theme_mod( 'author_box_txt_col', '#333' ) )."; 
		}";
		
		/* Widget Area */
		$custom_css .= "#secondary .widget-title,
		#tertiary .widget-title {
			color: ".esc_html( get_theme_mod( 'sidebar_ttl_col', '#fff' ) ).";
		}";
		
		$custom_css .= "#secondary .textwidget,
		#tertiary .textwidget {
			color: ".esc_html( get_theme_mod( 'sidebar_txt_col', '#f7f7f7' ) ).";
		}";
		
		$custom_css .= "#secondary a,
		#tertiary a,
		.widget-area aside ul li a:visited,
		#secondary ul li,
		#tertiary ul li,
		.widget_recent_entries ul li a:before,
		.widget_recent_comments ul li:before,
		.widget_archive ul li a:before,
		.widget_categories ul li a:before {
			color: ".esc_html( get_theme_mod( 'sidebar_link_txt_col', '#e0e0e0' ) ).";
		}";
		
		$custom_css .= ".widget {
			border-color: ".esc_html( zoom_hex2rgba( get_theme_mod( 'sidebar_bor_col', '#d3d3d3' ), '0.4' ) ).";
		}";
		
		$custom_css .= "#secondary a:hover,
		#tertiary a:hover {
			text-decoration: underline;
		}";

		/* Sidebar Styles */
		
		if ( ( strtolower( get_theme_mod( 'blog_layout', 'right' ) ) != 'none' || strtolower( get_theme_mod( 'page_layout', 'right' ) ) != 'none' ) ) {
			$custom_css .= "#zoom-theme-main {
				background: ".esc_html( get_theme_mod( 'sidebar_bg', '#228ed6' ) ).";
			}";
		}
			
		if ( get_theme_mod( 'sidebar_bor', true ) == true ) {
			
			$sdrfilter = array( 'post', 'page' );
			$sidebartype = get_post_type( get_the_ID() );
			
			if ( in_array( $sidebartype, $sdrfilter ) ) {
				
				$sidebartype = $sidebartype;
				
				if ( $sidebartype == 'post' ) {
					$sidebartype = 'blog';
				}
				
				$custom_css .= "#primary:not(.nosidebar) { border-".esc_html( strtolower( get_theme_mod( ''.$sidebartype.'_layout', 'right' ) ) ).": ".esc_html( get_theme_mod( 'sidebar_bor_width', '5' ) )."px ".esc_html( get_theme_mod( 'sidebar_bor_type', 'solid' ) )." ".esc_html( get_theme_mod( 'sidebar_bor_col', '#e2e2e2' ) ).";}";
			}
		}

		/* Main menu  */
		$custom_css .= "#zoomnav a,
		#zoom-mobile-nav a,
		#nav-toggle span {
			color: ".esc_html( $menu_txt ).";
		}";
		
		$custom_css .= ".menu-box,
		#zoomnav,
		.menu-box-mobile,
		#zoom-mobile-nav,
		.nav-holder,
		.zoom-menu-nav,
		#nav-toggle,
		#zoomnav.menu,
		#zoom-mobile-nav.menu-mobile {
			background-color: ".esc_html( $menu_bg ).";
		}";
		
		$custom_css .= "#zoomnav ul li:hover, #zoom-mobile-nav ul li:hover {
			background-color: ".esc_html( $sub_menu_bg ).";
			color: ".esc_html( $sub_menu_txt ).";
		}";
		
		$custom_css .= "#zoom-mobile-nav ul > li.current_page_item ,
		#zoom-mobile-nav ul > li.current-menu-item,
		#zoom-mobile-nav ul > li.current_page_ancestor,
		#zoom-mobile-nav ul > li.current-menu-ancestor,
		#zoomnav ul > li.current_page_item,
		#zoomnav ul > li.current-menu-item,
		#zoomnav ul > li.current_page_ancestor,
		#zoomnav ul > li.current-menu-ancestor {
			background-color: ".esc_html( zoom_hexadder( $menu_bg, '13' ) ).";
		}";
		
		$custom_css .= "#zoom-mobile-nav ul ul li.current_page_item,
		#zoom-mobile-nav ul ul li.current-menu-item,
		#zoom-mobile-nav ul ul li.current_page_ancestor,
		#zoom-mobile-nav ul ul li.current-menu-ancestor,	
		#zoomnav ul ul li.current_page_item,
		#zoomnav ul ul li.current-menu-item,
		#zoomnav ul ul li.current_page_ancestor,
		#zoomnav ul ul li.current-menu-ancestor {
			background-color: ".esc_html( zoom_hexadder( $sub_menu_bg, '14' ) ).";
		}";		
		
		/* Main menu Submenus */
		$custom_css .= "#zoom-mobile-nav ul ul li,
		#zoom-mobile-nav ul ul,
		#zoomnav ul ul li,
		#zoomnav ul ul {
			background-color: ".esc_html( $sub_menu_bg ).";
		}";
		
		$custom_css .= "#zoomnav ul ul li a, #zoom-mobile-nav ul ul li a {
			color: ".esc_html( $sub_menu_txt ).";
		}";
		
		$custom_css .= "#zoomnav ul ul li:hover, #zoom-mobile-nav ul ul li:hover {
			background: ".esc_html( zoom_hexadder( $sub_menu_bg, '14' ) ).";
		}";
		
		$custom_css .= ".navborberonscroll {
			border-bottom: 4px solid ".esc_html( zoom_hexadder( $menu_bg, '13' ) ).";
		}";		
		
		$custom_css .= ".nav-before-header .logo-title-mode {
			border-top: 1px solid ".esc_html( zoom_hexadder( get_theme_mod( 'site_header_color', '#228ed6' ), '13' ) ).";
			border-bottom: 1px solid ".esc_html( zoom_hexadder( $menu_bg, '13' ) ).";
		}";		
		
		$custom_css .= ".nav-after-header .logo-title-mode {
			border-bottom: 1px solid ".esc_html( zoom_hexadder( get_theme_mod( 'site_header_color', '#228ed6' ), '13' ) ).";
		}";
		
		/* Footer Area  */
		$custom_css .= ".site-footer {
			background-color:".esc_html( get_theme_mod( 'footer_bg', '#1a7dc0' ) ).";
			border-top: 1px solid ".esc_html( zoom_hexadder( get_theme_mod( 'footer_bg', '#1a7dc0' ), '35' ) ).";
		}";
		
		$custom_css .= "#footer-container {
			background-color: ".esc_html( get_theme_mod( 'footer_bg', '#1a7dc0' ) ).";
			". ( ! is_active_sidebar( 'footer-left' ) && ! is_active_sidebar( 'footer-center' ) && ! is_active_sidebar( 'footer-right' ) && ! is_customize_preview() && ! zoom_is_wprepo_demo() ? "display: none;" : "" )."
		}";
			
		$custom_css .= "#footer-container .widget-title {
			color: ".esc_html( get_theme_mod( 'footer_ttl_col', '#457fffcc3' ) ).";
		}";
		
		$custom_css .= "#footer-container, #footer-container .textwidget {
			color: ".esc_html( get_theme_mod( 'footer_txt_col', '#f7f7f7' ) ).";
		}";
		
		$custom_css .= "#footer-container a,
		#footer-container ul li,
		#footer-container ul li {
			color: ".esc_html( get_theme_mod( 'footer_link_txt_col', '#e0e0e0' ) ).";
		}";		
		
		/* Top Bar Area  */
		$custom_css .= "#top-bar {
			background-color:".esc_html( get_theme_mod( 'top_bar_bg', '#228ed6' ) ).";
			color: ".esc_html( get_theme_mod( 'top_bar_txt_col', '#f7f7f7' ) ).";
			border-bottom: 1px solid ".esc_html( zoom_hexadder( get_theme_mod( 'top_bar_bg', '#228ed6' ), '14' ) ).";
		}";
		
		$custom_css .= "#top-bar a,
		#top-bar ul li,
		#top-bar ul li {
			color: ".esc_html( get_theme_mod( 'top_bar_link_txt_col', '#f7f7f7' ) ).";
		}";
		
		$custom_css .= "#top-bar a:hover {
			text-decoration: underline;
		}";
		
		/* Bottom Bar Area  */
		$custom_css .= "#bottom-bar {
			background-color: ".esc_html( get_theme_mod( 'bottom_bar_bg', '#166cad' ) ).";
			color: ".esc_html( get_theme_mod( 'bottom_bar_txt_col', '#f7f7f7' ) ).";
			border-top: 1px solid ".esc_html( zoom_hexadder( get_theme_mod( 'bottom_bar_bg', '#1a7dc0' ), '14' ) ).";
		}";
		
		$custom_css .= "#bottom-bar a,
		#bottom-bar ul li,
		#bottom-bar ul li {
			color: ".esc_html( get_theme_mod( 'bottom_bar_link_txt_col', '#e0e0e0' ) ).";
		}";
		$custom_css .= "#footer-container .widget {
			border: none;
		}";
		
		$custom_css .= "#footer-container a:hover, #bottom-bar a:hover {
			text-decoration: underline;
		}";	

		/* Misc */
		/* Breadcrumbs */
		$custom_css .= ".breadcrumb:not(i) {
			border-color: ".esc_html( zoom_hex2rgba( get_theme_mod( 'post_meta_col', '#aaa' ), '0.2' ) ).";
		}";
		
		/* Sticky */
		$custom_css .= "#primary .sticky {
			background-color: ".( get_theme_mod( 'sticky_bg_col', '#fef3e3' ) ).";
			".( get_theme_mod( 'sticky_bor', true ) == true ? "
			border: ".esc_html( get_theme_mod( 'sticky_bor_width', '7' ) )."px ".esc_html( get_theme_mod( 'sticky_bor_type', 'solid' ) )." ".esc_html( get_theme_mod( 'sticky_bor_col', '#dddddd' ) ).";" : "border: none !important;" )."
		}";
		
		/* Sticky Ribbon */
		if ( get_theme_mod( 'sticky_bor', true ) == true ) {
		$border_w = ( is_rtl() ? 29 : 10 ) + esc_html( get_theme_mod( 'sticky_bor_width', '7' ) );
		$custom_css .= ".ribbon-container {
			right: -".$border_w."px;
		}
		.rtl .ribbon-container {
			right: auto;
			left: -".$border_w."px;
		}";
		} else {
		$custom_css .= ".ribbon-container {
			right: -10px;
		}
		.rtl .ribbon-container {
			right: auto;
			left: -29px;
		}";
		}
		if ( get_theme_mod( 'sticky_ribbon', true ) == true ) {
		$custom_css .= ".ribbon-container span, .mobile-ribbon-container {
			background: ".esc_html( get_theme_mod( 'sticky_ribbon_col', '#228ed6' ) ).";
		} .ribbon-container span::before {
			border-top: 21px solid ".esc_html( get_theme_mod( 'sticky_ribbon_col', '#228ed6' ) ).";
			border-bottom: 19px solid ".esc_html( get_theme_mod( 'sticky_ribbon_col', '#228ed6' ) ).";
		} .ribbon-container span::after {
			border-top: 10px solid ".esc_html( get_theme_mod( 'sticky_ribbon_col', '#228ed6' ) ).";
		}";
		}
		
		/* Share Button Position */
		$custom_css .= ".share-buttons-cont {
			text-align: ".esc_html( get_theme_mod( 'shrbtn_pos', 'right' ) ).";
		}";
		
		$custom_css .= ".top-bar-right ul li:not(:last-child) {
			margin-right: 15px;
		}";
		
		
		/* Comments */
		$custom_css .= ".bypostauthor .ribbon-blue {
			background-color: ".esc_html( get_theme_mod( 'post_author_badge_color', '#459dd8' ) ).";
		}";
		
		if ( get_theme_mod( 'post_hide_disable_comments_note', true ) == true ) {
			
			$custom_css .= "p.nocomments {
				display: none;
			}";
		
		}
		
		/* Post Nav */
		if ( get_theme_mod( 'post_next_prev', true ) != true ) {
			
			$custom_css .= ".site-navigation.post-navigation {
				border: none !important;
			}";
		
		}
		
		/* JetPack Infinite Scroll */
		if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) {
			
			$custom_css .= ".infinite-scroll .edit-link {
				display: none;
				}";

			if ( zoom_jetpack_active_module( 'infinite-scroll', 'infinite_scroll' ) ) {
				
				$custom_css .= ".infinite-scroll #nav-below:not(.jpkis-scroll-only) {
								display: none;
							}";
				
				$custom_css .= ".infinite-scroll .jpk-scroll-only {
									text-align: center;
									width:78px;
									height:49px;
									display:block;
									width:100%;
									margin:0 auto;
									margin-top: 30px;
								}
								
								.site-navigation.jpkis-scroll-only{
									border-top: none !important;
								}
								
								.bubblingG span {
									display: inline-block;
									vertical-align: middle;
									width: 10px;
									height: 10px;
									margin: 24px auto;
									background: rgb(0,0,0);
									border-radius: 49px;
										-o-border-radius: 49px;
										-ms-border-radius: 49px;
										-webkit-border-radius: 49px;
										-moz-border-radius: 49px;
									animation: bubblingG 0.455s infinite alternate;
										-o-animation: bubblingG 0.455s infinite alternate;
										-ms-animation: bubblingG 0.455s infinite alternate;
										-webkit-animation: bubblingG 0.455s infinite alternate;
										-moz-animation: bubblingG 0.455s infinite alternate;
								}
								
								#bubblingG_1 {
									animation-delay: 0s;
										-o-animation-delay: 0s;
										-ms-animation-delay: 0s;
										-webkit-animation-delay: 0s;
										-moz-animation-delay: 0s;
								}
								
								#bubblingG_2 {
									animation-delay: 0.1395s;
										-o-animation-delay: 0.1395s;
										-ms-animation-delay: 0.1395s;
										-webkit-animation-delay: 0.1395s;
										-moz-animation-delay: 0.1395s;
								}
								
								#bubblingG_3 {
									animation-delay: 0.269s;
										-o-animation-delay: 0.269s;
										-ms-animation-delay: 0.269s;
										-webkit-animation-delay: 0.269s;
										-moz-animation-delay: 0.269s;
								}
								
								
								
								@keyframes bubblingG {
									0% {
										width: 10px;
										height: 10px;
										background-color:rgb(0,0,0);
										transform: translateY(0);
									}
								
									100% {
										width: 23px;
										height: 23px;
										background-color:rgb(255,255,255);
										transform: translateY(-20px);
									}
								}
								
								@-o-keyframes bubblingG {
									0% {
										width: 10px;
										height: 10px;
										background-color:rgb(0,0,0);
										-o-transform: translateY(0);
									}
								
									100% {
										width: 23px;
										height: 23px;
										background-color:rgb(255,255,255);
										-o-transform: translateY(-20px);
									}
								}
								
								@-ms-keyframes bubblingG {
									0% {
										width: 10px;
										height: 10px;
										background-color:rgb(0,0,0);
										-ms-transform: translateY(0);
									}
								
									100% {
										width: 23px;
										height: 23px;
										background-color:rgb(255,255,255);
										-ms-transform: translateY(-20px);
									}
								}
								
								@-webkit-keyframes bubblingG {
									0% {
										width: 10px;
										height: 10px;
										background-color:rgb(0,0,0);
										-webkit-transform: translateY(0);
									}
								
									100% {
										width: 23px;
										height: 23px;
										background-color:rgb(255,255,255);
										-webkit-transform: translateY(-20px);
									}
								}
								
								@-moz-keyframes bubblingG {
									0% {
										width: 10px;
										height: 10px;
										background-color:rgb(0,0,0);
										-moz-transform: translateY(0);
									}
								
									100% {
										width: 23px;
										height: 23px;
										background-color:rgb(255,255,255);
										-moz-transform: translateY(-20px);
									}
								}";
									
							
			} else {
			
						$custom_css .= ".infinite-scroll #nav-below:not(.jpkis-nav-only),
			.infinite-scroll #infinite-footer,
			span.edit-link,
			.infinite-loader {
				display: none;
			}";
			
			$custom_css .= "#infinite-handle span {
				background: none;
				}";
				
			}
			
		}
		
		// WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			
			$custom_css .= ".woocommerce-page .product {
				color: ".esc_html( $cnt_txt ).";
			}";
			
			$custom_css .= ".woocommerce-page #container {
			background-color: ".esc_html( get_theme_mod( 'content_bg_color', '#f5f5f5' ) ).";
			color: ".esc_html( $cnt_txt ).";
			padding: ".esc_html( $content_pad )."em;
			-webkit-box-sizing: border-box;
			   -moz-box-sizing: border-box;
					box-sizing: border-box;
			}";
			
			$custom_css .= ".woocommerce-main-image img {
				width: 100%;
				max-width: 250px;
			}";
			
			$custom_css .= ".woocommerce img, .woocommerce-page img {
				height: auto;
				width: auto;
			}";
		
		}
		
		$custom_css .= "".esc_html( $bradius )."";
		
		$custom_css .= "@media screen and ( max-width: 920px ) {
			
			#primary.blog-with-sidebar-left,
			#primary.blog-with-sidebar-right,
			.rightside,
			.leftside,
			.nosidebar,
			#secondary,
			#tertiary {
				padding-left: ".esc_html( $content_pad )."%;
				padding-right: ".esc_html( $content_pad )."%;
			}
			.rightside,
			.leftside {
				margin: 0;
				border: none;
			}
		}";
		
		if ( is_single() || is_page() ) {
		$custom_css .= "@media screen and ( max-width: 480px ) {

			.entry-header .entry-meta {
				margin-bottom: 10px;
			}
			
		}";
		}
		
		if ( get_theme_mod( 'sticky_ribbon', true ) == true ) {
		$custom_css .= "@media screen and ( max-width: 480px ) {
			
			.ribbon-container { display: none; }
			.mobile-ribbon-container { display: block;}
			
		}";
		}
		
	
	// Sanitize the output
	$allowed_tags = wp_kses_allowed_html( 'post' );
	$inline_css = wp_kses( stripslashes_deep( zoom_css_compress( $custom_css ) ), $allowed_tags );

	wp_add_inline_style( 'zoom-theme-main-style', htmlspecialchars_decode( $inline_css, ENT_QUOTES ) );
	
}
add_action( 'wp_enqueue_scripts', 'zoom_theme_frontend_properties', 21 );