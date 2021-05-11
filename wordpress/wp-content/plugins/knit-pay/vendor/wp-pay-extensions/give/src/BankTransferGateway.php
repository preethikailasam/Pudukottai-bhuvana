<?php
/**
 * Bank transfer gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\Give
 */

namespace Pronamic\WordPress\Pay\Extensions\Give;

use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Title: Give Bank Transfer gateway
 * Description:
 * Copyright: 2005-2020 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.3
 * @since   1.0.0
 */
class BankTransferGateway extends Gateway {
	/**
	 * Constructs and initialize Bank Transfer gateway
	 */
	public function __construct() {
		parent::__construct(
			'pronamic_pay_bank_transfer',
			__( 'Bank Transfer', 'pronamic_ideal' ),
			PaymentMethods::BANK_TRANSFER
		);
	}
}
