<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since 1.0.0.37
 * @access public
 */
final class Zoom_Lite_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since 1.0.0.37
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since 1.0.0.37
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'zoom_lite_info_sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'zoom_lite_enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function zoom_lite_info_sections( $manager ) {

		// Load custom sections.
		require_once( trailingslashit( get_template_directory() ) . 'inc/customizer/info/info-cnt.php' );

		// Register custom section types.
		$manager->register_section_type( 'Zoom_Lite_Customize_Custom_Section' );

		$is_wpc = zoom_check_if_plugin_installed( 'wp-composer', 'wp-composer' );
		
		if ( $is_wpc ) {
			// Register sections.
			$manager->add_section(
				new Zoom_Lite_Customize_Custom_Section(
					$manager,
					'zoom_lite_info',
					array(
						'title'    => esc_html__( 'Love this Theme?', 'zoom-lite' ),
						'pro_text' => esc_html__( 'Write a Review', 'zoom-lite' ),
						'pro_url'  => esc_url( 'https://wordpress.org/support/theme/zoom-lite/reviews/?filter=5#new-post' ),
						'pro_icon' => 'dashicons-heart',
						'pro_type' => 'review'
					)
				)
			);	
			
		}
		else {
			// Register sections.
			$manager->add_section(
				new Zoom_Lite_Customize_Custom_Section(
					$manager,
					'zoom_lite_info',
					array(
						'title'    => esc_html__( 'ENABLE PAGE BUILDER ?', 'zoom-lite' ),
						'pro_text' => esc_html__( 'Install', 'zoom-lite' ),
						'pro_url'  => esc_url_raw( 'https://wpcomposer.com/?utm_source=customize&utm_medium=menu&utm_campaign=zoom-lite' ),
						'pro_icon' => 'dashicons-admin-customizer',
						'pro_type' => 'page_builder'
					)
				)
			);
		
		}
		
		$update_theme = zoom_get_update_info();
		
		if ( $update_theme ) {
			
			$act_theme = get_template();
			$nonce = wp_create_nonce( 'upgrade-theme_' . $act_theme );
			$update_url = add_query_arg( array( 'action' => 'upgrade-theme', 'theme' => $act_theme, '_wpnonce' => $nonce ), admin_url( 'update.php' ) );
			
			$manager->add_section(
				new Zoom_Lite_Customize_Custom_Section(
					$manager,
					'zoom_lite_update_info',
					array(
						'title'    => esc_html__( 'New version available', 'zoom-lite' ),
						'pro_text' => esc_html__( 'Update Now', 'zoom-lite' ),
						'pro_url'  => esc_url_raw( $update_url ),
						'pro_type' => 'update' 
					)
				)
			);
		
		}
		
	}
	

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @return void
	 */
	public function zoom_lite_enqueue_control_scripts() {

		wp_enqueue_script( 'zoom-lite-info-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/info/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'zoom-lite-info-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/info/customize-controls.css' );
	}
}

// Doing this customizer!
Zoom_Lite_Customize::get_instance();
