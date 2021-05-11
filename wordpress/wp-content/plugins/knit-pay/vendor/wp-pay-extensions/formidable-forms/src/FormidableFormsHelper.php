<?php
/**
 * Formidable Forms Helper
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\FormidableForms
 */

namespace Pronamic\WordPress\Pay\Extensions\FormidableForms;

use Pronamic\WordPress\Pay\Address;
use Pronamic\WordPress\Pay\AddressHelper;
use Pronamic\WordPress\Pay\ContactName;
use Pronamic\WordPress\Pay\ContactNameHelper;
use Pronamic\WordPress\Pay\CustomerHelper;
use FrmField;
use FrmFieldsHelper;
use FrmProAppHelper;
use Pronamic\WordPress\Money\Parser;

/**
 * Formidable Forms Helper
 *
 * @author  Remco Tolsma
 * @version 2.2.0
 * @since   2.2.0
 */
class FormidableFormsHelper {
	/**
	 * Get currency from settings.
	 *
	 * @return string
	 */
	public static function get_currency_from_settings() {
		$currency = null;

		// Try to get currency from Formidable Form settings.
		if ( \class_exists( '\FrmProAppHelper' ) ) {
			$settings = FrmProAppHelper::get_settings();

			$currency = \trim( $settings->currency );
		}

		// Check empty currency.
		if ( empty( $currency ) ) {
			$currency = 'INR';
		}

		return $currency;
	}

	/**
	 * Get description.
	 *
	 * @param unknown $action   Action.
	 * @param int     $form_id  Form ID.
	 * @param unknown $entry    Entry.
	 * @param int     $entry_id Entry ID.
	 * @return string
	 */
	public static function get_description( $action, $form_id, $entry, $entry_id ) {
		// Description template.
		$description_template = $action->post_content['pronamic_pay_transaction_description'];

		/*
		 * Find shortcode.
		 *
		 * @link https://github.com/wp-premium/formidable/blob/2.0.22/classes/helpers/FrmFieldsHelper.php#L684-L696
		 */
		$shortcodes = FrmFieldsHelper::get_shortcodes( $description_template, $form_id );

		/*
		 * Replace shortcodes.
		 *
		 * @link https://github.com/wp-premium/formidable/blob/2.0.22/classes/helpers/FrmFieldsHelper.php#L715-L821
		 */
		$description = FrmFieldsHelper::replace_content_shortcodes( $description_template, $entry, $shortcodes );

		// Check if there was a replacement to make sure the description has a dynamic part.
		if ( $description_template === $description ) {
			$description .= ' ' . $entry_id;
		}

		return $description;
	}

	/**
	 * Get payment method from action/entry.
	 *
	 * @param unknowm $action Action.
	 * @param unknown $entry  Entry.
	 * @return string|null
	 */
	public static function get_payment_method_from_action_entry( $action, $entry ) {
		$payment_method = null;

		$payment_method_field = $action->post_content['pronamic_pay_payment_method_field'];

		if ( ! empty( $payment_method_field ) && isset( $entry->metas[ $payment_method_field ] ) ) {
			$payment_method = $entry->metas[ $payment_method_field ];

			$replacements = array(
				'pronamic_pay_' => '',
				'pronamic_pay'  => '',
			);

			$payment_method = strtr( $payment_method, $replacements );

			if ( empty( $payment_method ) ) {
				$payment_method = null;
			}
		}

		return $payment_method;
	}

	/**
	 * Get issuer from form entry.
	 *
	 * @param int     $form_id Form ID.
	 * @param unknown $entry   Entry.
	 * @return string|null
	 */
	public static function get_issuer_from_form_entry( $form_id, $entry ) {
		$bank = null;

		$bank_fields = FrmField::get_all_types_in_form( $form_id, 'pronamic_bank_select' );

		$bank_field = reset( $bank_fields );

		if ( $bank_field && isset( $entry->metas[ $bank_field->id ] ) ) {
			$bank = $entry->metas[ $bank_field->id ];
		}

		return $bank;
	}

	/**
	 * Get amount from field.
	 *
	 * @param unknowm $action Action.
	 * @param unknown $entry  Entry.
	 * @return float
	 */
	public static function get_amount_from_field( $action, $entry ) {
		$amount = 0;

		$amount_field = $action->post_content['pronamic_pay_amount_field'];

		if ( ! empty( $amount_field ) && isset( $entry->metas[ $amount_field ] ) ) {
			$parser = new Parser();

			$amount = $parser->parse( $entry->metas[ $amount_field ] )->get_value();
		}

		return $amount;
	}

	/**
	 * Get origin post ID from entry.
	 *
	 * @since 2.1.3
	 * @return int|null
	 */
	public static function get_origin_id_from_entry( $entry ) {
		// Get origin post ID via referrer in entry.
		if ( \property_exists( $entry, 'description' ) && \is_array( $entry->description ) && isset( $entry->description['referrer'] ) ) {
			$post_id = \url_to_postid( $entry->description['referrer'] );

			if ( $post_id > 0 ) {
				return $post_id;
			}
		}

		return null;
	}

	private static function get_field_value( $action, $entry, $field_name ) {

		$value = '';

		$field = $action->post_content[ $field_name ];

		if ( ! empty( $field ) && isset( $entry->metas[ $field ] ) ) {
			$value = $entry->metas[ $field ];
		}

		return $value;
	}

	/**
	 * Get customer from user data.
	 */
	public static function get_customer( $action, $entry ) {
		return CustomerHelper::from_array(
			array(
				'name'    => self::get_name( $action, $entry ),
				'email'   => self::get_field_value( $action, $entry, 'pronamic_pay_email_field' ),
				'phone'   => self::get_field_value( $action, $entry, 'pronamic_pay_phone_field' ),
				'user_id' => null,
			)
		);
	}

	/**
	 * Get name from user data.
	 *
	 * @return ContactName|null
	 */
	public static function get_name( $action, $entry ) {
		$name       = self::get_field_value( $action, $entry, 'pronamic_pay_name_field' );
		$name       = trim( $name );
		$last_name  = ( strpos( $name, ' ' ) === false ) ? '' : preg_replace( '#.*\s([\w-]*)$#', '$1', $name );
		$first_name = trim( preg_replace( '#' . preg_quote( $last_name, '#' ) . '#', '', $name ) );

		return ContactNameHelper::from_array(
			array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
			)
		);
	}

	/**
	 * Get address from user info.
	 *
	 * @return Address|null
	 */
	public static function get_address( $action, $entry ) {

		return AddressHelper::from_array(
			array(
				'name'  => self::get_name( $action, $entry ),
				'email' => self::get_field_value( $action, $entry, 'pronamic_pay_email_field' ),
				'phone' => self::get_field_value( $action, $entry, 'pronamic_pay_phone_field' ),
			)
		);
	}
}
