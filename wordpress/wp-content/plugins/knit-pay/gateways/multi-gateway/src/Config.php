<?php

namespace KnitPay\Gateways\MultiGateway;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: Multi Gateway Config
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   4.0.0
 */
class Config extends GatewayConfig {
	public $enabled_payment_gateways;
}
