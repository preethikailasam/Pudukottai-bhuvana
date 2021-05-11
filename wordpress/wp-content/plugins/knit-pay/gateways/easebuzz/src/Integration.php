<?php

namespace KnitPay\Gateways\Easebuzz;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Title: Easebuzz Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   1.2.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Easebuzz integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'easebuzz',
				'name'          => 'Easebuzz',
				'url'           => 'http://go.thearrangers.xyz/easebuzz?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=',
				'product_url'   => 'http://go.thearrangers.xyz/easebuzz?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'dashboard_url' => 'http://go.thearrangers.xyz/easebuzz?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
				'provider'      => 'easebuzz',
				'supports'      => array(
					'webhook',
					'webhook_log',
					'webhook_no_config',
				),
				// 'manual_url'    => \__( 'http://go.thearrangers.xyz/easebuzz', 'knit-pay' ),
			)
		);

		parent::__construct( $args );

		// Actions
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

		// Intro.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: 1: Easebuzz */
				__( 'Account details are provided by %1$s after registration. These settings need to match with settings provided to you by %1$s over the email.', 'knit-pay' ),
				__( 'Easebuzz', 'knit-pay' )
			),
		);

		// Client ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_easebuzz_merchant_key',
			'title'    => __( 'Merchant Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Merchant Key as mentioned in the Easebuzz test/live kit sent over email.', 'knit-pay' ),
		);

		// Client Secret
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_easebuzz_merchant_salt',
			'title'    => __( 'Merchant Salt', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Merchant Salt as mentioned in the Easebuzz test/live kit sent over email.', 'knit-pay' ),
		);

		// Sub Merchant ID.
		$fields[] = array(
			'section'  => 'advanced',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_easebuzz_sub_merchant_id',
			'title'    => __( 'Sub Merchant ID (Not Required)', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Mandatory parameter if you are using sub-aggregator feature otherwise not mandatory. Here pass sub-aggregator id. You can create sub aggregator from Easebuzz dashboard web portal.', 'knit-pay' ),
		);

		$fields[] = array(
			'section'  => 'feedback',
			'title'    => \__( 'Transaction Webhook Call URL', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'large-text', 'code' ),
			'value'    => add_query_arg( 'easebuzz_webhook', '', home_url( '/' ) ),
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.',
					'knit-pay'
				),
				__( 'Easebuzz', 'knit-pay' )
			),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_key    = $this->get_meta( $post_id, 'easebuzz_merchant_key' );
		$config->merchant_salt   = $this->get_meta( $post_id, 'easebuzz_merchant_salt' );
		$config->sub_merchant_id = $this->get_meta( $post_id, 'easebuzz_sub_merchant_id' );
		$config->mode            = $this->get_meta( $post_id, 'mode' );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		return new Gateway( $this->get_config( $post_id ) );
	}
}
