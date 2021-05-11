<?php

namespace KnitPay\Gateways\Razorpay;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Title: Razorpay Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   1.7.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Razorpay integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'razorpay',
				'name'          => 'Razorpay',
				'url'           => 'http://go.thearrangers.xyz/razorpay?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=',
				'product_url'   => 'http://go.thearrangers.xyz/razorpay?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'dashboard_url' => 'http://go.thearrangers.xyz/razorpay?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
				'provider'      => 'razorpay',
				'supports'      => array(
					'webhook',
					'webhook_log',
				),
				// 'manual_url'    => \__( 'http://go.thearrangers.xyz/razorpay', 'pronamic_ideal' ),
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
			'html'    => '<p>' . __( '<h1>Limited Period Offer.</h1>' ) . '</p>' .
			'<p>' . __( '0% Razorpay platform fee, for the first three months. Offer valid on the new account created before 15 May 2021.' ) . '</p>' .
			'<br /> <a class="button button-primary button-large" target="_new" href="' . $this->get_url() . 'special-offer"
		    role="button"><strong>Start Free Trial</strong></a>',
		);

		// Intro.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: 1: Razorpay */
				__( 'Account details are provided by %1$s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'Razorpay', 'pronamic_ideal' )
			),
		);

		// Client ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_razorpay_key_id',
			'title'    => __( 'Key ID', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Key ID as mentioned in the Razorpay dashboard at the "API Keys" tab to settings page.', 'pronamic_ideal' ),
		);

		// Client Secret
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_razorpay_key_secret',
			'title'    => __( 'Key Secret', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Key Secret as mentioned in the Razorpay dashboard at the "API Keys" tab to settings page.', 'pronamic_ideal' ),
		);

		// TODO: Payment Action to be added (authorize/capture)

		// Webhook URL.
		$fields[] = array(
			'section'  => 'feedback',
			'title'    => \__( 'Webhook URL', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'large-text', 'code' ),
			'value'    => add_query_arg( 'kp_razorpay_webhook', '', home_url( '/' ) ),
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.',
					'knit-pay'
				),
				__( 'Razorpay', 'knit-pay' )
			),
		);

		$fields[] = array(
			'section'  => 'feedback',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_razorpay_webhook_secret',
			'title'    => \__( 'Webhook Secret', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  =>
				__(
					'Create a new webhook secret. This can be a random string, and you don\'t have to remember it. Do not use your password or Key Secret here.',
					'knit-pay'
				),
		);

		$fields[] = array(
			'section' => 'feedback',
			'title'   => \__( 'Active Events', 'knit-pay' ),
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: 1: Razorpay */
				__( 'In Active Events section check payment authorized, failed and captured events.', 'knit-pay' ),
				__( 'Razorpay', 'knit-pay' )
			),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->key_id         = $this->get_meta( $post_id, 'razorpay_key_id' );
		$config->key_secret     = $this->get_meta( $post_id, 'razorpay_key_secret' );
		$config->webhook_secret = $this->get_meta( $post_id, 'razorpay_webhook_secret' );
		$config->mode           = $this->get_meta( $post_id, 'mode' );

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
