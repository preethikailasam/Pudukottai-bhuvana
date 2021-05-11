<?php
/**
 * Legacy payment
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

/**
 * Legacy payment.
 *
 * Legacy and deprecated functions are here to keep the Payment class clean.
 * This class will be removed in future versions.
 *
 * @author  Remco Tolsma
 * @version 2.2.6
 * @since   2.1.0
 *
 * @property string   $language
 * @property string   $locale
 * @property string   $email
 * @property string   $first_name
 * @property string   $last_name
 * @property string   $telephone_number
 * @property string   $country
 * @property string   $zip
 * @property string   $city
 * @property string   $address
 * @property int|null $user_id
 */
abstract class LegacyPayment extends LegacyPaymentInfo {

}
