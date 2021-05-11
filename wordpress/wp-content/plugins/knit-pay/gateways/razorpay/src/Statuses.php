<?php

namespace KnitPay\Gateways\Razorpay;

use Pronamic\WordPress\Pay\Payments\PaymentStatus as Core_Statuses;

/**
 * Title: Razorpay Statuses
 * Copyright: 2020-2021 Knit Pay
 *
 * @author  Knit Pay
 * @version 1.0.0
 * @since   1.7.0
 */
class Statuses {
	/**
	 * PAID
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/payment-links/#payment-links-life-cycle
	 */
	const PAID = 'paid';

	/**
	 * CANCELLED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/payment-links/#payment-links-life-cycle
	 */
	const CANCELLED = 'cancelled';

	/**
	 * ISSUED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/payment-links/#payment-links-life-cycle
	 */
	const ISSUED = 'issued';

	/**
	 * EXPIRED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/api/invoices/#invoices-entity
	 */
	const EXPIRED = 'expired';

	/**
	 * CREATED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/api/payments/#payment-entity
	 */
	const CREATED = 'created';

	/**
	 * CAPTURED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/api/payments/#payment-entity
	 */
	const CAPTURED = 'captured';

	/**
	 * FAILED.
	 *
	 * @var string
	 *
	 * @link https://razorpay.com/docs/api/payments/#payment-entity
	 */
	const FAILED = 'failed';


	/**
	 * Transform an Razorpay status to an Knit Pay status
	 *
	 * @param string $status
	 *
	 * @return null|string
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::PAID:
			case self::CAPTURED:
				return Core_Statuses::SUCCESS;

			case self::CANCELLED:
				return Core_Statuses::CANCELLED;

			case self::FAILED:
				return Core_Statuses::FAILURE;

			case self::ISSUED:
			case self::CREATED:
				return Core_Statuses::OPEN;

			default:
				return null;
		}
	}
}
