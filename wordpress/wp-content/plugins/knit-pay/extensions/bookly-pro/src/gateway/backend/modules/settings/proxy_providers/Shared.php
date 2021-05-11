<?php
namespace BooklyKnitPay\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Modules\Settings\Proxy;
use BooklyKnitPay\Lib;
use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Class Shared
 *
 * @package BooklyKnitPay\Backend\Modules\Settings\ProxyProviders
 */
class Shared extends Proxy\Shared {

	/**
	 * @inheritdoc
	 */
	public static function preparePaymentGatewaySettings( $payment_data ) {
		$payment_data['knit_pay'] = self::renderTemplate(
			'payment_settings',
			array(
				'gateway_name'   => 'Knit Pay',
				'payment_method' => 'knit_pay',
			),
			false
		);

		$active_payment_methods = PaymentMethods::get_active_payment_methods();
		foreach ( $active_payment_methods as $payment_method ) {
			$payment_data[ $payment_method ] = self::renderTemplate(
				'payment_settings',
				array(
					'gateway_name'   => PaymentMethods::get_name( $payment_method ),
					'payment_method' => $payment_method,
				),
				false
			);
		}

		return $payment_data;
	}

	/**
	 * @inheritdoc
	 */
	public static function saveSettings( array $alert, $tab, array $params ) {
		if ( $tab == 'payments' ) {
			$options = array();

			$active_payment_methods   = PaymentMethods::get_active_payment_methods();
			$active_payment_methods[] = 'knit_pay';
			foreach ( $active_payment_methods as $payment_method ) {
				$options[] = 'bookly_' . $payment_method . '_enabled';
				$options[] = 'bookly_' . $payment_method . '_config_id';
				$options[] = 'bookly_' . $payment_method . '_payment_description';
				$options[] = 'bookly_' . $payment_method . '_icon_url';
				$options[] = 'bookly_l10n_label_pay_' . $payment_method;
			}

			foreach ( $options as $option_name ) {
				if ( array_key_exists( $option_name, $params ) ) {
					update_option( $option_name, trim( $params[ $option_name ] ) );
				}
			}
		}

		return $alert;
	}
}
