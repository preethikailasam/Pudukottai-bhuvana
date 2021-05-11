<?php
/**
 * Payment Gateways per Products for WooCommerce - Core Class
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  WPWhale
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_PGPP_Core' ) ) :

class Alg_WC_PGPP_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_pgpp_enabled', 'yes' ) ) {
			if ( 'constructor' === get_option( 'alg_wc_pgpp_advanced_add_hook', 'init' ) ) {
				$this->add_hook();
			} else { // 'init'
				add_action( 'init', array( $this, 'add_hook' ) );
			}
		}
	}

	/**
	 * add_hook.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_hook() {
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'filter_available_payment_gateways_per_category' ), PHP_INT_MAX );
	}

	/**
	 * do_disable_gateway_by_terms.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function do_disable_gateway_by_terms( $terms, $taxonomy, $is_include ) {
		if ( empty( $terms ) || 'no' === get_option( 'alg_wc_pgpp_' . $taxonomy . '_section_enabled', 'yes' ) ) {
			return false;
		}
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item_values ) {
			$product_terms = get_the_terms( $cart_item_values['product_id'], $taxonomy );
			if ( $product_terms && ! is_wp_error( $product_terms ) ) {
				foreach( $product_terms as $product_term ) {
					if ( in_array( $product_term->term_id, $terms ) ) {
						return ( ! $is_include );
					}
				}
			}
		}
		return $is_include;
	}

	/**
	 * get_cart_product_id.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_cart_product_id( $cart_item_values ) {
		return ( 'yes' === get_option( 'alg_wc_pgpp_products_add_variations', 'no' ) ?
			( ! empty( $cart_item_values['variation_id'] ) ? $cart_item_values['variation_id'] : $cart_item_values['product_id'] ) :
			$cart_item_values['product_id']
		);
	}

	/**
	 * do_disable_gateway_by_products.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function do_disable_gateway_by_products( $products, $is_include ) {
		if ( empty( $products ) || 'no' === apply_filters( 'alg_wc_pgpp', 'no', 'products_section' ) ) {
			return false;
		}
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item_values ) {
			if ( in_array( $this->get_cart_product_id( $cart_item_values ), $products ) ) {
				return ( ! $is_include );
			}
		}
		return $is_include;
	}

	/**
	 * filter_available_payment_gateways_per_category.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) `if ( ! isset( WC()->cart ) || '' === WC()->cart ) { WC()->cart = new WC_Cart(); }`
	 */
	function filter_available_payment_gateways_per_category( $available_gateways ) {
		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) || WC()->cart->is_empty() || empty( $available_gateways ) ) {
			return $available_gateways;
		}
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			if (
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_categories_include_' . $gateway_id, '' ), 'product_cat', true ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_categories_exclude_' . $gateway_id, '' ), 'product_cat', false ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_tags_include_'       . $gateway_id, '' ), 'product_tag', true ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_tags_exclude_'       . $gateway_id, '' ), 'product_tag', false ) ||
				$this->do_disable_gateway_by_products( apply_filters( 'alg_wc_pgpp', '', 'products_include', array( 'gateway_id' => $gateway_id ) ), true ) ||
				$this->do_disable_gateway_by_products( apply_filters( 'alg_wc_pgpp', '', 'products_exclude', array( 'gateway_id' => $gateway_id ) ), false )
			) {
				unset( $available_gateways[ $gateway_id ] );
			}
		}
		return $available_gateways;
	}

}

endif;

return new Alg_WC_PGPP_Core();