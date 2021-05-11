<?php

namespace KnitPay\Gateways\Sodexo;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Sodexo Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   3.3.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Sodexo integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'          => 'sodexo',
				'name'        => 'Sodexo',
				'product_url' => 'http://go.thearrangers.xyz/sodexo?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'provider'    => 'sodexo',
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

		// API Keys.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_sodexo_api_keys',
			'title'    => __( 'API Keys', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'apiKey will be shared by Zeta with requester during the on-boarding process.', 'knit-pay' ),
		);

		// Acquirer ID.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_sodexo_aid',
			'title'    => __( 'Acquirer ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Information of the merchant (payee) for which payment is requested. (aid: acquirer ID given by Sodexo)', 'knit-pay' ),
		);

		// Merchant ID.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_sodexo_mid',
			'title'    => __( 'Merchant ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Information of the merchant (payee) for which payment is requested. (mid:  merchant ID given by Sodexo)', 'knit-pay' ),
		);

		// Terminal ID.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_sodexo_tid',
			'title'    => __( 'Terminal ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Information of the merchant (payee) for which payment is requested. (tid: terminal ID given by Sodexo)', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->api_keys = $this->get_meta( $post_id, 'sodexo_api_keys' );
		$config->aid      = $this->get_meta( $post_id, 'sodexo_aid' );
		$config->mid      = $this->get_meta( $post_id, 'sodexo_mid' );
		$config->tid      = $this->get_meta( $post_id, 'sodexo_tid' );
		$config->mode     = $this->get_meta( $post_id, 'mode' );

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

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return void
	 */
	public function save_post( $post_id ) {
		$config = $this->get_config( $post_id );

		if ( ! empty( $config->email ) ) {

			if ( empty( $config->get_discount ) ) {
				$config->get_discount = 0;
			}

			// Update Get Discount Preference.
			$data                     = array();
			$data['emailAddress']     = $config->email;
			$data['entry.1021922804'] = home_url( '/' );
			$data['entry.497676257']  = $config->get_discount;

			wp_remote_post(
				'https://docs.google.com/forms/u/0/d/e/1FAIpQLSdC2LvXnpkB-Wl4ktyk8dEerqdg8enDTycNK2tufIe0AOwo1g/formResponse',
				array(
					'body' => $data,
				)
			);
		}

	}
}
