<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! get_theme_mod( 'misc_admin_about', true ) ) return;

function zoom_about_config() {

	// About Page(
		/*
		* About page instance
		*/
		$config = array(
			// Menu name under Appearance.
			'menu_name'               => __( 'About Zoom Lite', 'zoom-lite' ),
			// Page title.
			'page_name'               => __( 'About Zoom Lite', 'zoom-lite' ),
			// Main welcome title
			'welcome_title'         => sprintf( __( 'Welcome to %s', 'zoom-lite' ), 'Zoom Lite' ),
			// Main welcome content
			'welcome_content'       => esc_html__( 'Zoom Lite is a free WordPress theme. It\'s perfect for blogs, personal, photography sites or freelancer. Zoom Lite is 100% responsive, clean, modern, flat and minimal. Zoom Lite is ecommerce (WooCommerce) Compatible, WPML, RTL and also SEO Friendly.', 'zoom-lite' ),
			/**
			 * Tabs array.
			 *
			 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
			 * the will be the name of the function which will be used to render the tab content.
			 */
			'tabs'                    => array(
				'getting_started'  => __( 'Getting Started', 'zoom-lite' ),
				'recommended_actions' => __( 'Recommended Actions', 'zoom-lite' ),
				'recommended_plugins' => __( 'Useful Plugins', 'zoom-lite' ),
				'support'       => __( 'Support', 'zoom-lite' ),
				'optimize_your_theme'       => __( 'Optimize Your Theme', 'zoom-lite' ),
				//'changelog'        => __( 'Changelog', 'zoom-lite' ),
			),
			
			// Optimize Theme
			'optimize_your_theme'        => array(
				'content' => array(
					  'title'       => esc_html__( 'Want to make better look of your website?', 'zoom-lite' ),
					  'description' => __( 'Want to have a beautiful website? Want to create a website like a professional? Want an easy way to build and customize your WordPress site?', 'zoom-lite' ),
					  'id' => 'wp-composer',
					  'external_link' => 'https://wpcomposer.com/',
				  )
			),
			
			// Support content tab.
			'support_content'      => array(
				'first' => array (
					'title' => esc_html__( 'Contact Support', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-sos',
					'text' => esc_html__( 'Finding yourself tied to the tracks with unresolved issues and unanswered questions? It sounds like you need a Heroic Support. Just contact us now! We want to make sure you have the best experience using Zoom Lite.', 'zoom-lite' ),
					'button_label' => esc_html__( 'Contact Support', 'zoom-lite' ),
					'button_link' => esc_url( 'https://ghozylab.com/plugins/submit-support-request/#tab-1399384216-2-4' ),
					'is_button' => true,
					'is_new_tab' => true
				),
				'second' => array(
					'title' => esc_html__( 'FAQ\'s', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-editor-help',
					'text' => esc_html__( 'This FAQ ( Frequently Asked Questions ) provides answers to basic questions about Zoom Theme, find answers to your most frequently asked questions about how to use this theme. Click button below for more information.', 'zoom-lite' ),
					'button_label' => esc_html__( 'Browse FAQ\'s', 'zoom-lite' ),
					'button_link' => esc_url( 'https://themes.ghozylab.com/faq/' ),
					'is_button' => true,
					'is_new_tab' => true
				),
				'third' => array(
					'title' => esc_html__( 'Development Log', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-portfolio',
					'text' => esc_html__( 'Want to get the gist on the latest theme changes? Just consult our changelog below to get a taste of the recent fixes and features implemented. We recommend you to use the latest version of the theme.', 'zoom-lite' ),
					'button_label' => esc_html__( 'View Changes', 'zoom-lite' ),
					'button_link' => esc_url( 'https://themes.trac.wordpress.org/log/zoom-lite/' ),
					'is_button' => true,
					'is_new_tab' => true
				),
			),
			
			// Getting started tab
			'getting_started' => array(
				'first' => array (
					'title' => esc_html__( 'Recommended Actions', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-thumbs-up',
					'text' => esc_html__( 'We have compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.', 'zoom-lite' ),
					'button_label' => esc_html__( 'Recommended Actions', 'zoom-lite' ),
					'button_link' => esc_url( admin_url( 'themes.php?page=zoom-lite-welcome&tab=recommended_actions' ) ),
					'is_button' => true,
					'recommended_actions' => true,
					'is_new_tab' => false
				),
				'second' => array (
					'title' => esc_html__( 'Rate this Theme', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-heart',
					'text' => esc_html__( 'We really appreciate if you kindly give some feedback for this theme, your review can makes us more excited to make this theme more better', 'zoom-lite' ),
					'button_label' => esc_html__( 'Add Your Review', 'zoom-lite' ),
					'button_link' => esc_url( 'https://wordpress.org/support/theme/zoom-lite/reviews/?filter=5' ),
					'is_button' => true,
					'recommended_actions' => false,
					'is_new_tab' => true
				),
				'third' => array(
					'title' => esc_html__( 'Customize Your Theme', 'zoom-lite' ),
					'icon' => 'dashicons dashicons-admin-settings',
					'text' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme. We also provide tons of options that you can use!', 'zoom-lite' ),
					'button_label' => esc_html__( 'Go to Customizer', 'zoom-lite' ),
					'button_link' => esc_url( admin_url( 'customize.php' ) ),
					'is_button' => true,
					'recommended_actions' => false,
					'is_new_tab' => false
				),
			),
			
			// Plugins array.
			'recommended_plugins'        => array(
				'already_activated_message' => esc_html__( 'Already activated', 'zoom-lite' ),
				'version_label' => esc_html__( 'Version: ', 'zoom-lite' ),
				'install_label' => esc_html__( 'Install', 'zoom-lite' ),
				'activate_label' => esc_html__( 'Activate', 'zoom-lite' ),
				'deactivate_label' => esc_html__( 'Deactivate', 'zoom-lite' ),
				'content'                   => array(
					array(
						'slug' => 'contact-form-lite',
						'file_name' => 'easy-contact-form'
					),
					array(
						'slug' => 'easy-media-gallery',
						'file_name' => 'easy-media-gallery'
					),
					array(
						'slug' => 'feed-instagram-lite',
						'file_name' => 'feed-instagram-lite'
					),
					array(
						'slug' => 'icon',
						'file_name' => 'icon'
					),
					array(
						'slug' => 'image-slider-widget',
						'file_name' => 'easy-slider-widget-lite'
					),
					array(
						'slug' => 'image-carousel',
						'file_name' => 'image-carousel'
					)
				),
			),
			
			// Required actions array.
			'recommended_actions'        => array(
				'install_label' => esc_html__( 'Install', 'zoom-lite' ),
				'activate_label' => esc_html__( 'Activate', 'zoom-lite' ),
				'deactivate_label' => esc_html__( 'Deactivate', 'zoom-lite' ),
				'update_label' => esc_html__( 'Udate Now', 'zoom-lite' ),
				'content'            => array(
					'wp-composer' => array(
						'title'       => 'WP Composer',
						'description' => __( 'The Best WordPress page builder plugin and the most powerful time saver for WordPress ever. Easily to build any layout design quickly – It works like magic with WP Composer!', 'zoom-lite' ),
						'check'       => defined( 'WPC_VERSION' ),
						'plugin_slug' => esc_html__( 'wp-composer', 'zoom-lite' ),
						'file_name' => 'wp-composer',
						'id' => 'wp-composer',
						'external_link' => 'https://wpcomposer.com/',
					),
					'easy-media-gallery' => array(
						'title'       => 'Gallery',
						'description' => __( 'Easy Media Gallery is a wordPress plugin designed to display various media support including grid gallery, galleries, photo album, multiple photo albums, portfolio, photo gallery, image slider or image gallery.', 'zoom-lite' ),
						'check'       => defined( 'EASYMEDIA_VERSION' ),
						'plugin_slug' => esc_html__( 'easy-media-gallery', 'zoom-lite' ),
						'file_name' => 'easy-media-gallery',
						'id' => 'easy-media-gallery'
					),
					'contact-form-lite' => array(
						'title'       => 'Contact Form',
						'description' => __( 'Makes your contact page more engaging by creating a good-looking contact form on your website. The interaction with your visitors was easier.', 'zoom-lite' ),
						'check'       => defined( 'ECF_VERSION' ),
						'plugin_slug' => esc_html__( 'contact-form-lite', 'zoom-lite' ),
						'file_name' => 'easy-contact-form',
						'id' => 'contact-form-lite'
					),
					'zoom-lite-theme' => array(
						'title'       => 'Theme Update Available',
						'description' => __( 'There is a new version of Zoom Lite. Please update to get the latest features and other important updates of this theme', 'zoom-lite' ),
						'check'       => '',
						'update_theme' => 'yes',
						'id' => 'zoom-lite-theme'
					),
	
				),
			),
	
			// Active Plugins Currently Supported
			'supported_plugins' => array(
				'wpc' => array (
					'title' => esc_html__( 'WP Composer', 'zoom-lite' ),
					'icon_class' => 'rec-wpc',
					'content_before_link' => esc_html__( 'Congratulations! Now this theme compatible with WP Composer plugin. You want to start making beautiful layout? Learn more here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'Learn more about WP Composer', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( 'https://docs.wpcomposer.com/getting-started/' ),
					'is_new_tab' => true
				),
				'vc' => array (
					'title' => esc_html__( 'Visual Composer', 'zoom-lite' ),
					'icon_class' => 'rec-vcom',
					'content_before_link' => esc_html__( 'Congratulations! Now this theme compatible with Visual Composer plugin. You want to start making onepage layout? Learn more here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'How to create Custom Front Page', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( 'https://themes.ghozylab.com/faq/?theme='.get_template().'&version='.wp_get_theme()->get( 'Version' ).'&plugin=visualcomposer#00' ),
					'is_new_tab' => true
				),
				'kcom' => array (
					'title' => esc_html__( 'Page Builder: KingComposer', 'zoom-lite' ),
					'icon_class' => 'rec-kcom',
					'content_before_link' => esc_html__( 'Congratulations! Now this theme compatible with KingComposer plugin. You want to start making onepage layout? Learn more here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'How to create Custom Front Page', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( 'https://themes.ghozylab.com/faq/?theme='.get_template().'&version='.wp_get_theme()->get( 'Version' ).'&plugin=kingcomposer#00' ),
					'is_new_tab' => true
				),
				'rvs' => array (
					'title' => esc_html__( 'Revolution Slider', 'zoom-lite' ),
					'icon_class' => 'rec-rslider',
					'content_before_link' => esc_html__( 'Congratulations! Now this theme compatible with Revolution Slider plugin. To get started just learn more here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'How to add Revolutin Slider to header', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( 'https://themes.ghozylab.com/faq/#04' ),
					'is_new_tab' => true
				),
				'jpk' => array (
					'title' => esc_html__( 'JetPack ( Infinite Scroll )', 'zoom-lite' ),
					'icon_class' => 'rec-jpack',
					'content_before_link' => esc_html__( 'It seems your JetPack Infinite Scroll module already active. You can try to check another options this theme provided from here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'Go to Infinite Scroll Settings Page', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( admin_url( 'customize.php?autofocus[control]=misc_jpis_per_page_control' ) ),
					'is_new_tab' => false
				),
				'sopb' => array (
					'title' => esc_html__( 'SiteOrigin Page Builder', 'zoom-lite' ),
					'icon_class' => 'rec-sopb',
					'content_before_link' => esc_html__( 'Congratulations! Now this theme compatible with SiteOrigin Page Builder plugin. To get started just learn more here : %s', 'zoom-lite' ),
					'link_text' => esc_html__( 'How to create Custom Front Page', 'zoom-lite' ),
					'use_link' => true,
					'the_link' => esc_url( 'https://themes.ghozylab.com/faq/?theme='.get_template().'&version='.wp_get_theme()->get( 'Version' ).'&plugin=sopb#00' ),
					'is_new_tab' => true
				),
			),
			
		);
		
	ZOOM_About_Page::init( $config );
	
}

add_action( 'after_setup_theme', 'zoom_about_config' );


/**
 * Get Theme Update Info
 */
 
function zoom_about_get_update_info() {
	
	$need_update = false;
	
	$themes_info = wp_prepare_themes_for_js( array( wp_get_theme() ) );
	
	foreach ( $themes_info as $theme ) :
	
		if ( $theme['hasUpdate'] ) :
			$need_update = true;
		endif;
		
	endforeach;

	return $need_update;
	
}