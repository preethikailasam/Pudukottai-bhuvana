<?php
/**
 * Plugin Name: Knit Pay
 * Plugin URI: https://www.knitpay.org
 * Description: Top Indian payment gateways knitted together to integrate with major WordPress Plugins.
 *
 * Version: 4.2.0.2
 * Requires at least: 4.7
 * Requires PHP: 5.6
 *
 * WC requires at least: 2.3.0
 * WC tested up to: 4.5
 *
 * Author: KnitPay
 * Author URI: https://www.knitpay.org
 *
 * Text Domain: knit-pay
 * Domain Path: /languages/
 *
 * License: GPL-3.0-or-later
 *
 * @author    KnitPay
 * @license   GPL-3.0-or-later
 * @package   KnitPay
 * @copyright 2020-2021 Knit Pay
 */

/**
 * Autoload.
 */

if ( ! defined( 'KNIT_PAY_DEBUG' ) ) {
	define( 'KNIT_PAY_DEBUG', false );
}
if ( ! defined( 'PRONAMIC_PAY_DEBUG' ) ) {
	define( 'PRONAMIC_PAY_DEBUG', false );
}

define( 'KNITPAY_URL', plugins_url( '', __FILE__ ) );
define( 'KNITPAY_DIR', plugin_dir_path( __FILE__ ) );
define( 'KNITPAY_PATH', __FILE__ );

$autoload_before = __DIR__ . '/src/autoload-before.php';

if ( is_readable( $autoload_before ) ) {
	require $autoload_before;
}

$loader = require __DIR__ . '/vendor/autoload.php';

$autoload_after = __DIR__ . '/src/autoload-after.php';

if ( is_readable( $autoload_after ) ) {
	require $autoload_after;
}

require plugin_dir_path( __FILE__ ) . 'include.php';

/**
 * Bootstrap.
 */
\Pronamic\WordPress\Pay\Plugin::instance(
	array(
		'file'    => __FILE__,
		'options' => array(), /*
	array(
			'about_page_file' => __DIR__ . '/admin/page-about.php',
		)*/
	)
);

add_filter(
	'pronamic_pay_plugin_integrations',
	function( $integrations ) {

		// Charitable.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\Charitable\Extension();

		// Easy Digital Downloads.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension();

		// Give.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\Give\Extension();

		// LearnPress.
		$integrations[] = new \KnitPay\Extensions\LearnPress\Extension();

		// LifterLMS.
		$integrations[] = new \KnitPay\Extensions\LifterLMS\Extension();

		// NinjaForms.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\NinjaForms\Extension();

		// Paid Memberships Pro.
		$integrations[] = new \KnitPay\Extensions\PaidMembershipsPro\Extension();

		// Tourmaster.
		$integrations[] = new \KnitPay\Extensions\TourMaster\Extension();

		// WPTravelEngine.
		$integrations[] = new \KnitPay\Extensions\WPTravelEngine\Extension();

		// WooCommerce.
		$integrations[] = new \Pronamic\WordPress\Pay\Extensions\WooCommerce\Extension();

		// Return integrations.
		return $integrations;
	}
);

add_filter(
	'pronamic_pay_gateways',
	function( $gateways ) {
		// Cashfree
		$gateways[] = new \KnitPay\Gateways\Cashfree\Integration();

		// Instamojo
		$gateways[] = new \KnitPay\Gateways\Instamojo\Integration();

		// PayUMoney
		$gateways[] = new \KnitPay\Gateways\PayUmoney\Integration();

		// Easebuzz
		$gateways[] = new \KnitPay\Gateways\Easebuzz\Integration();

		// RazorPay
		$gateways[] = new \KnitPay\Gateways\Razorpay\Integration();

		// Stripe Connect.
		$gateways[] = new \KnitPay\Gateways\Stripe\Connect\Integration();

		// Test.
		$gateways[] = new \KnitPay\Gateways\Test\Integration();

		// UPI QR.
		$gateways[] = new \KnitPay\Gateways\UPIQR\Integration();

		// Return gateways.
		return $gateways;
	}
);

/**
 * Backward compatibility.
 */
global $pronamic_ideal;

$pronamic_ideal = pronamic_pay_plugin();


// Show Error If no configuration Found
function knitpay_admin_no_config_error() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( 0 === wp_count_posts( 'pronamic_gateway' )->publish ) {
		$class              = 'notice notice-error';
		$url                = admin_url() . 'post-new.php?post_type=pronamic_gateway';
		$link               = '<a href="' . $url . '">' . __( 'Knit Pay >> Configurations', 'knit-pay' ) . '</a>';
		$supported_gateways = '<br><a href="https://www.knitpay.org/indian-payment-gateways-supported-in-knit-pay/">' . __( 'Check the list of Supported Payment Gateways', 'knit-pay' ) . '</a>';
		$message            = sprintf( __( '<b>Knit Pay:</b> No Payment Gateway configuration was found. %1$s and visit %2$s to add the first configuration before start using Knit Pay.', 'knit-pay' ), $supported_gateways, $link );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}
}
add_action( 'admin_notices', 'knitpay_admin_no_config_error' );


// Add custom link on plugin page
function knitpay_filter_plugin_action_links( array $actions ) {
	return array_merge(
		array(
			'configurations' => '<a href="edit.php?post_type=pronamic_gateway">' . esc_html__( 'Configurations', 'knit-pay' ) . '</a>',
			'payments'       => '<a href="edit.php?post_type=pronamic_payment">' . esc_html__( 'Payments', 'knit-pay' ) . '</a>',
		),
		$actions
	);
}
$plugin = plugin_basename( __FILE__ );
add_filter( "network_admin_plugin_action_links_$plugin", 'knitpay_filter_plugin_action_links' );
add_filter( "plugin_action_links_$plugin", 'knitpay_filter_plugin_action_links' );


// Added to fix Razorpay double ? issue in callback URL
function knitpay_fix_get_url() {
	$current_url = home_url( $_SERVER['REQUEST_URI'] );
	if ( 1 < substr_count( $current_url, '?' ) ) {
		$current_url = str_replace_n( '?', '&', $current_url, 2 );
		wp_redirect( $current_url );
		exit;
	}
}
// https://vijayasankarn.wordpress.com/2017/01/03/string-replace-nth-occurrence-php/
function str_replace_n( $search, $replace, $subject, $occurrence ) {
	$search = preg_quote( $search );
	return preg_replace( "/^((?:(?:.*?$search){" . --$occurrence . "}.*?))$search/", "$1$replace", $subject );
}
add_action( 'init', 'knitpay_fix_get_url', 0 );
