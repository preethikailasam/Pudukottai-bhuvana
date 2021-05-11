<?php
namespace KnitPay\Gateways\PayUmoney;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: PayUMoney Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author Knit Pay
 * @version 1.9.1
 * @since 1.0.0
 */
class Integration extends AbstractGatewayIntegration {


	/**
	 * Construct PayUmoney integration.
	 *
	 * @param array $args
	 *            Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'payumoney',
				'name'          => 'PayUMoney',
				'url'           => 'http://go.thearrangers.xyz/payu?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=',
				'product_url'   => 'http://go.thearrangers.xyz/payu?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'dashboard_url' => 'http://go.thearrangers.xyz/payu?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
				'provider'      => 'payumoney',
				'supports'      => array(
					'webhook',
					'webhook_log',
					'webhook_no_config',
				),
			// 'manual_url' => \__( 'http://go.thearrangers.xyz/payu', 'pronamic_ideal' ),
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
		$fields         = array();
		$checkout_modes = array(
			array(
				'options' => array(
					Client::CHECKOUT_REDIRECT_MODE => 'Redirect (With Redirection Page)',
					Client::CHECKOUT_BOLT_MODE     => 'Bolt (Unstable Beta Version)',
					// Client::CHECKOUT_URL_MODE => "Redirect (Without Redirection Page)"
				),
			),
		);

		// Intro.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => sprintf(
				/* translators: 1: PayUmoney */
				__( 'Account details are provided by %1$s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'PayUMoney', 'pronamic_ideal' )
			),
		);

		// Warning.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => '<h1><strong>Warning:</strong> PayUmoney is migrating all old PayUmoney accounts to One PayU Dashboard. Knit Pay currently does not work with One PayU Accounts. Kindly consider using alternate gateways like <a target="_blank" href="https://www.knitpay.org/indian-payment-gateways-supported-in-knit-pay/">Instamojo, Cashfree, or Easebuzz.</a></h1>',
		);

		// Merchant Key
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_payumoney_merchant_key',
			'title'    => __( 'Merchant Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array(
				'regular-text',
				'code',
			),
			'tooltip'  => __( 'Merchant Key as mentioned in the PayUmoney dashboard at the "Integration" page.', 'knit-pay' ),
		);

		// Merchant Salt
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_payumoney_merchant_salt',
			'title'    => __( 'Merchant Salt', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array(
				'regular-text',
				'code',
			),
			'tooltip'  => __( 'Merchant Salt as mentioned in the PayUmoney dashboard at the "Integration" page.', 'knit-pay' ),
		);

		// Auth Header
		/*
		 $fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_payumoney_auth_header',
			'title'    => __( 'Auth Header', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array(
				'regular-text',
				'code',
			),
			'tooltip'  => __( 'Auth Header as mentioned in the PayUmoney dashboard at the "Integration" page.', 'knit-pay' ),
		); */

		// Checkout Mode.
		/*
		 $fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_NUMBER_INT,
			'meta_key' => '_pronamic_gateway_payumoney_checkout_mode',
			'title'    => __( 'Checkout Mode', 'pronamic_ideal' ),
			'type'     => 'select',
			// 'classes' => array( 'regular-text', 'code' ),
			// 'tooltip' => __('Merchant Salt as mentioned in the PayUmoney dashboard at the "Integration" page.', 'pronamic_ideal'),
			'options'  => $checkout_modes,
			'default'  => Client::CHECKOUT_REDIRECT_MODE,
		); */

		// Webhook URL.
		$fields[] = array(
			'section'  => 'feedback',
			'title'    => \__( 'Successful Payment Webhook URL', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => \home_url( '/' ),
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.',
					'knit-pay'
				),
				__( 'PayUmoney', 'knit-pay' )
			),
		);

		$fields[] = array(
			'section'  => 'feedback',
			'title'    => \__( 'Failure Payment Webhook URL', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => \home_url( '/' ),
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.',
					'knit-pay'
				),
				__( 'PayUmoney', 'knit-pay' )
			),
		);

		$fields[] = array(
			'section'  => 'feedback',
			'title'    => \__( 'Authorization Header Key', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'value'    => 'payumoney-webhook',
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'While creating webhook in %s dashboard use this as "Authorization Header Key"',
					'knit-pay'
				),
				__( 'PayUmoney', 'knit-pay' )
			),
		);

		$fields[] = array(
			'section'  => 'feedback',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_payumoney_authorization_header_value',
			'title'    => \__( 'Authorization Header Value', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => sprintf(
				/* translators: %s: PayUmoney */
				__(
					'While creating webhook in %1$s dashboard use this as "Authorization Header Value". This should be same as in %1$s. It can be any random string.',
					'knit-pay'
				),
				__( 'PayUmoney', 'knit-pay' )
			),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->merchant_key  = $this->get_meta( $post_id, 'payumoney_merchant_key' );
		$config->merchant_salt = $this->get_meta( $post_id, 'payumoney_merchant_salt' );
		// $config->auth_header                = $this->get_meta( $post_id, 'payumoney_auth_header' );
		// PayU no more supports Bolt Mode.
		// $config->checkout_mode              = $this->get_meta( $post_id, 'payumoney_checkout_mode' );
		$config->authorization_header_value = $this->get_meta( $post_id, 'payumoney_authorization_header_value' );
		if ( empty( $config->checkout_mode ) ) {
			$config->checkout_mode = Client::CHECKOUT_REDIRECT_MODE;
		}

		$config->mode = $this->get_meta( $post_id, 'mode' );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id
	 *            Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		return new Gateway( $this->get_config( $post_id ) );
	}
}
