<?php
/**
 * Extension
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\Give
 */

namespace Pronamic\WordPress\Pay\Extensions\Give;

use Pronamic\WordPress\Pay\AbstractPluginIntegration;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: Give extension
 * Description:
 * Copyright: 2005-2020 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.1.1
 * @since   1.0.0
 */
class Extension extends AbstractPluginIntegration {
	/**
	 * Slug
	 *
	 * @var string
	 */
	const SLUG = 'give';

	/**
	 * Gateways.
	 *
	 * @var array|null
	 */
	private $gateways;

	/**
	 * Construct Give plugin integration.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name' => __( 'Give', 'pronamic_ideal' ),
			)
		);

		// Dependencies.
		$dependencies = $this->get_dependencies();

		$dependencies->add( new GiveDependency() );

		// Add Phone Number field in Give donation form which is mandatory for most of the India Payment Gateways
		require_once 'custom-fields-phone.php';
	}

	/**
	 * Setup.
	 *
	 * @return void
	 */
	public function setup() {
		\add_filter( 'pronamic_payment_source_description_' . self::SLUG, array( $this, 'source_description' ), 10, 2 );
		\add_filter( 'pronamic_payment_source_text_' . self::SLUG, array( $this, 'source_text' ), 10, 2 );
		\add_filter( 'pronamic_payment_source_url_' . self::SLUG, array( $this, 'source_url' ), 10, 2 );

		// Check if dependencies are met and integration is active.
		if ( ! $this->is_active() ) {
			return;
		}

		\add_action( 'pronamic_payment_status_update_' . self::SLUG, array( $this, 'status_update' ), 10, 1 );
		\add_filter( 'pronamic_payment_redirect_url_' . self::SLUG, array( $this, 'redirect_url' ), 10, 2 );

		\add_filter( 'give_payment_gateways', array( $this, 'give_payment_gateways' ) );
		\add_filter( 'give_currencies', array( __CLASS__, 'currencies' ), 10, 1 );
	}

	/**
	 * Give payments gateways.
	 *
	 * @link https://github.com/WordImpress/Give/blob/1.3.6/includes/gateways/functions.php#L37
	 *
	 * @param array $gateways Gateways.
	 *
	 * @return array
	 */
	public function give_payment_gateways( $gateways ) {
		if ( null === $this->gateways ) {
			$this->gateways = array();

			$classes = array(
				'Gateway',
				'BankTransferGateway',
				'CreditCardGateway',
			);

			if ( PaymentMethods::is_active( PaymentMethods::GULDEN ) ) {
				$classes[] = 'GuldenGateway';
			}

			foreach ( $classes as $class ) {
				$class = __NAMESPACE__ . '\\' . $class;

				$gateway = new $class();

				$this->gateways[ $gateway->id ] = array(
					'admin_label'    => $gateway->name,
					'checkout_label' => $gateway->name,
				);
			}
		}

		return array_merge( $gateways, $this->gateways );
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @param string  $url     Redirect URL.
	 * @param Payment $payment Payment.
	 *
	 * @return string
	 */
	public function redirect_url( $url, $payment ) {
		switch ( $payment->get_status() ) {
			case PaymentStatus::CANCELLED:
			case PaymentStatus::FAILURE:
				$url = give_get_failed_transaction_uri();

				break;
			case PaymentStatus::SUCCESS:
				$url = give_get_success_page_uri();

				break;
		}

		return $url;
	}

	/**
	 * Update lead status of the specified payment
	 *
	 * @link https://github.com/Charitable/Charitable/blob/1.1.4/includes/gateways/class-charitable-gateway-paypal.php#L229-L357
	 *
	 * @param Payment $payment Payment.
	 */
	public function status_update( Payment $payment ) {
		$donation_id = (int) $payment->get_source_id();

		switch ( $payment->get_status() ) {
			case PaymentStatus::CANCELLED:
				give_update_payment_status( $donation_id, 'cancelled' );

				break;
			case PaymentStatus::EXPIRED:
				give_update_payment_status( $donation_id, 'abandoned' );

				break;
			case PaymentStatus::FAILURE:
				give_update_payment_status( $donation_id, 'failed' );

				break;
			case PaymentStatus::SUCCESS:
				give_update_payment_status( $donation_id, 'publish' );

				break;
			case PaymentStatus::OPEN:
			default:
				give_update_payment_status( $donation_id, 'pending' );

				break;
		}
	}

	/**
	 * Filter currencies.
	 *
	 * @param array $currencies Available currencies.
	 *
	 * @return mixed
	 */
	public static function currencies( $currencies ) {
		if ( PaymentMethods::is_active( PaymentMethods::GULDEN ) ) {
			$currencies['NLG'] = array(
				'admin_label' => PaymentMethods::get_name( PaymentMethods::GULDEN ) . ' (G)',
				'symbol'      => 'G',
				'setting'     => array(
					'currency_position'   => 'before',
					'thousands_separator' => '',
					'decimal_separator'   => '.',
					'number_decimals'     => 4,
				),
			);
		}

		return $currencies;
	}

	/**
	 * Source column
	 *
	 * @param string  $text    Source text.
	 * @param Payment $payment Payment.
	 *
	 * @return string
	 */
	public function source_text( $text, Payment $payment ) {
		$source_id = (int) $payment->source_id;

		$text = __( 'Give', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $source_id ),
			/* translators: %s: source id */
			sprintf( __( 'Donation %s', 'pronamic_ideal' ), $source_id )
		);

		return $text;
	}

	/**
	 * Source description.
	 *
	 * @param string  $description Source description.
	 * @param Payment $payment     Payment.
	 *
	 * @return string
	 */
	public function source_description( $description, Payment $payment ) {
		return __( 'Give Donation', 'pronamic_ideal' );
	}

	/**
	 * Source URL.
	 *
	 * @param string  $url     Source URL.
	 * @param Payment $payment payment.
	 *
	 * @return string
	 */
	public function source_url( $url, Payment $payment ) {
		return get_edit_post_link( (int) $payment->source_id );
	}
}
