<?php

namespace KnitPay\Gateways\PayUmoney;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: PayUMoney Config
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.9.1
 * @since   1.0.0
 */
class Config extends GatewayConfig {
	public $merchant_key;

	public $merchant_salt;

	public $auth_header;

	public $checkout_mode;

	public $authorization_header_value;
}
