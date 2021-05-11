<?php

namespace KnitPay\Gateways\Cashfree;

use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;

/**
 * Title: Cashfree Statuses
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   2.4
 */
class Statuses {

	const PENDING = 'PENDING';

	const CANCELLED = 'CANCELLED';

	const SUCCESS = 'SUCCESS';

	const FLAGGED = 'FLAGGED';

	const FAILED = 'FAILED';

	/**
	 * Transform an Cashfree status to an Knit Pay status
	 *
	 * @param string $status
	 *
	 * @return null|string
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::SUCCESS:
				return Core_Statuses::SUCCESS;

			case self::CANCELLED:
				return Core_Statuses::CANCELLED;

			case self::PENDING:
				return Core_Statuses::OPEN;

			case self::FLAGGED:
				return Core_Statuses::ON_HOLD;

			case self::FAILED:
				return Core_Statuses::FAILURE;

			default:
				return null;
		}
	}
}
