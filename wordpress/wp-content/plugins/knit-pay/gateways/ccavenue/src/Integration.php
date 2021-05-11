<?php

namespace KnitPay\Gateways\CCAvenue;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: CCAvenue Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   2.3.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct CCAvenue integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'          => 'ccavenue',
				'name'        => 'CCAvenue',
				'product_url' => 'http://go.thearrangers.xyz/ccavenue?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'provider'    => 'ccavenue',
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Get settings fields.
	 *
	 * @return array
	 */
	public function get_settings_fields() {
		$fields = array();

		// Merchant ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ccavenue_merchant_id',
			'title'    => __( 'Merchant ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'This is the identifier for your CCAvenue merchant Account.', 'knit-pay' ),
		);

		// Access Code
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ccavenue_access_code',
			'title'    => __( 'Access Code', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'This is the access code for your application.', 'knit-pay' ),
		);

		// Working Key
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ccavenue_working_key',
			'title'    => __( 'Working Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Ensure you are using the correct key while encrypting requests from different URLs registered with CCAvenue.', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_id = $this->get_meta( $post_id, 'ccavenue_merchant_id' );
		$config->access_code = $this->get_meta( $post_id, 'ccavenue_access_code' );
		$config->working_key = $this->get_meta( $post_id, 'ccavenue_working_key' );
		$config->mode        = $this->get_meta( $post_id, 'mode' );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $config_id ) {
		return new Gateway( $this->get_config( $config_id ) );
	}
}
