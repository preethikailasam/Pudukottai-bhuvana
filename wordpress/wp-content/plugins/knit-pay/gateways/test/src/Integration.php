<?php

namespace KnitPay\Gateways\Test;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Title: Test Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   2.5.4
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Test integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'          => 'test',
				'name'        => 'Test',
				'product_url' => 'https://www.knitpay.org/indian-payment-gateways-supported-in-knit-pay/',
				'provider'    => 'test',
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

		$checkout_modes = array(
			array(
				'options' => array(
					0 => 'Express Mode',
					1 => 'Normal Mode',
				),
			),
		);

		// Checkout Mode.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_NUMBER_INT,
			'meta_key' => '_pronamic_gateway_test_checkout_mode',
			'title'    => __( 'Checkout Mode', 'knit-pay' ),
			'type'     => 'select',
			'options'  => $checkout_modes,
			'default'  => 0,
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->checkout_mode = $this->get_meta( $post_id, 'test_checkout_mode' );

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
