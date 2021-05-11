<?php

namespace KnitPay\Gateways\UPIQR;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: UPI QR Integration
 * Copyright: 2020 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   4.1.0
 */
class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct UPI QR integration.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'            => 'upi-qr',
				'name'          => 'UPI QR (Beta)',
				'url'           => 'http://go.thearrangers.xyz/',
				'dashboard_url' => array(
					\__( 'Google Pay', 'knit-pay' ) => 'http://go.thearrangers.xyz/gpay?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
					\__( 'PhonePe', 'knit-pay' )    => 'http://go.thearrangers.xyz/phonepe?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
					\__( 'Amazon Pay', 'knit-pay' ) => 'http://go.thearrangers.xyz/amazon-pay?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
					\__( 'Open Money', 'knit-pay' ) => 'http://go.thearrangers.xyz/open-money?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
					\__( 'BharatPe', 'knit-pay' )   => 'http://go.thearrangers.xyz/bharatpe?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=dashboard-url',
				),
				'provider'      => 'upi-qr',
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

		$utm_parameter = '?utm_source=knit-pay&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=help-signup';

		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => '<strong>Note:</strong> This payment method is currently in the Beta stage, which means you might face issues while using it. ' .

			'In case you find any issue or you have any feedback to improve it, feel free to <a target="_new" href="https://www.knitpay.org/contact-us/">contact us</a>.',
		);

		// Steps to Integrate.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => '<p>' . __( '<strong>Steps to Integrate UPI QR</strong>' ) . '</p>' .

			'<ol>
                <li>Signup at any UPI-enabled App. If you will signup using provided signup URLs and use the referral codes, you might also get a bonus after making few payments.
                    <ul>
                        <li>- <a target="_new" href="' . $this->get_url() . 'gpay' . $utm_parameter . '">Google Pay</a> Referral Code: Z05o0</li>
                        <li>- <a target="_new" href="' . $this->get_url() . 'phonepe' . $utm_parameter . '">PhonePe</a></li>
                        <li>- <a target="_new" href="' . $this->get_url() . 'amazon-pay' . $utm_parameter . '">Amazon Pay</a> Referral Code: KT6NTI</li>
                        <li>- <a target="_new" href="' . $this->get_url() . 'open-money' . $utm_parameter . '">Open Money</a></li>
                        <li>- <a target="_new" href="' . $this->get_url() . 'bharatpe' . $utm_parameter . '">BharatPe (' . $this->get_url() . 'bharatpe)</a> - Open referral link on phone to get upto 1000 free BharatPe Runs.</li>
                        <li>- <a target="_new" href="https://play.google.com/store/search?q=upi&c=apps">More UPI Apps</a></li>
                    </ul>
                </li>
		    
                <li>Link your Bank Account and generate a UPI ID/VPA.</li>
		    
                <li>Use this VPA/UPI ID on the configuration page below.
                <br><strong>Kindly use the correct VPA/UPI ID. In case of wrong settings, payments will get credited to the wrong bank account. Knit Pay will not be responsible for any of your lose.</strong></li>
		    
                <li>Save the settings.</li>
		    
                <li>Before going live, make a test payment of ₹1 and check that you are receiving this payment in the correct bank account.</li>
		    
            </ol>',
		);

		// How does it work.
		$fields[] = array(
			'section' => 'general',
			'type'    => 'html',
			'html'    => '<p>' . __( '<strong>How does it work?</strong>' ) . '</p>' .

			'<ol>
                <li>On the payment screen, the customer scans the QR code using any UPI-enabled mobile app and makes the payment.</li>
		    
                <li>The customer enters the transaction ID and submits the payment form.</li>
		    
                <li>Payment remains on hold. Merchant manually checks the payment and mark it as complete on the "Knit Pay" Payments page.</li>
		    
                <li>Automatic tracking is not available in the UPI QR payment method. You can signup at other supported free payment gateways to get an automatic payment tracking feature.
                    <br><a target="_new" href="https://www.knitpay.org/indian-payment-gateways-supported-in-knit-pay/">Indian Payment Gateways Supported in Knit Pay</a>
                </li>
		    
            </ol>',
		);

		// UPI VPA ID
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_upi_qr_vpa',
			'title'    => __( 'UPI VPA ID', 'knit-pay' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'tooltip'  => __( 'UPI/VPA ID which you want to use to receive the payment.', 'knit-pay' ),
		);

		// Return fields.
		return $fields;
	}

	public function get_config( $post_id ) {
		$config = new Config();

		$config->vpa  = $this->get_meta( $post_id, 'upi_qr_vpa' );
		$config->mode = $this->get_meta( $post_id, 'mode' );

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
