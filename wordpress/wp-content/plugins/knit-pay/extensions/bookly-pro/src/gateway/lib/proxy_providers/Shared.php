<?php
namespace BooklyKnitPay\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyKnitPay\Lib;
use BooklyKnitPay\Frontend\Modules\KnitPay;
use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Class Shared
 *
 * @package BooklyKnitPay\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared {

	/**
	 * @inheritdoc
	 */
	public static function handleRequestAction( $action ) {
		$active_payment_methods   = PaymentMethods::get_active_payment_methods();
		$active_payment_methods[] = 'knit_pay';
		foreach ( $active_payment_methods as $payment_method ) {
			if ( ! get_option( 'bookly_' . $payment_method . '_enabled' ) ) {
				continue;
			}

			if ( $action === $payment_method . '-checkout' ) {
				KnitPay\Controller::checkout( $payment_method );
				break;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway ) {
		$active_payment_methods   = PaymentMethods::get_active_payment_methods();
		$active_payment_methods[] = 'knit_pay';

		if ( ! in_array( $gateway, $active_payment_methods ) ) {
			return $cart_info;
		}

		if ( get_option( 'bookly_' . $gateway . '_enabled' ) ) {
			$cart_info->setGateway( $gateway );
		}

		return $cart_info;
	}
}
