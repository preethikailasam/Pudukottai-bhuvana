<?php

namespace KnitPay\Gateways\Cashfree;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Cashfree Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   2.4
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Cashfree integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'cashfree',
				'name'          => 'Cashfree',
				'url'           => 'http://go.thearrangers.xyz/cashfree?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=',
				'product_url'   => 'http://go.thearrangers.xyz/cashfree?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'dashboard_url' => 'http://go.thearrangers.xyz/cashfree?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
				'provider'      => 'cashfree',
				'supports'      => array(
					'webhook',
					'webhook_log',
					'webhook_no_config',
				),
			)
		);

		parent::__construct( $args );

		// Webhook Listener.
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	/**
	 * Get settings fields.
	 *
	 * @return array
	 */
	public function get_settings_fields() {
		$fields = array();

		if ( ! defined( 'KNIT_PAY_CASHFREE' ) ) {
			$fields[] = array(
				'section' => 'general',
				'type'    => 'html',
				'html'    => sprintf(
					/* translators: 1: Cashfree */
					__( 'Knit Pay supports %1$s with a Premium Addon. But you can get this premium addon for free and also you can get a special discount on transaction fees.%2$s', 'pronamic_ideal' ),
					__( 'Cashfree', 'pronamic_ideal' ),
					'<br><br><a class="button button-primary" target="_new" href="' . $this->get_url() . 'know-more"
                     role="button"><strong>Click Here to Know More</strong></a>'
				),
			);
			return $fields;
		}

		// Client ID.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_cashfree_api_id',
			'title'    => __( 'API ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'API ID as mentioned in the Cashfree dashboard at the "Credentials" page.', 'knit-pay' ),
		);

		// Client Secret.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_cashfree_secret_key',
			'title'    => __( 'Secret Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Secret Key as mentioned in the Cashfree dashboard at the "Credentials" page.', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->api_id     = $this->get_meta( $post_id, 'cashfree_api_id' );
		$config->secret_key = $this->get_meta( $post_id, 'cashfree_secret_key' );
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
