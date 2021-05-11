<?php

namespace KnitPay\Gateways\EBS;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: EBS Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   3.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct EBS integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'          => 'ebs',
				'name'        => 'EBS',
				'product_url' => 'http://go.thearrangers.xyz/ebs?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'provider'    => 'ebs',
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

		// Account ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ebs_account_id',
			'title'    => __( 'Account ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'This is the Account ID for your EBS merchant Account.', 'knit-pay' ),
		);

		// Secret Key
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ebs_secret_key',
			'title'    => __( 'Secret Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'This is the Secret Key for your EBS merchant Account.', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->account_id = $this->get_meta( $post_id, 'ebs_account_id' );
		$config->secret_key = $this->get_meta( $post_id, 'ebs_secret_key' );
		$config->mode       = $this->get_meta( $post_id, 'mode' );

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
