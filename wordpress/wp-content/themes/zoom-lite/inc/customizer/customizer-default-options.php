<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


function zoom_default_theme_options() {
	
	return array(
		'header_type' => 'slider',
		'header_image' => get_template_directory_uri().'/assets/images/header/default.jpg',
		'header_slider' => '',
		'header_slider_is_max_height' => true,
		'header_slider_max_height' => 350,
		'header_slider_p_time' => 3,
		'header_slider_effect' => 'fade',
		'header_slider_hp_only' => false,
		'site_bg_color' => '#3f3f3f',
		'site_header_color' => '#228ed6',
		'site_title_color' => '#1e73be',
		'site_desc_color' => '#e7e7e7',
		'link_color' => '#1e73be',
		'content_bg_color' => '#f5f5f5',
		'content_text_color' => '#333',
		'content_accent_col' => '#d3d3d3',
		'site_nav_col' => '#ffffff',
		'main_logo_on_nav' => false,
		'main_logo_on_mobile_nav' => false,
		'bottom_logo' => get_template_directory_uri().'/assets/images/logo/zoom-bottom-logo.png',
		'menu_pos' => 'nav-after-header',
		'menu_align' => 'left',
		'menu_floating' => true,
		'menu_search' => false,
		'main_menu_bg' => '#228ed6',
		'main_menu_txt' => '#ffffff',
		'menu_home_btn_txt' => 'Home',
		'menu_home_btn' => false,
		'sub_menu_bg' => '#54af1c',
		'sub_menu_txt' => '#ffffff',
		'site_layout' => 'boxed',
		'blog_layout' => 'right',
		'page_layout' => 'right',
		'content_padding' => '5',
		'site_maxwidth' => '1200',
		'sidebar_width' => '30',
		'sidebar_bor' => true,
		'sidebar_bg' => '#228ed6',
		'sidebar_bor_type' => 'solid',
		'sidebar_bor_width' => 5,
		'sidebar_ttl_col' => '#fff',
		'sidebar_link_txt_col' => '#e0e0e0',
		'sidebar_txt_col' => '#f7f7f7',
		'sidebar_bor_col' => '#e2e2e2',
		'footer_bg' => '#1a7dc0',	
		'footer_ttl_col' => '#fff',
		'footer_txt_col' => '#f7f7f7',		
		'footer_link_txt_col' => '#e0e0e0',
		'footer_layout' => array( 'widget_left', 'widget_center', 'widget_right' ),
		'excerpts_text' => 'Read More',
		'excerpt_length' => 45,
		'featured_image_on_post_list' => true,
		'featured_image_on_single' => false,
		'featured_image_placeholder' => true,
		'post_layout' => 'grid',
		'post_layout_cols' => 'two',
		'post_cols_shadow' => true,
		'post_thumb_radius' => '0',
		'post_blog_thumb_size' => 'medium',
		'post_single_thumb_size' => 'full',
		'post_content' => 'excerpts',
		'post_meta_col' => '#aaa',
		'post_meta' => array( 'meta_date', 'meta_cat', 'meta_tags', 'meta_author', 'meta_comments', 'breadcrumb' ),
		'post_hide_disable_comments_note' => true,
		'post_disable_all_comment_form' => false,
		'post_related' => 'by_cat',
		'post_author_badge_color' => '#459dd8',
		'post_next_prev' => true,
		'sticky_bor' => true,
		'sticky_bg_col' => '#fef3e3',
		'sticky_bor_type' => 'solid',
		'sticky_bor_width' => 7,
		'sticky_bor_col' => '#dddddd',
		'sticky_ribbon' => true,
		'sticky_ribbon_col' => '#228ed6',
		'sticky_ribbon_txt' => 'Featured',
		'top_bar_active' => true,
		'top_bar_mobile' => true,
		'top_bar_bg' => '#228ed6',
		'top_bar_txt_col' => '#f7f7f7',		
		'top_bar_link_txt_col' => '#f7f7f7',
		'top_bar_email' => '',
		'top_bar_w_hours' => '',	
		'top_bar_sos_facebook' => '',
		'top_bar_sos_twitter' => '',			
		'top_bar_sos_googleplus' => '',
		'top_bar_sos_youtube' => '',	
		'top_bar_sos_instagram' => '',
		'top_bar_sos_pinterest' => '',
		'bottom_bar_active' => true,
		'bottom_bar_bg' => '#166cad',
		'bottom_bar_txt_col' => '#f7f7f7',		
		'bottom_bar_link_txt_col' => '#e0e0e0',
		'bottom_bar_copyright' => '',
		'effect_screen_preload' => false,
		'effect_screen_preload_bg' => '#17486E',
		'effect_stt' => true,
		'effect_stt_speed' => '1000',
		'button_readmore' => 'blue',
		'button_readmore_pos' => 'left',
		'button_nav' => 'green',
		'author_box' => true,
		'author_box_bg' => '#efefef',
		'author_box_txt_col' => '#333',
		'color_scheme' => 'default_scheme',
		'misc_custom_js' => '',
		'misc_txt_np' => 'Newer posts',
		'misc_txt_op' => 'Older posts',
		'misc_txt_next' => 'Next',
		'misc_txt_prev' => 'Previous',
		'misc_txt_pg' => 'Pages:',
		'misc_txt_rp' => 'You may also like...',
		'misc_txt_comment_note' => 'Your email will not be published. Name and Email fields are required',
		'misc_min_stylesheet' => true,
		'misc_jpis_per_page' => 7,
		'misc_jpis_order' => 'desc',
		'misc_jpis_load_more_txt' => 'Load More...',
		'misc_image_lazyload' => true,
		'misc_struct_data' => true,
		'misc_admin_topbar' => true,
		'misc_admin_about' => true,
	);
	
}