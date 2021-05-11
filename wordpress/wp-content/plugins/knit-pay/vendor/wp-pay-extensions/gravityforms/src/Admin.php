<?php
/**
 * Admin
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\GravityForms
 */

namespace Pronamic\WordPress\Pay\Extensions\GravityForms;

use RGFormsModel;
use stdClass;

/**
 * Title: WordPress pay extension Gravity Forms admin
 * Description:
 * Copyright: 2005-2020 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.1.14
 * @since   1.0.0
 */
class Admin {
	/**
	 * Bootstrap.
	 */
	public static function bootstrap() {
		// Actions.
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_redirect_to_entry' ) );

		// Filters.
		add_filter( 'gform_addon_navigation', array( __CLASS__, 'addon_navigation' ) );

		add_filter( 'gform_entry_info', array( __CLASS__, 'entry_info' ), 10, 2 );

		add_filter( 'gform_custom_merge_tags', array( __CLASS__, 'custom_merge_tags' ), 10 );

		// Actions - AJAX.
		add_action( 'wp_ajax_gf_get_form_data', array( __CLASS__, 'ajax_get_form_data' ) );
		add_action( 'wp_ajax_gf_dismiss_pronamic_pay_feeds_menu', array( __CLASS__, 'ajax_dismiss_feeds_menu' ) );
	}

	/**
	 * Admin initialize.
	 */
	public static function admin_init() {
		new AdminPaymentFormPostType();
	}

	/**
	 * Gravity Forms addon navigation.
	 *
	 * @param array $menus Addon menu items.
	 *
	 * @return array
	 */
	public static function addon_navigation( $menus ) {
		if ( GravityForms::version_compare( '1.7', '<' ) ) {
			$menus[] = array(
				'name'       => 'edit.php?post_type=pronamic_pay_gf',
				'label'      => __( 'Payment Feeds', 'pronamic_ideal' ),
				'callback'   => null,
				'permission' => 'manage_options',
			);

			return $menus;
		}

		if ( '1' === get_user_meta( get_current_user_id(), '_pronamic_pay_gf_dismiss_feeds_menu', true ) ) {
			return $menus;
		}

		$menus[] = array(
			'name'       => PaymentAddOn::SLUG,
			'label'      => __( 'Payment Feeds', 'pronamic_ideal' ),
			'callback'   => array( __CLASS__, 'temporary_feeds_page' ),
			'permission' => 'manage_options',
		);

		return $menus;
	}

	/**
	 * Temporary feeds page.
	 *
	 * @since unreleased
	 */
	public static function temporary_feeds_page() {
		require dirname( __FILE__ ) . '/../views/html-admin-temporary-feeds-page.php';
	}

	/**
	 * Ajax action to dismiss feeds menu item.
	 */
	public function ajax_dismiss_feeds_menu() {
		$current_user = wp_get_current_user();

		update_user_meta( $current_user->ID, '_pronamic_pay_gf_dismiss_feeds_menu', 1 );
	}

	/**
	 * Add menu item to form settings.
	 *
	 * @param array $menu_items Array with form settings menu items.
	 *
	 * @return array
	 */
	public static function form_settings_menu_item( $menu_items ) {
		$menu_items[] = array(
			'name'  => 'pronamic_pay',
			'label' => __( 'Pay', 'pronamic_ideal' ),
			'query' => array( 'fid' => null ),
		);

		return $menu_items;
	}

	/**
	 * Render entry info of the specified form and lead
	 *
	 * @param string $form_id Gravity Forms form ID.
	 * @param array  $lead    Gravity Forms lead/entry.
	 */
	public static function entry_info( $form_id, $lead ) {
		$payment_id = gform_get_meta( $lead['id'], 'pronamic_payment_id' );

		if ( ! $payment_id ) {
			return;
		}

		printf(
			'<a href="%s">%s</a>',
			esc_attr( get_edit_post_link( $payment_id ) ),
			esc_html( get_the_title( $payment_id ) )
		);
	}

	/**
	 * Custom merge tags.
	 *
	 * @param array $merge_tags Array with merge tags.
	 * @return array
	 */
	public static function custom_merge_tags( $merge_tags ) {
		// Payment.
		$merge_tags[] = array(
			'label' => __( 'Payment Status', 'pronamic_ideal' ),
			'tag'   => '{payment_status}',
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Date', 'pronamic_ideal' ),
			'tag'   => '{payment_date}',
		);

		$merge_tags[] = array(
			'label' => __( 'Transaction Id', 'pronamic_ideal' ),
			'tag'   => '{transaction_id}',
		);

		$merge_tags[] = array(
			'label' => __( 'Payment Amount', 'pronamic_ideal' ),
			'tag'   => '{payment_amount}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Payment ID', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_id}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay Again URL', 'pronamic_ideal' ),
			'tag'   => '{knitpay_pay_again_url}',
		);

		// Bank transfer.
		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient reference', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_reference}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient bank name', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_bank_name}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient name', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_name}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient IBAN', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_iban}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient BIC', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_bic}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient city', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_city}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient country', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_country}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay bank transfer recipient account number', 'pronamic_ideal' ),
			'tag'   => '{knitpay_payment_bank_transfer_recipient_account_number}',
		);

		// Subscription.
		$merge_tags[] = array(
			'label' => __( 'Knit Pay Subscription Payment ID', 'pronamic_ideal' ),
			'tag'   => '{knitpay_subscription_payment_id}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay Subscription Amount', 'pronamic_ideal' ),
			'tag'   => '{knitpay_subscription_amount}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay Subscription Cancel URL', 'pronamic_ideal' ),
			'tag'   => '{knitpay_subscription_cancel_url}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay Subscription Renew URL', 'pronamic_ideal' ),
			'tag'   => '{knitpay_subscription_renew_url}',
		);

		$merge_tags[] = array(
			'label' => __( 'Knit Pay Subscription Renewal Date', 'pronamic_ideal' ),
			'tag'   => '{knitpay_subscription_renewal_date}',
		);

		return $merge_tags;
	}

	/**
	 * Maybe redirect to Gravity Forms entry
	 */
	public static function maybe_redirect_to_entry() {
		if ( ! filter_has_var( INPUT_GET, 'pronamic_gf_lid' ) ) {
			return;
		}

		$lead_id = filter_input( INPUT_GET, 'pronamic_gf_lid', FILTER_SANITIZE_STRING );

		$lead = RGFormsModel::get_lead( $lead_id );

		if ( ! empty( $lead ) ) {
			$url = add_query_arg(
				array(
					'page' => 'gf_entries',
					'view' => 'entry',
					'id'   => $lead['form_id'],
					'lid'  => $lead_id,
				),
				admin_url( 'admin.php' )
			);

			wp_safe_redirect( $url );

			exit;
		}
	}

	/**
	 * Handle AJAX request get form data
	 */
	public static function ajax_get_form_data() {
		$form_id = filter_input( INPUT_GET, 'formId', FILTER_SANITIZE_STRING );

		$data = RGFormsModel::get_form_meta( $form_id );

		wp_send_json_success( $data );
	}

	/**
	 * Get new feed URL.
	 *
	 * @since 1.6.3
	 *
	 * @param string $form_id Gravity Forms form ID.
	 * @return string
	 */
	public static function get_new_feed_url( $form_id ) {
		if ( GravityForms::version_compare( '1.7', '<' ) ) {
			return add_query_arg( 'post_type', 'pronamic_pay_gf', admin_url( 'post-new.php' ) );
		}

		return add_query_arg(
			array(
				'page'    => 'gf_edit_forms',
				'view'    => 'settings',
				'subview' => 'pronamic_pay',
				'id'      => $form_id,
				'fid'     => 0,
			),
			admin_url( 'admin.php' )
		);
	}
}
