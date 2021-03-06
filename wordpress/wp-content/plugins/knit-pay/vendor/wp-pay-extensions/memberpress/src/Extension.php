<?php
/**
 * Extension
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Extensions\MemberPress
 */

namespace Pronamic\WordPress\Pay\Extensions\MemberPress;

use MeprDb;
use MeprOptions;
use MeprProduct;
use MeprSubscription;
use MeprTransaction;
use MeprUtils;
use Pronamic\WordPress\Pay\AbstractPluginIntegration;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Extensions\MemberPress\Gateways\Gateway;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use Pronamic\WordPress\Pay\Subscriptions\SubscriptionStatus;

/**
 * WordPress pay MemberPress extension
 *
 * @author  Remco Tolsma
 * @version 2.2.3
 * @since   1.0.0
 */
class Extension extends AbstractPluginIntegration {
	/**
	 * The slug of this addon
	 *
	 * @var string
	 */
	const SLUG = 'memberpress';

	/**
	 * Construct MemberPress plugin integration.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name' => __( 'MemberPress', 'pronamic_ideal' ),
			)
		);

		// Dependencies.
		$dependencies = $this->get_dependencies();

		$dependencies->add( new MemberPressDependency() );
	}

	/**
	 * Setup.
	 */
	public function setup() {
		\add_filter( 'pronamic_subscription_source_description_' . self::SLUG, array( $this, 'subscription_source_description' ), 10, 2 );
		\add_filter( 'pronamic_payment_source_description_' . self::SLUG, array( $this, 'source_description' ), 10, 2 );

		// Check if dependencies are met and integration is active.
		if ( ! $this->is_active() ) {
			return;
		}

		// @link https://gitlab.com/pronamic/memberpress/blob/1.2.4/app/lib/MeprGatewayFactory.php#L48-50
		\add_filter( 'mepr-gateway-paths', array( $this, 'gateway_paths' ) );

		\add_filter( 'pronamic_payment_redirect_url_' . self::SLUG, array( $this, 'redirect_url' ), 10, 2 );
		\add_action( 'pronamic_payment_status_update_' . self::SLUG, array( $this, 'status_update' ), 10, 1 );

		\add_filter( 'pronamic_subscription_source_text_' . self::SLUG, array( $this, 'subscription_source_text' ), 10, 2 );
		\add_filter( 'pronamic_subscription_source_url_' . self::SLUG, array( $this, 'subscription_source_url' ), 10, 2 );
		\add_filter( 'pronamic_payment_source_text_' . self::SLUG, array( $this, 'source_text' ), 10, 2 );
		\add_filter( 'pronamic_payment_source_url_' . self::SLUG, array( $this, 'source_url' ), 10, 2 );

		\add_action( 'mepr_subscription_pre_delete', array( $this, 'subscription_pre_delete' ), 10, 1 );

		\add_action( 'mepr_subscription_transition_status', array( $this, 'memberpress_subscription_transition_status' ), 10, 3 );

		// Hide MemberPress columns for payments and subscriptions.
		\add_filter( 'registered_post_type', array( $this, 'post_type_columns_hide' ), 15, 1 );

		if ( \is_admin() ) {
			$this->admin_subscriptions = new Admin\AdminSubscriptions();
			$this->admin_transactions  = new Admin\AdminTransactions();

			$this->admin_subscriptions->setup();
			$this->admin_transactions->setup();
		}
	}

	/**
	 * Gateway paths.
	 *
	 * @link https://gitlab.com/pronamic/memberpress/blob/1.2.4/app/lib/MeprGatewayFactory.php#L48-50
	 *
	 * @param array $paths Array with gateway paths.
	 * @return array
	 */
	public function gateway_paths( $paths ) {
		$paths[] = dirname( __FILE__ ) . '/../gateways/';

		return $paths;
	}

	/**
	 * Hide MemberPress columns for payments and subscriptions.
	 *
	 * @link https://gitlab.com/pronamic/memberpress/blob/1.2.4/app/controllers/MeprAppCtrl.php#L129-146
	 *
	 * @param string $post_type Registered post type.
	 *
	 * @return void
	 */
	public function post_type_columns_hide( $post_type ) {
		if ( ! in_array( $post_type, array( 'pronamic_payment', 'pronamic_pay_subscr' ), true ) ) {
			return;
		}

		remove_filter( 'manage_edit-' . $post_type . '_columns', 'MeprAppCtrl::columns' );
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @since 1.0.1
	 *
	 * @param string  $url     Payment redirect URL.
	 * @param Payment $payment Payment to redirect for.
	 *
	 * @return string
	 */
	public function redirect_url( $url, Payment $payment ) {
		global $transaction;

		$transaction_id = $payment->get_source_id();

		$transaction = new MeprTransaction( $transaction_id );

		switch ( $payment->get_status() ) {
			case PaymentStatus::CANCELLED:
			case PaymentStatus::EXPIRED:
			case PaymentStatus::FAILURE:
				$product = $transaction->product();

				$url = add_query_arg(
					array(
						'action'   => 'payment_form',
						'txn'      => $transaction->trans_num,
						'errors'   => array(
							__( 'Payment failed. Please try again.', 'pronamic_ideal' ),
						),
						'_wpnonce' => wp_create_nonce( 'mepr_payment_form' ),
					),
					$product->url()
				);

				break;
			case PaymentStatus::SUCCESS:
				// @link https://gitlab.com/pronamic/memberpress/blob/1.2.4/app/models/MeprOptions.php#L768-782
				$mepr_options = MeprOptions::fetch();

				$product         = new MeprProduct( $transaction->product_id );
				$sanitized_title = sanitize_title( $product->post_title );

				$args = array(
					'membership_id' => $product->ID,
					'membership'    => $sanitized_title,
					'trans_num'     => $transaction->trans_num,
				);

				$url = $mepr_options->thankyou_page_url( http_build_query( $args ) );

				break;
			case PaymentStatus::OPEN:
			default:
				break;
		}

		return $url;
	}

	/**
	 * Update lead status of the specified payment.
	 *
	 * @link https://github.com/Charitable/Charitable/blob/1.1.4/includes/gateways/class-charitable-gateway-paypal.php#L229-L357
	 *
	 * @param Payment $payment The payment whose status is updated.
	 */
	public function status_update( Payment $payment ) {
		$transaction = new MeprTransaction( $payment->get_source_id() );

		if ( $payment->get_recurring() || empty( $transaction->id ) ) {
			$subscription_id = $payment->get_subscription()->get_source_id();
			$subscription    = new MeprSubscription( $subscription_id );

			// Same source ID and first transaction ID for recurring payment means we need to add a new transaction.
			if ( $payment->get_source_id() === $subscription->id ) {
				// First transaction.
				$first_txn = $subscription->first_txn();

				if ( false === $first_txn || ! ( $first_txn instanceof MeprTransaction ) ) {
					$first_txn             = new MeprTransaction();
					$first_txn->user_id    = $subscription->user_id;
					$first_txn->product_id = $subscription->product_id;
					$first_txn->coupon_id  = $subscription->coupon_id;
					$first_txn->gateway    = null;
				}

				// Transaction number.
				$trans_num = $payment->get_transaction_id();

				if ( empty( $trans_num ) ) {
					$trans_num = uniqid();
				}

				// New transaction.
				$transaction                  = new MeprTransaction();
				$transaction->created_at      = $payment->post->post_date_gmt;
				$transaction->user_id         = $first_txn->user_id;
				$transaction->product_id      = $first_txn->product_id;
				$transaction->coupon_id       = $first_txn->coupon_id;
				$transaction->gateway         = $first_txn->gateway;
				$transaction->trans_num       = $trans_num;
				$transaction->txn_type        = MeprTransaction::$payment_str;
				$transaction->status          = MeprTransaction::$pending_str;
				$transaction->expires_at      = MeprUtils::ts_to_mysql_date( $payment->get_end_date()->getTimestamp(), 'Y-m-d 23:59:59' );
				$transaction->subscription_id = $subscription->id;

				$transaction->set_gross( $payment->get_total_amount()->get_value() );

				$transaction->store();

				// Set source ID.
				$payment->set_meta( 'source_id', $transaction->id );

				$payment->source_id = $transaction->id;

				if ( MeprSubscription::$active_str === $subscription->status ) {
					/*
					 * We create a 'confirmed' 'subscription_confirmation'
					 * transaction for a grace period of 15 days.
					 *
					 * Transactions of type "subscription_confirmation" with a
					 * status of "confirmed" are hidden in the UI, and are used
					 * as a way to provide free trial periods and the 24 hour
					 * grace period on a recurring subscription signup.
					 *
					 * @link https://docs.memberpress.com/article/219-where-is-data-stored.
					 */
					$subscription_confirmation                  = new MeprTransaction();
					$subscription_confirmation->created_at      = $payment->post->post_date_gmt;
					$subscription_confirmation->user_id         = $first_txn->user_id;
					$subscription_confirmation->product_id      = $first_txn->product_id;
					$subscription_confirmation->coupon_id       = $first_txn->coupon_id;
					$subscription_confirmation->gateway         = $first_txn->gateway;
					$subscription_confirmation->trans_num       = $trans_num;
					$subscription_confirmation->txn_type        = MeprTransaction::$subscription_confirmation_str;
					$subscription_confirmation->status          = MeprTransaction::$confirmed_str;
					$subscription_confirmation->subscription_id = $subscription->id;
					$subscription_confirmation->expires_at      = MeprUtils::ts_to_mysql_date( strtotime( $payment->post->post_date_gmt ) + MeprUtils::days( 15 ), 'Y-m-d 23:59:59' );

					$subscription_confirmation->set_subtotal( 0.00 );

					$subscription_confirmation->store();
				}
			}
		}

		$should_update = ! MemberPress::transaction_has_status(
			$transaction,
			array(
				MeprTransaction::$complete_str,
			)
		);

		// Allow successful recurring payments to update failed transaction.
		if ( $payment->get_recurring() && PaymentStatus::SUCCESS === $payment->get_status() && MeprTransaction::$failed_str === $transaction->status ) {
			$should_update = true;
		}

		if ( $should_update ) {
			$gateway = new Gateway();

			$gateway->pronamic_payment = $payment;
			$gateway->mp_txn           = $transaction;

			switch ( $payment->get_status() ) {
				case PaymentStatus::FAILURE:
					$gateway->record_payment_failure();

					// Set subscription 'On Hold' to prevent subsequent
					// successful subscriptions from awaking subscription.
					if ( ! $payment->get_recurring() ) {
						$subscription = $payment->get_subscription();

						if ( null !== $subscription ) {
							$subscription->set_status( SubscriptionStatus::ON_HOLD );
						}
					}

					break;
				case PaymentStatus::CANCELLED:
				case PaymentStatus::EXPIRED:
					$gateway->record_payment_failure();

					break;
				case PaymentStatus::SUCCESS:
					if ( $payment->get_recurring() ) {
						$gateway->record_subscription_payment();
					} else {
						$gateway->record_payment();
					}

					break;
				case PaymentStatus::OPEN:
				default:
					break;
			}
		}
	}

	/**
	 * Subscription deleted.
	 *
	 * @param int $subscription_id MemberPress subscription id.
	 */
	public function subscription_pre_delete( $subscription_id ) {
		$subscription = get_pronamic_subscription_by_meta( '_pronamic_subscription_source_id', $subscription_id );

		if ( ! $subscription ) {
			return;
		}

		// Add note.
		$note = sprintf(
			/* translators: %s: MemberPress */
			__( '%s subscription deleted.', 'pronamic_ideal' ),
			__( 'MemberPress', 'pronamic_ideal' )
		);

		$subscription->add_note( $note );

		// The status of canceled or completed subscriptions will not be changed automatically.
		if ( ! in_array( $subscription->get_status(), array( SubscriptionStatus::CANCELLED, SubscriptionStatus::COMPLETED ), true ) ) {
			$subscription->set_status( SubscriptionStatus::CANCELLED );

			$subscription->save();
		}
	}

	/**
	 * Source text.
	 *
	 * @param string  $text    Source text.
	 * @param Payment $payment Payment to create the source text for.
	 *
	 * @return string
	 */
	public function source_text( $text, Payment $payment ) {
		$text = __( 'MemberPress', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			add_query_arg(
				array(
					'page'   => 'memberpress-trans',
					'action' => 'edit',
					'id'     => $payment->source_id,
				),
				admin_url( 'admin.php' )
			),
			/* translators: %s: payment source id */
			sprintf( __( 'Transaction %s', 'pronamic_ideal' ), $payment->source_id )
		);

		return $text;
	}

	/**
	 * Subscription source text.
	 *
	 * @param string       $text         Source text.
	 * @param Subscription $subscription Subscription to create the source text for.
	 *
	 * @return string
	 */
	public function subscription_source_text( $text, Subscription $subscription ) {
		$text = __( 'MemberPress', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			add_query_arg(
				array(
					'page'         => 'memberpress-subscriptions',
					'subscription' => $subscription->source_id,
				),
				admin_url( 'admin.php' )
			),
			/* translators: %s: payment source id */
			sprintf( __( 'Subscription %s', 'pronamic_ideal' ), $subscription->source_id )
		);

		return $text;
	}

	/**
	 * Source description.
	 *
	 * @param string  $description Description.
	 * @param Payment $payment     Payment to create the description for.
	 *
	 * @return string
	 */
	public function source_description( $description, Payment $payment ) {
		return __( 'MemberPress Transaction', 'pronamic_ideal' );
	}

	/**
	 * Subscription source description.
	 *
	 * @param string       $description  Description.
	 * @param Subscription $subscription Subscription to create the description for.
	 *
	 * @return string
	 */
	public function subscription_source_description( $description, Subscription $subscription ) {
		return __( 'MemberPress Subscription', 'pronamic_ideal' );
	}

	/**
	 * Source URL.
	 *
	 * @param string  $url     URL.
	 * @param Payment $payment The payment to create the source URL for.
	 *
	 * @return string
	 */
	public function source_url( $url, Payment $payment ) {
		$url = add_query_arg(
			array(
				'page'   => 'memberpress-trans',
				'action' => 'edit',
				'id'     => $payment->source_id,
			),
			admin_url( 'admin.php' )
		);

		return $url;
	}

	/**
	 * Subscription source URL.
	 *
	 * @param string       $url          URL.
	 * @param Subscription $subscription Subscription.
	 *
	 * @return string
	 */
	public function subscription_source_url( $url, Subscription $subscription ) {
		$url = add_query_arg(
			array(
				'page'         => 'memberpress-subscriptions',
				'subscription' => $subscription->source_id,
			),
			admin_url( 'admin.php' )
		);

		return $url;
	}

	/**
	 * MemberPress update subscription.
	 *
	 * @link https://github.com/wp-premium/memberpress-basic/blob/1.3.18/app/controllers/MeprSubscriptionsCtrl.php#L92-L111
	 * @link https://github.com/wp-premium/memberpress-basic/blob/1.3.18/app/models/MeprSubscription.php#L100-L123
	 * @link https://github.com/wp-premium/memberpress-basic/blob/1.3.18/app/models/MeprSubscription.php#L112
	 *
	 * @param string           $status_old               Old status identifier.
	 * @param string           $status_new               New status identifier.
	 * @param MeprSubscription $memberpress_subscription MemberPress subscription object.
	 */
	public function memberpress_subscription_transition_status( $status_old, $status_new, $memberpress_subscription ) {
		$subscription = get_pronamic_subscription_by_meta( '_pronamic_subscription_source_id', $memberpress_subscription->id );

		if ( empty( $subscription ) ) {
			return;
		}

		$status = SubscriptionStatuses::transform( $status_new );

		if ( null === $status ) {
			return;
		}

		$subscription->set_status( $status );

		$subscription->save();
	}
}
