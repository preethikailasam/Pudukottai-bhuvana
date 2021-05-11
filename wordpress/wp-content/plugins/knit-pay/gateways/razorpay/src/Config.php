<?php

namespace KnitPay\Gateways\Razorpay;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: Razorpay Config
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   1.7.0
 */
class Config extends GatewayConfig {
	public $key_id;

	public $key_secret;

	public $webhook_secret;
}
