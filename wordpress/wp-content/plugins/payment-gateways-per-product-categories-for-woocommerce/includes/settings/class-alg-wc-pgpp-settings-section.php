<?php
/**
 * Payment Gateways per Products for WooCommerce - Section Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  WPWhale
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_PGPP_Settings_Section' ) ) :

class Alg_WC_PGPP_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_wc_pgpp',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_pgpp_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_products.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @todo    [dev] use `'post_type' => 'product_variation'` (instead of `$_product->get_children()`) (need to unset main variable product then)
	 */
	function get_products( $products = array(), $post_status = 'any', $block_size = 512, $add_variations = false ) {
		$offset = 0;
		while( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => $post_status,
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products[ $post_id ] = get_the_title( $post_id ) . ' (#' . $post_id . ')';
				if ( $add_variations ) {
					$_product = wc_get_product( $post_id );
					if ( $_product->is_type( 'variable' ) ) {
						unset( $products[ $post_id ] );
						foreach ( $_product->get_children() as $child_id ) {
							$products[ $child_id ] = get_the_title( $child_id ) . ' (#' . $child_id . ')';
						}
					}
				}
			}
			$offset += $block_size;
		}
		return $products;
	}

	/**
	 * get_terms.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_terms( $args ) {
		if ( ! is_array( $args ) ) {
			$_taxonomy = $args;
			$args = array(
				'taxonomy'   => $_taxonomy,
				'orderby'    => 'name',
				'hide_empty' => false,
			);
		}
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			$_taxonomy = $args['taxonomy'];
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args );
		}
		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ){
			foreach ( $_terms as $_term ) {
				$_terms_options[ $_term->term_id ] = $_term->name;
			}
		}
		return $_terms_options;
	}

	/**
	 * get_gateways_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @todo    [dev] add "Chosen select / Standard multiselect" option
	 * @todo    [dev] add "Select all" button
	 * @todo    [dev] add "Set as IDs" option (i.e. enter categories / tags / products by ID (i.e. as comma separated text))
	 * @todo    [dev] maybe add (i.e. duplicate) settings to "WooCommerce > Settings > Payments > Direct bank transfer" etc.
	 */
	function get_gateways_settings( $args ) {
		$available_gateways = WC()->payment_gateways->payment_gateways();
		$gateways_settings  = array();
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			$gateways_settings = array_merge( $gateways_settings, array(
				array(
					'title'    => $gateway->title,
					'type'     => 'title',
					'id'       => 'alg_wc_pgpp_' . $args['options_id'] . '_gateway_' . $gateway_id . '_options',
				),
				array(
					'title'    => __( 'Include', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip' => $args['desc_tips']['include'] . ' ' . __( 'Ignored if empty.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'       => 'alg_wc_pgpp_' . $args['options_id'] . '_include_' . $gateway_id,
					'default'  => '',
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $args['options'],
				),
				array(
					'title'    => __( 'Exclude', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip' => $args['desc_tips']['exclude'] . ' ' . __( 'Ignored if empty.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'       => 'alg_wc_pgpp_' . $args['options_id'] . '_exclude_' . $gateway_id,
					'default'  => '',
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $args['options'],
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_pgpp_' . $args['options_id'] . '_gateway_' . $gateway_id . '_options',
				),
			) );
		}
		return $gateways_settings;
	}

}

endif;
