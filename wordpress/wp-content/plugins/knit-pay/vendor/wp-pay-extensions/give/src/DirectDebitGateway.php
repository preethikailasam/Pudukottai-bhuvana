<?php
/**
 * Direct debit gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\Give
 */

namespace Pronamic\WordPress\Pay\Extensions\Give;

use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Title: Give Direct Debit gateway
 * Description:
 * Copyright: 2005-2020 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.3
 * @since   1.0.0
 */
class DirectDebitGateway extends Gateway {
	/**
	 * Constructs and initialize Direct Debit gateway.
	 */
	public function __construct() {
		parent::__construct(
			'pronamic_pay_direct_debit',
			__( 'Direct Debit', 'pronamic_ideal' ),
			PaymentMethods::DIRECT_DEBIT
		);
	}
}
