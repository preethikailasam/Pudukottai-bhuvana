<?php
/**
 * Plugin
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Admin\AdminModule;
use Pronamic\WordPress\Pay\Banks\BankAccountDetails;
use Pronamic\WordPress\Pay\Core\Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\PaymentStatus;
use Pronamic\WordPress\Pay\Core\Util as Core_Util;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentPostType;
use Pronamic\WordPress\Pay\Payments\StatusChecker;
use Pronamic\WordPress\Pay\Subscriptions\SubscriptionPostType;
use Pronamic\WordPress\Pay\Webhooks\WebhookLogger;
use WP_Error;
use WP_Query;

/**
 * Plugin
 *
 * @author  Remco Tolsma
 * @version 2.5.1
 * @since   2.0.1
 */
class Plugin {
	/**
	 * Version.
	 *
	 * @var string
	 */
	private $version = '';

	/**
	 * The root file of this WordPress plugin
	 *
	 * @var string
	 */
	public static $file;

	/**
	 * The plugin dirname
	 *
	 * @var string
	 */
	public static $dirname;

	/**
	 * The timezone
	 *
	 * @var string
	 */
	const TIMEZONE = 'UTC';

	/**
	 * Instance.
	 *
	 * @var Plugin|null
	 */
	protected static $instance;

	/**
	 * Instance.
	 *
	 * @param string|array|object $args The plugin arguments.
	 *
	 * @return Plugin
	 */
	public static function instance( $args = array() ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $args );
		}

		return self::$instance;
	}

	/**
	 * Plugin settings.
	 *
	 * @var Settings
	 */
	public $settings;

	/**
	 * Payment data storing.
	 *
	 * @var Payments\PaymentsDataStoreCPT
	 */
	public $payments_data_store;

	/**
	 * Subscription data storing.
	 *
	 * @var Subscriptions\SubscriptionsDataStoreCPT
	 */
	public $subscriptions_data_store;

	/**
	 * Gateway post type.
	 *
	 * @var GatewayPostType
	 */
	public $gateway_post_type;

	/**
	 * Payment post type.
	 *
	 * @var PaymentPostType
	 */
	public $payment_post_type;

	/**
	 * Subscription post type.
	 *
	 * @var SubscriptionPostType
	 */
	public $subscription_post_type;

	/**
	 * Licence manager.
	 *
	 * @var LicenseManager
	 */
	public $license_manager;

	/**
	 * Privacy manager.
	 *
	 * @var PrivacyManager
	 */
	public $privacy_manager;

	/**
	 * Admin module.
	 *
	 * @var AdminModule
	 */
	public $admin;

	/**
	 * Blocks module.
	 *
	 * @var Blocks\BlocksModule
	 */
	public $blocks_module;

	/**
	 * Forms module.
	 *
	 * @var Forms\FormsModule
	 */
	public $forms_module;

	/**
	 * Tracking module.
	 *
	 * @var TrackingModule
	 */
	public $tracking_module;

	/**
	 * Payments module.
	 *
	 * @var Payments\PaymentsModule
	 */
	public $payments_module;

	/**
	 * Subsciptions module.
	 *
	 * @var Subscriptions\SubscriptionsModule
	 */
	public $subscriptions_module;

	/**
	 * Google analytics ecommerce.
	 *
	 * @var GoogleAnalyticsEcommerce
	 */
	public $google_analytics_ecommerce;

	/**
	 * Gateway integrations.
	 *
	 * @var GatewayIntegrations
	 */
	public $gateway_integrations;

	/**
	 * Integrations
	 *
	 * @var AbstractIntegration[]
	 */
	public $integrations;

	/**
	 * Webhook logger.
	 *
	 * @var WebhookLogger
	 */
	private $webhook_logger;

	/**
	 * Options.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Plugin integrations.
	 *
	 * @var array
	 */
	public $plugin_integrations;

	/**
	 * Construct and initialize an Pronamic Pay plugin object.
	 *
	 * @param string|array|object $args The plugin arguments.
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'file'    => null,
				'options' => array(),
			)
		);

		// Version from plugin file header.
		if ( null !== $args['file'] ) {
			$file_data = get_file_data( $args['file'], array( 'Version' => 'Version' ) );

			if ( \array_key_exists( 'Version', $file_data ) ) {
				$this->version = $file_data['Version'];
			}
		}

		// Backward compatibility.
		self::$file    = $args['file'];
		self::$dirname = dirname( self::$file );

		// Options.
		$this->options = $args['options'];

		// Integrations.
		$this->integrations = array();

		/*
		 * Plugins loaded.
		 *
		 * Priority should be at least lower then 8 to support the "WP eCommerce" plugin.
		 *
		 * new WP_eCommerce()
		 * add_action( 'plugins_loaded' , array( $this, 'init' ), 8 );
		 * $this->load();
		 * wpsc_core_load_gateways();
		 *
		 * @link https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L342-L343
		 * @link https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L26-L35
		 * @link https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L54
		 * @link https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L296-L297
		 */
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );

		// Plugin locale.
		add_filter( 'plugin_locale', array( $this, 'plugin_locale' ), 10, 2 );

		// Register styles.
		add_action( 'init', array( $this, 'register_styles' ), 9 );

		// If WordPress is loaded check on returns and maybe redirect requests.
		add_action( 'wp_loaded', array( $this, 'handle_returns' ), 10 );
		add_action( 'wp_loaded', array( $this, 'maybe_redirect' ), 10 );

		// Default date time format.
		add_filter( 'pronamic_datetime_default_format', array( $this, 'datetime_format' ), 10, 1 );
	}

	/**
	 * Get the version number of this plugin.
	 *
	 * @return string The version number of this plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get plugin file path.
	 *
	 * @return string
	 */
	public function get_file() {
		return self::$file;
	}

	/**
	 * Get option.
	 *
	 * @param string $option Name of option to retrieve.
	 * @return string|null
	 */
	public function get_option( $option ) {
		if ( array_key_exists( $option, $this->options ) ) {
			return $this->options[ $option ];
		}

		return null;
	}

	/**
	 * Get the plugin dir path.
	 *
	 * @return string
	 */
	public function get_plugin_dir_path() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * Update payment.
	 *
	 * @param Payment $payment      The payment to update.
	 * @param bool    $can_redirect Flag to indicate if redirect is allowed after the payment update.
	 * @return void
	 */
	public static function update_payment( $payment = null, $can_redirect = true ) {
		if ( empty( $payment ) ) {
			return;
		}

		// Gateway.
		$gateway = self::get_gateway( $payment->config_id );

		if ( empty( $gateway ) ) {
			return;
		}

		// Update status.
		$gateway->update_status( $payment );

		// Add gateway errors as payment notes.
		$error = $gateway->get_error();

		if ( $error instanceof WP_Error ) {
			foreach ( $error->get_error_codes() as $code ) {
				$payment->add_note( sprintf( '%s: %s', $code, $error->get_error_message( $code ) ) );
			}
		}

		// Update payment in data store.
		$payment->save();

		// Maybe redirect.
		if ( ! $can_redirect ) {
			return;
		}

		/*
		 * If WordPress is doing cron we can't redirect.
		 *
		 * @link https://github.com/pronamic/wp-pronamic-ideal/commit/bb967a3e7804ecfbd83dea110eb8810cbad097d7
		 * @link https://github.com/pronamic/wp-pronamic-ideal/commit/3ab4a7c1fc2cef0b6f565f8205da42aa1203c3c5
		 */
		if ( Core_Util::doing_cron() ) {
			return;
		}

		/*
		 * If WordPress CLI is runnig we can't redirect.
		 *
		 * @link https://basecamp.com/1810084/projects/10966871/todos/346407847
		 * @link https://github.com/woocommerce/woocommerce/blob/3.5.3/includes/class-woocommerce.php#L381-L383
		 */
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return;
		}

		// Redirect.
		$url = $payment->get_return_redirect_url();

		wp_redirect( $url );

		exit;
	}

	/**
	 * Handle returns.
	 *
	 * @return void
	 */
	public function handle_returns() {
		if ( ! filter_has_var( INPUT_GET, 'payment' ) ) {
			return;
		}

		$payment_id = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );

		$payment = get_pronamic_payment( $payment_id );

		if ( null === $payment ) {
			return;
		}

		// Check if payment key is valid.
		$valid_key = false;

		if ( empty( $payment->key ) ) {
			$valid_key = true;
		} elseif ( filter_has_var( INPUT_GET, 'key' ) ) {
			$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

			$valid_key = ( $key === $payment->key );
		}

		if ( ! $valid_key ) {
			wp_safe_redirect( home_url() );

			exit;
		}

		// Check if we should redirect.
		$should_redirect = apply_filters( 'pronamic_pay_return_should_redirect', true, $payment );

		try {
			self::update_payment( $payment, $should_redirect );
		} catch ( \Exception $e ) {
			self::render_exception( $e );

			exit;
		}
	}

	/**
	 * Maybe redirect.
	 *
	 * @return void
	 */
	public function maybe_redirect() {
		if ( ! filter_has_var( INPUT_GET, 'payment_redirect' ) || ! filter_has_var( INPUT_GET, 'key' ) ) {
			return;
		}

		// Get payment.
		$payment_id = filter_input( INPUT_GET, 'payment_redirect', FILTER_SANITIZE_NUMBER_INT );

		$payment = get_pronamic_payment( $payment_id );

		if ( null === $payment ) {
			return;
		}

		// Validate key.
		$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

		if ( $key !== $payment->key || empty( $payment->key ) ) {
			return;
		}

		// Don't cache.
		Core_Util::no_cache();

		// Handle redirect message from payment meta.
		$redirect_message = $payment->get_meta( 'payment_redirect_message' );

		if ( ! empty( $redirect_message ) ) {
			require self::$dirname . '/views/redirect-message.php';

			exit;
		}

		$gateway = self::get_gateway( $payment->config_id );

		if ( $gateway ) {
			// Give gateway a chance to handle redirect.
			$gateway->payment_redirect( $payment );

			// Handle HTML form redirect.
			if ( $gateway->is_html_form() ) {
				$gateway->start( $payment );

				$error = $gateway->get_error();

				if ( $error instanceof WP_Error ) {
					self::render_errors( $error );
				} else {
					$gateway->redirect( $payment );
				}
			}
		}

		// Redirect to payment action URL.
		if ( ! empty( $payment->action_url ) ) {
			wp_redirect( $payment->action_url );

			exit;
		}
	}

	/**
	 * Get number payments.
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_count_posts/
	 *
	 * @return int|false
	 */
	public static function get_number_payments() {
		$number = false;

		$count = wp_count_posts( 'pronamic_payment' );

		if ( isset( $count->payment_completed ) ) {
			$number = intval( $count->payment_completed );
		}

		return $number;
	}

	/**
	 * Plugins loaded.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/plugins_loaded/
	 * @link https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
	 * @return void
	 */
	public function plugins_loaded() {
		// Load plugin text domain.
		$rel_path = dirname( plugin_basename( self::$file ) );

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path . '/languages' );

		load_plugin_textdomain( 'pronamic-money', false, $rel_path . '/vendor/pronamic/wp-money/languages' );

		// Settings.
		$this->settings = new Settings( $this );

		// Data Stores.
		$this->payments_data_store      = new Payments\PaymentsDataStoreCPT();
		$this->subscriptions_data_store = new Subscriptions\SubscriptionsDataStoreCPT();

		$this->payments_data_store->setup();
		$this->subscriptions_data_store->setup();

		// Post Types.
		$this->gateway_post_type      = new GatewayPostType();
		$this->payment_post_type      = new PaymentPostType();
		$this->subscription_post_type = new SubscriptionPostType();

		// License Manager.
		// $this->license_manager = new LicenseManager( $this );

		// Privacy Manager.
		$this->privacy_manager = new PrivacyManager();

		// Webhook Logger.
		$this->webhook_logger = new WebhookLogger();
		$this->webhook_logger->setup();

		// Modules.
		$this->forms_module         = new Forms\FormsModule( $this );
		$this->payments_module      = new Payments\PaymentsModule( $this );
		$this->subscriptions_module = new Subscriptions\SubscriptionsModule( $this );
		$this->tracking_module      = new TrackingModule();

		// Blocks module.
		/*
		if ( function_exists( 'register_block_type' ) ) {
			$this->blocks_module = new Blocks\BlocksModule();
			$this->blocks_module->setup();
		}*/

		// Google Analytics Ecommerce.
		$this->google_analytics_ecommerce = new GoogleAnalyticsEcommerce();

		// Admin.
		if ( is_admin() ) {
			$this->admin = new Admin\AdminModule( $this );
		}

		// Gateway Integrations.
		$gateways = apply_filters( 'pronamic_pay_gateways', array() );

		$this->gateway_integrations = new GatewayIntegrations( $gateways );

		foreach ( $this->gateway_integrations as $integration ) {
			$integration->setup();
		}

		// Plugin Integrations.
		$this->plugin_integrations = apply_filters( 'pronamic_pay_plugin_integrations', array() );

		foreach ( $this->plugin_integrations as $integration ) {
			$integration->setup();
		}

		// Integrations.
		$gateway_integrations = \iterator_to_array( $this->gateway_integrations );

		$this->integrations = array_merge( $gateway_integrations, $this->plugin_integrations );

		// Maybes.
		PaymentMethods::maybe_update_active_payment_methods();

		// Filters.
		\add_filter( 'pronamic_payment_redirect_url', array( $this, 'payment_redirect_url' ), 10, 2 );
	}

	/**
	 * Filter plugin locale.
	 *
	 * @param string $locale A WordPress locale identifier.
	 * @param string $domain A WordPress text domain indentifier.
	 *
	 * @return string
	 */
	public function plugin_locale( $locale, $domain ) {
		if ( 'pronamic_ideal' !== $domain ) {
			return $locale;
		}

		if ( 'nl_NL_formal' === $locale ) {
			return 'nl_NL';
		}

		if ( 'nl_BE' === $locale ) {
			return 'nl_NL';
		}

		return $locale;
	}

	/**
	 * Default date time format.
	 *
	 * @param string $format Format.
	 *
	 * @return string
	 */
	public function datetime_format( $format ) {
		$format = _x( 'D j M Y \a\t H:i', 'default datetime format', 'pronamic_ideal' );

		return $format;
	}

	/**
	 * Get default error message.
	 *
	 * @return string
	 */
	public static function get_default_error_message() {
		return __( 'Something went wrong with the payment. Please try again later or pay another way.', 'pronamic_ideal' );
	}

	/**
	 * Register styles.
	 *
	 * @since 2.1.6
	 * @return void
	 */
	public function register_styles() {
		$min = SCRIPT_DEBUG ? '' : '.min';

		wp_register_style(
			'pronamic-pay-redirect',
			plugins_url( 'css/redirect' . $min . '.css', dirname( __FILE__ ) ),
			array(),
			$this->get_version()
		);
	}

	/**
	 * Get config select options.
	 *
	 * @param null|string $payment_method The gateway configuration options for the specified payment method.
	 *
	 * @return array
	 */
	public static function get_config_select_options( $payment_method = null ) {
		if ( 'knit_pay' === $payment_method ) {
			$payment_method = null;
		}

		$args = array(
			'post_type' => 'pronamic_gateway',
			'orderby'   => 'post_title',
			'order'     => 'ASC',
			'nopaging'  => true,
		);

		if ( null !== $payment_method ) {
			$config_ids = PaymentMethods::get_config_ids( $payment_method );

			$args['post__in'] = empty( $config_ids ) ? array( 0 ) : $config_ids;
		}

		$query = new WP_Query( $args );

		$options = array( __( '??? Select Configuration ???', 'pronamic_ideal' ) );

		foreach ( $query->posts as $post ) {
			$id = $post->ID;

			$options[ $id ] = sprintf(
				'%s (%s)',
				get_the_title( $id ),
				get_post_meta( $id, '_pronamic_gateway_mode', true )
			);
		}

		return $options;
	}

	/**
	 * Render errors.
	 *
	 * @param array|WP_Error $errors An array with errors to render.
	 * @return void
	 */
	public static function render_errors( $errors = array() ) {
		if ( ! is_array( $errors ) ) {
			$errors = array( $errors );
		}

		foreach ( $errors as $pay_error ) {
			include self::$dirname . '/views/error.php';
		}
	}

	/**
	 * Render exception.
	 *
	 * @param \Exception $exception An exception.
	 * @return void
	 */
	public static function render_exception( \Exception $exception ) {
		include self::$dirname . '/views/exception.php';
	}

	/**
	 * Get gateway.
	 *
	 * @link https://wordpress.org/support/article/post-status/#default-statuses
	 *
	 * @param string|integer|boolean|null $config_id A gateway configuration ID.
	 * @param array                       $args      Extra arguments.
	 *
	 * @return null|Gateway
	 */
	public static function get_gateway( $config_id, $args = array() ) {
		// Check for 0, false, null and other empty values.
		if ( empty( $config_id ) ) {
			return null;
		}

		$config_id = intval( $config_id );

		// Check if config is trashed.
		if ( 'trash' === get_post_status( $config_id ) ) {
			return null;
		}

		// Arguments.
		$args = wp_parse_args(
			$args,
			array(
				'gateway_id' => get_post_meta( $config_id, '_pronamic_gateway_id', true ),
				'mode'       => get_post_meta( $config_id, '_pronamic_gateway_mode', true ),
			)
		);

		// Get config.
		$gateway_id = $args['gateway_id'];
		$mode       = $args['mode'];

		$integration = pronamic_pay_plugin()->gateway_integrations->get_integration( $gateway_id );

		if ( null === $integration ) {
			return null;
		}

		$gateway = $integration->get_gateway( $config_id );

		return $gateway;
	}

	/**
	 * Complement payment.
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 */
	private static function complement_payment( Payment $payment ) {
		// Entrance Code.
		if ( null === $payment->entrance_code ) {
			$payment->entrance_code = uniqid();
		}

		// Key.
		if ( null === $payment->key ) {
			$payment->key = uniqid( 'pay_' );
		}

		// User ID.
		if ( null === $payment->user_id && is_user_logged_in() ) {
			$payment->user_id = get_current_user_id();
		}

		$origin_id = $payment->get_origin_id();

		if ( null === $origin_id ) {
			// Queried object.
			$queried_object    = \get_queried_object();
			$queried_object_id = \get_queried_object_id();

			if ( null !== $queried_object && $queried_object_id > 0 ) {
				$origin_id = $queried_object_id;
			}

			// Referer.
			$referer = \wp_get_referer();

			if ( null === $origin_id && false !== $referer ) {
				$post_id = \url_to_postid( $referer );

				if ( $post_id > 0 ) {
					$origin_id = $post_id;
				}
			}

			// Set origin ID.
			$payment->set_origin_id( $origin_id );
		}

		// Google Analytics client ID.
		if ( null === $payment->analytics_client_id ) {
			$payment->analytics_client_id = GoogleAnalyticsEcommerce::get_cookie_client_id();
		}

		// Customer.
		$customer = $payment->get_customer();

		if ( null === $customer ) {
			$customer = new Customer();

			$payment->set_customer( $customer );
		}

		CustomerHelper::complement_customer( $customer );

		// Email.
		if ( null === $payment->get_email() ) {
			$payment->email = $customer->get_email();
		}

		// Billing address.
		$billing_address = $payment->get_billing_address();

		if ( null !== $billing_address ) {
			AddressHelper::complement_address( $billing_address );
		}

		// Shipping address.
		$shipping_address = $payment->get_shipping_address();

		if ( null !== $shipping_address ) {
			AddressHelper::complement_address( $shipping_address );
		}

		// Version.
		if ( null === $payment->get_version() ) {
			$payment->set_version( pronamic_pay_plugin()->get_version() );
		}

		// Mode.
		$config_id = $payment->get_config_id();

		if ( null === $payment->get_mode() && null !== $config_id ) {
			$mode = get_post_meta( $config_id, '_pronamic_gateway_mode', true );

			$payment->set_mode( $mode );
		}

		// Issuer.
		if ( null === $payment->issuer ) {
			// Credit card.
			if ( PaymentMethods::CREDIT_CARD === $payment->method && filter_has_var( INPUT_POST, 'pronamic_credit_card_issuer_id' ) ) {
				$payment->issuer = filter_input( INPUT_POST, 'pronamic_credit_card_issuer_id', FILTER_SANITIZE_STRING );
			}

			// iDEAL.
			$ideal_methods = array( PaymentMethods::IDEAL, PaymentMethods::DIRECT_DEBIT_IDEAL );

			if ( \in_array( $payment->method, $ideal_methods, true ) && filter_has_var( INPUT_POST, 'pronamic_ideal_issuer_id' ) ) {
				$payment->issuer = filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );
			}
		}

		/**
		 * If an issuer has been specified and the payment
		 * method is unknown, we set the payment method to
		 * iDEAL. This may not be correct in all cases,
		 * but for now Pronamic Pay works this way.
		 *
		 * @link https://github.com/wp-pay-extensions/gravityforms/blob/2.4.0/src/Processor.php#L251-L256
		 * @link https://github.com/wp-pay-extensions/contact-form-7/blob/1.0.0/src/Pronamic.php#L181-L187
		 * @link https://github.com/wp-pay-extensions/formidable-forms/blob/2.1.0/src/Extension.php#L318-L329
		 * @link https://github.com/wp-pay-extensions/ninjaforms/blob/1.2.0/src/PaymentGateway.php#L80-L83
		 * @link https://github.com/wp-pay/core/blob/2.4.0/src/Forms/FormProcessor.php#L131-L134
		 */
		if ( null !== $payment->issuer && null === $payment->method ) {
			$payment->method = PaymentMethods::IDEAL;
		}

		// Consumer bank details.
		$consumer_bank_details = $payment->get_consumer_bank_details();

		if ( null === $consumer_bank_details ) {
			$consumer_bank_details = new BankAccountDetails();
		}

		if ( null === $consumer_bank_details->get_name() && filter_has_var( INPUT_POST, 'pronamic_pay_consumer_bank_details_name' ) ) {
			$consumer_bank_details->set_name( filter_input( INPUT_POST, 'pronamic_pay_consumer_bank_details_name', FILTER_SANITIZE_STRING ) );
		}

		if ( null === $consumer_bank_details->get_iban() && filter_has_var( INPUT_POST, 'pronamic_pay_consumer_bank_details_iban' ) ) {
			$consumer_bank_details->set_iban( filter_input( INPUT_POST, 'pronamic_pay_consumer_bank_details_iban', FILTER_SANITIZE_STRING ) );
		}

		$payment->set_consumer_bank_details( $consumer_bank_details );

		// Payment lines payment.
		$lines = $payment->get_lines();

		if ( null !== $lines ) {
			foreach ( $lines as $line ) {
				$line->set_payment( $payment );
			}
		}
	}

	/**
	 * Start payment.
	 *
	 * @param Payment $payment The payment to start at the specified gateway.
	 * @param Gateway $gateway The gateway to start the payment at.
	 *
	 * @return Payment
	 *
	 * @throws \Exception Throws exception if gateway payment start fails.
	 */
	public static function start_payment( Payment $payment, $gateway = null ) {
		global $pronamic_ideal;

		// Complement payment.
		self::complement_payment( $payment );

		/**
		 * Filters the payment gateway configuration ID.
		 *
		 * @param int     $configuration_id Gateway configuration ID.
		 * @param Payment $payment          The payment resource data.
		 */
		$config_id = \apply_filters( 'pronamic_payment_gateway_configuration_id', $payment->get_config_id(), $payment );

		$payment->set_config_id( $config_id );

		// Create payment.
		$pronamic_ideal->payments_data_store->create( $payment );

		// Prevent payment start at gateway if amount is empty.
		$amount = $payment->get_total_amount()->get_value();

		if ( empty( $amount ) ) {
			$payment->set_status( PaymentStatus::SUCCESS );

			$payment->save();

			/**
			 * Return or throw exception?
			 *
			 * @link https://github.com/wp-pay/core/commit/aa6422f0963d9718edd11ac41edbadfd6cd07d49
			 * @todo Throw exception?
			 */

			return $payment;
		}

		// Gateway.
		$gateway = self::get_gateway( $payment->get_config_id() );

		if ( null === $gateway ) {
			$payment->set_status( PaymentStatus::FAILURE );

			$payment->save();

			return $payment;
		}

		// Recurring.
		if ( true === $payment->get_recurring() && ! $gateway->supports( 'recurring' ) ) {
			throw new \Exception( 'Gateway does not support recurring payments.' );
		}

		// Start payment at the gateway.
		try {
			$gateway->start( $payment );

			// Add gateway errors as payment notes.
			$error = $gateway->get_error();

			if ( $error instanceof \WP_Error ) {
				$message = $error->get_error_message();
				$code    = $error->get_error_code();

				if ( ! \is_int( $code ) ) {
					$message = sprintf( '%s: %s', $code, $message );
					$code    = 0;
				}

				throw new \Exception( $message, $code );
			}
		} catch ( \Exception $error ) {
			$message = $error->getMessage();

			// Maybe include error code in message.
			$code = $error->getCode();

			if ( $code > 0 ) {
				$message = \sprintf( '%s: %s', $code, $message );
			}

			// Add note.
			$payment->add_note( $message );

			// Set payment status.
			$payment->set_status( PaymentStatus::FAILURE );
		}

		// Save payment.
		$payment->save();

		// Schedule payment status check.
		if ( $gateway->supports( 'payment_status_request' ) ) {
			StatusChecker::schedule_event( $payment );
		}

		// Throw/rethrow exception.
		if ( $error instanceof \Exception ) {
			throw $error;
		}

		return $payment;
	}

	/**
	 * Get pages.
	 *
	 * @return array
	 */
	public function get_pages() {
		$return = array();

		$pages = array(
			'completed' => __( 'Completed', 'pronamic_ideal' ),
			'cancel'    => __( 'Canceled', 'pronamic_ideal' ),
			'expired'   => __( 'Expired', 'pronamic_ideal' ),
			'error'     => __( 'Error', 'pronamic_ideal' ),
			'unknown'   => __( 'Unknown', 'pronamic_ideal' ),
		);

		foreach ( $pages as $key => $label ) {
			$id = sprintf( 'pronamic_pay_%s_page_id', $key );

			$return[ $id ] = $label;
		}

		return $return;
	}

	/**
	 * Payment redirect URL.
	 *
	 * @param string  $url     Redirect URL.
	 * @param Payment $payment Payment.
	 * @return string
	 */
	public function payment_redirect_url( $url, Payment $payment ) {
		$url = \apply_filters( 'pronamic_payment_redirect_url_' . $payment->get_source(), $url, $payment );

		return $url;
	}

	/**
	 * Is debug mode.
	 *
	 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/2.9.26/includes/misc-functions.php#L26-L38
	 * @return bool True if debug mode is enabled, false otherwise.
	 */
	public function is_debug_mode() {
		$value = \get_option( 'pronamic_pay_debug_mode', false );

		if ( PRONAMIC_PAY_DEBUG ) {
			$value = true;
		}

		return (bool) $value;
	}
}
