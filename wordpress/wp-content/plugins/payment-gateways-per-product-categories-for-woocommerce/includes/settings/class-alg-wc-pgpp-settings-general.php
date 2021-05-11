<?php
/**
 * Payment Gateways per Products for WooCommerce - General Section Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  WPWhale
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_PGPP_Settings_General' ) ) :

class Alg_WC_PGPP_Settings_General extends Alg_WC_PGPP_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'payment-gateways-per-product-categories-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @todo    [dev] better description for "Add filter" option
	 */
	function get_settings() {
		$settings_links    = array();
		$settings_sections = array(
			'cats'       => __( 'Per Product Categories', 'payment-gateways-per-product-categories-for-woocommerce' ),
			'tags'       => __( 'Per Product Tags', 'payment-gateways-per-product-categories-for-woocommerce' ),
			'products'   => __( 'Per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
		);
		foreach ( $settings_sections as $settings_section_id => $settings_section_title ) {
			$settings_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_pgpp&section=' . $settings_section_id ) . '">' . $settings_section_title . '</a>';
		}
		return array(
			array(
				'title'    => __( 'Payment Gateways per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_pgpp_options',
			),
			array(
				'title'    => __( 'Enable/Disable', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip' => sprintf( __( '<strong>Sections:</strong> %s.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					implode( '<strong style="color: red;"> | </strong>', $settings_links ) ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'payment-gateways-per-product-categories-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_pgpp_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_pgpp_options',
			),
			array(
				'title'    => __( 'Advanced Options', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_pgpp_advanced_options',
			),
			array(
				'title'    => __( 'Add filter', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip' => __( 'Change this if you are having issues with plugin not working correctly.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'       => 'alg_wc_pgpp_advanced_add_hook',
				'default'  => 'init',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'constructor' => __( 'In constructor', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'init'        => __( 'On "init" action', 'payment-gateways-per-product-categories-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_pgpp_advanced_options',
			),
		);
	}

}

endif;

return new Alg_WC_PGPP_Settings_General();
