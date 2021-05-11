<?php

namespace KnitPay\Gateways\Instamojo;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Instamojo Integration
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   1.0.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct Instamojo integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'instamojo',
				'name'          => 'Instamojo',
				'url'           => 'http://go.thearrangers.xyz/instamojo?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=',
				'product_url'   => 'http://go.thearrangers.xyz/instamojo?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=product-url',
				'dashboard_url' => array(
					\__( 'Sign Up', 'knit-pay' ) => 'http://go.thearrangers.xyz/instamojo?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=signup',
					\__( 'Live', 'knit-pay' )    => 'http://go.thearrangers.xyz/instamojo?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
					\__( 'Test', 'knit-pay' )    => 'https://test.instamojo.com',
				),
				'provider'      => 'instamojo',
				'supports'      => array(
					'webhook',
					'webhook_log',
					'webhook_no_config',
				),
				// TODO:
				// 'manual_url'    => \__( 'http://go.thearrangers.xyz/instamojo', 'knit-pay' ),
			)
		);

		parent::__construct( $args );

		\add_filter( 'pronamic_pay_return_should_redirect', array( $this, 'return_should_redirect' ), 10, 2 );

		// TODO \add_filter( 'pronamic_gateway_configuration_display_value_' . $this->get_id(), array( $this, 'gateway_configuration_display_value' ), 10, 2 );
	}

	/**
	 * Setup gateway integration.
	 *
	 * @return void
	 */
	public function setup() {
		// TODO Check how can we use it.
	}

	/**
	 * Filter whether or not to redirect when handling return.
	 *
	 * @param bool    $should_redirect Whether or not to redirect.
	 * @param Payment $payment         Payment.
	 *
	 * @return bool
	 */
	public function return_should_redirect( $should_redirect, Payment $payment ) {
		// Don't Redirect if it's webhook call.
		if ( filter_has_var( INPUT_POST, 'mac' ) ) {
			$should_redirect = false;
		}

		return $should_redirect;
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
			'html'    => '<p>' . __(
				'Instamojo is a free Payment Gateway for 12,00,000+ Businesses in India. There is no setup or annual fee. Just pay a transaction fee of 2% + ₹3 for the transactions. Instamojo accepts Debit Cards, Credit Cards, Net Banking, UPI, Wallets, and EMI.',
				'knit-pay'
			) . '</p>' . '<p>' . __( '<strong>Steps to Integrate Instamojo</strong>' ) . '</p>' .

			'<ol>' . '<li>Some features may not work with the old Instamojo account! We
                    recommend you create a new account. Sign up process will hardly
                    take 10-15 minutes.<br />
                    <br /> <a class="button button-primary" target="_new" href="' . $this->get_url() . 'help-signup"
                     role="button"><strong>Sign Up on Instamojo</strong></a>
                    </li>
                    <br />
		    
                    <li>During signup, Instamojo will ask your PAN and Bank
                    account details, after filling these details, you will reach
                    Instamojo Dashboard.</li>
		    
                    <li>On the left-hand side menu, you will see the option "API &
						Plugins" click on this button.</li>
		    
                    <li>This plugin is based on Instamojo API v2.0, So it will not
                    work with API Key and Auth Token. For this plugin to work, you
                    will have to generate a Client ID and Client Secret. On the bottom
                    of the "API & Plugins" page, you will see Generate Credentials /
                    Create new Credentials button. Click on this button.</li>
		    
                    <li>Now choose a platform from the drop-down
                    menu. You can choose any of them, but I will recommend choosing
                    option "WooCommerce/WordPress"</li>
		    
                    <li>Copy "Client ID" & "Client Secret" and paste it in the
                    Knit Pay Configuration Page.</li>

                    <li>On the Knit Pay Configuration page, change the "Mode" to "Live" mode, if you are using the Live Credentials.</li>
		    
                    <!-- <li>Fill "Registered Email Address" field.</li> -->
		    
					<li>Save the settings using the "Publish" or "Update" button on the configuration page.</li>

                    <li>After saving the settings, test the settings using the Test block on the bottom of the configuration page. If you are getting an error while test the payment, kindly re-check Keys and Mode and save them again before retry.</li>

                    </ol>' .
					 'For more details about Instamojo service
                    and details about transactions, you need to access Instamojo dashboard. <br /> <a
					target="_new" href="' . $this->get_url() . 'know-more">Access
						Instamojo</a>',
		);

		// Client ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_instamojo_client_id',
			'title'    => __( 'Client ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Client ID as mentioned in the Instamojo dashboard at the "API & Plugins" page.', 'knit-pay' ),
		);

		// Client Secret
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_instamojo_client_secret',
			'title'    => __( 'Client Secret', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Client Secret as mentioned in the Instamojo dashboard at the "API & Plugins" page.', 'knit-pay' ),
		);

		// Registered Email Address.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_EMAIL,
			'meta_key' => '_pronamic_gateway_instamojo_email',
			'title'    => __( 'Registered Email Address', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'Email Address used for Instamojo Account.', 'knit-pay' ),
		);

		// Get Discounted Price.
		$fields[] = array(
			'section'     => 'general',
			'filter'      => FILTER_VALIDATE_BOOLEAN,
			'meta_key'    => '_pronamic_gateway_instamojo_get_discount',
			'title'       => __( 'Get Discounted Fees', 'knit-pay' ),
			'type'        => 'checkbox',
			'description' => 'Knit Pay will try to activate discounted transaction fees on your Instamojo account. Discounts are available on a case to case basis.<br>Discounted transaction fees will get activated before the 10th of next month on eligible accounts.',
			'tooltip'     => __( 'Tick to show your interested in discounted transaction fees.', 'knit-pay' ),
			'label'       => __( 'I am interested in discounted Instamojo transaction fees.', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->client_id     = $this->get_meta( $post_id, 'instamojo_client_id' );
		$config->client_secret = $this->get_meta( $post_id, 'instamojo_client_secret' );
		$config->email         = $this->get_meta( $post_id, 'instamojo_email' );
		$config->get_discount  = $this->get_meta( $post_id, 'instamojo_get_discount' );
		$config->mode          = $this->get_meta( $post_id, 'mode' );

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

		if ( Gateway::MODE_TEST === $config->mode && ! empty( $config->client_id ) && 0 !== strpos( $config->client_id, 'test' ) ) {
			update_post_meta( $post_id, '_pronamic_gateway_mode', 'live' );
		}

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
