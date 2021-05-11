<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


// Move default Panel, Section & Control to ?
function zoom_theme_move_section( $wp_customize ){
	
	$wp_customize->get_section( 'title_tagline' )->panel = 'zoom_site_branding';
	$wp_customize->get_section( 'title_tagline' )->priority = 17;
	$wp_customize->get_section( 'title_tagline' )->title = esc_html__( 'Site Title, Tagline &amp; Site Icon', 'zoom-lite' );
	$wp_customize->get_section( 'static_front_page' )->panel = 'zoom_post_options';
	$wp_customize->get_section( 'menu_locations' )->title = esc_html__( 'Menu Location &amp; Position', 'zoom-lite' );
	
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	// Move default Custom Logo to Site Branding Panel
	$wp_customize->get_control( 'custom_logo' )->section = 'zoom_theme_logo_section';
	$wp_customize->get_control( 'custom_logo' )->priority = 1;
	$wp_customize->get_control( 'custom_logo' )->label = esc_html__( 'Main Logo (Header Logo)', 'zoom-lite' );
	$wp_customize->get_control( 'custom_logo' )->description = esc_html__( 'By default this logo will be displayed in the header. We recommend you to use image with minimal 200px of width.', 'zoom-lite' );
	
	// Move default Background Color Control to Zoom Styles Panel
	$wp_customize->get_control( 'background_color' )->section = 'site_styles_section';
	$wp_customize->get_control( 'background_color' )->label = '<span class="menu-group-title">'.esc_html__( 'GENERAL', 'zoom-lite' ).'</span><span class="menu-group-title-after">'.esc_html__( 'Site Background', 'zoom-lite' ).'</span>';
	$wp_customize->get_control( 'background_color' )->priority = 1;
	
	// Move default Background Image Section to Zoom Site Styles Panel
	$wp_customize->get_section( 'background_image' )->panel = 'zoom_site_styles';
	$wp_customize->get_section( 'background_image' )->priority = 5;
	
	// Move default Header Image Control to Zoom Header Section
	$wp_customize->get_control( 'header_image' )->section = 'zoom_theme_header_section';
	$wp_customize->get_control( 'header_image' )->title = esc_html__( 'Header Image', 'zoom-lite' );
	$wp_customize->get_section( 'header_image' )->priority = 2;
	
	// Move default Additional CSS to Zoom Site Styles Panel
	$wp_customize->get_section( 'custom_css' )->panel = 'zoom_misc_panel';
	$wp_customize->get_section( 'custom_css' )->priority = 3;
	
	wp_enqueue_style( 'zoom-theme-customizer', get_template_directory_uri() . '/inc/customizer/css/zoom-theme-customizer.css' );
	
}
add_action( 'customize_register', 'zoom_theme_move_section', 999, 1 ); 

// Controls enqueue scripts
function zoom_theme_customize_controls_enqueue_scripts() {
	
	wp_enqueue_script( 'zoom-theme-customizer-controls-extend', get_template_directory_uri() . '/inc/customizer/js/zoom-theme-extend-customizer.js', array( 'jquery', 'customize-controls', 'jquery-ui-core', 'jquery-effects-highlight' ) );
	
	$extend_data = array();
	$extend_data['content'] = sprintf( esc_html( '<h3>%1$s</h3><ul class="zoom-customizer-help-list"><li>%2$s <span class="edit-help-icon"></span> %3$s</li></li><li>%4$s <span class="'.( is_rtl() ? 'is_rtl ' : '' ).'collapse-sidebar-arrow-sc"></span> %5$s</li></ul>' ),
	esc_html__( 'Quick Notes!', 'zoom-lite' ),
	esc_html__( 'Move your cursor to any elements and click icon like this', 'zoom-lite' ),
	esc_html__( 'to customizing', 'zoom-lite' ),
	esc_html__( 'Click icon like this', 'zoom-lite' ),
	esc_html__( 'in the bottom left to see your site in actual wide', 'zoom-lite' )
	);
	$extend_data['is_rtl'] = is_rtl();
		
	wp_localize_script( 'zoom-theme-customizer-controls-extend', 'zoomExtendData', $extend_data );
	
	wp_enqueue_script( 'zoom-theme-customizer-scrollto', get_template_directory_uri() . '/inc/customizer/js/jquery-scrollto.js', array( 'jquery', 'customize-controls' ) );
	
	// Enqueue WP Pointer
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'zoom_customize_pointer', $dismissed ) ) {
		
        wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'zoom-customizer-script', get_template_directory_uri() . '/inc/customizer/js/zoom-customizer-script.js', array( 'jquery', 'customize-controls', 'wp-pointer' ) );
		
		$pointer_content = array();
		$pointer_content['title'] = esc_html__( 'Information', 'zoom-lite' );
		$pointer_content['msg_before'] = esc_html__( 'Click this icon', 'zoom-lite' );
		$pointer_content['msg_after'] = esc_html__( 'below to see your site in actual view', 'zoom-lite' );
		
		wp_localize_script( 'zoom-customizer-script', 'zoomPointer', $pointer_content );
	   
    }
	

}
add_action( 'customize_controls_enqueue_scripts', 'zoom_theme_customize_controls_enqueue_scripts' );


function zoom_customizer_live_preview() {
	
	// Allow real-time updating of the theme customizer
	wp_enqueue_script( 'zoom-theme-customizer', get_template_directory_uri() . '/inc/customizer/js/zoom-theme-customizer.js', array( 'jquery', 'customize-preview' ) );
	
	// Allow Communicating with the Previewer
	wp_enqueue_script( 'zoom-theme-customizer-communicator', get_template_directory_uri() . '/inc/customizer/js/zoom-theme-customizer-communicator.js', array( 'jquery', 'customize-preview', 'customize-preview-widgets' ) );
	
	wp_localize_script( 'zoom-theme-customizer-communicator', 'zoom_comm',
			array(
				'edit' => esc_html__( 'EDIT', 'zoom-lite' ),
				'meta_sett' => json_encode( get_theme_mod( 'post_meta' ) ),
				'multiplemenu' => json_encode( zoom_customize_multiple_menu_generator() ),
				'is_rtl' => is_rtl(),
			)
		);
	
}
add_action( 'customize_preview_init', 'zoom_customizer_live_preview' );


// This array used to generate the popup menu (submenu) on customizer edit button
function zoom_customize_multiple_menu_generator(){
	
	$menu = array( 'homebutton' => array(
						'one' => array(
							'title'  => esc_html__( 'Change Label', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'menu_home_btn_txt',
							'icon' => 'dashicons-edit',
						),
						'two' => array(
							'title'  => esc_html__( 'Hide Home Button', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'menu_home_btn',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
						),
					),
					'menu' => array(
						'one' => array(
							'title'  => esc_html__( 'Menu Settings', 'zoom-lite' ),
							'type'  => 'section',
							'target' => 'menu_locations',
							'icon' => 'dashicons-admin-settings',
							),
						'two' => array(
							'title'  => esc_html__( 'Menu Styles', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'main_menu_bg_control',
							'icon' => 'dashicons-admin-appearance',
							)
					),
					'readmore' => array(
						'one' => array(
							'is_submenu' => true,
							'title'  => esc_html__( 'Button Position', 'zoom-lite' ),
							'submenu_items' => array(
								'one' => array(
									'title'  => esc_html__( 'Left', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_pos_control',
									'value' => 'left',
								),
								'two' => array(
									'title'  => esc_html__( 'Center', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_pos_control',
									'value' => 'center',
								),
								'three' => array(
									'title'  => esc_html__( 'Right', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_pos_control',
									'value' => 'right',
								),
							),
						),
						'two' => array(
							'is_submenu' => true,
							'title'  => esc_html__( 'Button Color', 'zoom-lite' ),
							'submenu_items' => array(
								'one' => array(
									'title'  => esc_html__( 'Blue', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'blue',
								),
								'two' => array(
									'title'  => esc_html__( 'Green', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'green',
								),
								'three' => array(
									'title'  => esc_html__( 'Grey', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'grey',
								),
								'four' => array(
									'title'  => esc_html__( 'Orange', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'orange',
								),
								'five' => array(
									'title'  => esc_html__( 'Pink', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'pink',
								),
								'six' => array(
									'title'  => esc_html__( 'Purple', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'purple',
								),
								'seven' => array(
									'title'  => esc_html__( 'Red', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'red',
								),
								'eight' => array(
									'title'  => esc_html__( 'Turquoise', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'turq',
								),
								'nine' => array(
									'title'  => esc_html__( 'Default', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'button_readmore_control',
									'value' => 'defbtn',
								),
							),
						),
						'three' => array(
							'title'  => esc_html__( 'Other Options', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'button_readmore_control',
							'icon' => 'dashicons-admin-generic',
							)
					),
					'menusearch' => array(
						'one' => array(
							'title'  => esc_html__( 'Hide Search Box', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'menu_search',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							)
					),
					'featureimg' => array(
						'one' => array(
							'is_submenu' => true,
							'title'  => esc_html__( 'Image Size', 'zoom-lite' ),
							'submenu_items' => array(
								'one' => array(
									'title'  => esc_html__( 'Thumbnail', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_blog_thumb_size_control',
									'value' => 'thumbnail',
									),
								'two' => array(
									'title'  => esc_html__( 'Medium', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_blog_thumb_size_control',
									'value' => 'medium',
								),
								'three' => array(
									'title'  => esc_html__( 'Medium Large', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_blog_thumb_size_control',
									'value' => 'mlarge',
								),
								'four' => array(
									'title'  => esc_html__( 'Large', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_blog_thumb_size_control',
									'value' => 'large',
									),
								'five' => array(
									'title'  => esc_html__( 'Full', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_blog_thumb_size_control',
									'value' => 'full',
								)
							),
						),
						'two' => array(
							'title'  => esc_html__( 'Disable Featured Images', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'featured_image_on_post_list_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'featureimgthumb' => array(
						'one' => array(
							'title'  => esc_html__( 'Hide Image Placeholder', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'featured_image_placeholder_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
                     ),
					'featureimgsingle' => array(
						'one' => array(
							'is_submenu' => true,
							'title'  => esc_html__( 'Image Size', 'zoom-lite' ),
							'submenu_items' => array(
								'one' => array(
									'title'  => esc_html__( 'Thumbnail', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_single_thumb_size_control',
									'value' => 'thumbnail',
									),
								'two' => array(
									'title'  => esc_html__( 'Medium', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_single_thumb_size_control',
									'value' => 'medium',
								),
								'three' => array(
									'title'  => esc_html__( 'Medium Large', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_single_thumb_size_control',
									'value' => 'mlarge',
								),
								'four' => array(
									'title'  => esc_html__( 'Large', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_single_thumb_size_control',
									'value' => 'large',
									),
								'five' => array(
									'title'  => esc_html__( 'Full', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_single_thumb_size_control',
									'value' => 'full',
								)
							),
						),
						'two' => array(
							'title'  => esc_html__( 'Disable Featured Images', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'featured_image_on_single_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'relatedpost' => array(
						'one' => array(
							'is_submenu' => true,
							'title'  => esc_html__( 'Show Related Articles by', 'zoom-lite' ),
							'icon' => 'dashicons-editor-ul',
							'submenu_items' => array(
								'one' => array(
									'title'  => esc_html__( 'by Category', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_related_control',
									'value' => 'by_cat',
									),
								'two' => array(
									'title'  => esc_html__( 'by Tags', 'zoom-lite' ),
									'type'  => 'change',
									'target' => 'post_related_control',
									'value' => 'by_tags',
								)
							),
						),
						'two' => array(
							'title'  => esc_html__( 'Hide Related Posts', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_related_control',
							'value' => 'disable',
							'icon' => 'dashicons-hidden',
						)
					),
					'authorbox' => array(
						'one' => array(
							'title'  => esc_html__( 'Author Box Styles', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'author_box_bg_control',
							'icon' => 'dashicons-admin-appearance',
							),
						'two' => array(
							'title'  => esc_html__( 'Hide Author Box', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'author_box_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'bottombar' => array(
						'one' => array(
							'title'  => esc_html__( 'Change Text', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'bottom_bar_copyright_control',
							'icon' => 'dashicons-edit',
							),
						'two' => array(
							'title'  => esc_html__( 'Bottom Bar Styles', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'bottom_bar_bg_control',
							'icon' => 'dashicons-admin-appearance',
							),
						'three' => array(
							'title'  => esc_html__( 'Hide Bottom Bar', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'bottom_bar_active_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'topbar' => array(
						'one' => array(
							'title'  => esc_html__( 'Top Bar Styles', 'zoom-lite' ),
							'type'  => 'control',
							'target' => 'top_bar_bg_control',
							'icon' => 'dashicons-admin-appearance',
							),
						'two' => array(
							'title'  => esc_html__( 'Hide Top Bar', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'top_bar_active_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'nextpost' => array(
						'one' => array(
							'title'  => esc_html__( 'Hide Post Navigation', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_next_prev_control',
							'value' => 'false',
							'icon' => 'dashicons-hidden',
							),
					),
					'postmeta' => array(
						'meta_date' => array(
							'title'  => esc_html__( 'Hide Post Date', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'meta_date',
							'icon' => 'dashicons-hidden',
							),
						'meta_cat' => array(
							'title'  => esc_html__( 'Hide Categories', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'meta_cat',
							'icon' => 'dashicons-hidden',
							),
						'meta_tags' => array(
							'title'  => esc_html__( 'Hide Tags', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'meta_tags',
							'icon' => 'dashicons-hidden',
							),
						'meta_author' => array(
							'title'  => esc_html__( 'Hide Author', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'meta_author',
							'icon' => 'dashicons-hidden',
							),
						'meta_comments' => array(
							'title'  => esc_html__( 'Hide Comments', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'meta_comments',
							'icon' => 'dashicons-hidden',
							),
					),
					'breadcrumb' => array(
						'one' => array(
							'title'  => esc_html__( 'Hide Breadcrumb', 'zoom-lite' ),
							'type'  => 'change',
							'target' => 'post_meta_control',
							'value' => 'breadcrumb',
							'icon' => 'dashicons-hidden',
							),
					)

			);
			
			
	// Conditional for Post Meta items
	$the_meta = get_theme_mod( 'post_meta' );	
	$compare = array( 'meta_date', 'meta_cat', 'meta_tags', 'meta_author', 'meta_comments' );
	
	foreach ( $compare as $key => $val ) {

		if ( ! in_array( $val, $the_meta ) ) {
			
			unset ( $menu['postmeta'][$val]);

		}
	
	}

	return $menu;
	
}