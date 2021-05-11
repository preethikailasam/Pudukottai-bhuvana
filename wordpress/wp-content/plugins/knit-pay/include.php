<?php

// Gateway.
require_once KNITPAY_DIR . 'gateways/Gateway.php';
// Cashfree
require_once KNITPAY_DIR . 'gateways/cashfree/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/cashfree/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/cashfree/src/Config.php';
require_once KNITPAY_DIR . 'gateways/cashfree/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/cashfree/src/Statuses.php';
require_once KNITPAY_DIR . 'gateways/cashfree/src/Listener.php';

// CCAvenue
require_once KNITPAY_DIR . 'gateways/ccavenue/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/ccavenue/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/ccavenue/src/Config.php';
require_once KNITPAY_DIR . 'gateways/ccavenue/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/ccavenue/src/Statuses.php';

// EBS
require_once KNITPAY_DIR . 'gateways/ebs/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/ebs/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/ebs/src/Config.php';
require_once KNITPAY_DIR . 'gateways/ebs/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/ebs/src/Statuses.php';

// Instamojo
require_once KNITPAY_DIR . 'gateways/instamojo/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/instamojo/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/instamojo/src/Config.php';
require_once KNITPAY_DIR . 'gateways/instamojo/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/instamojo/src/Statuses.php';

// EaseBuzz
require_once KNITPAY_DIR . 'gateways/easebuzz/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/easebuzz/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/easebuzz/src/Config.php';
require_once KNITPAY_DIR . 'gateways/easebuzz/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/easebuzz/src/Statuses.php';
require_once KNITPAY_DIR . 'gateways/easebuzz/src/Listener.php';

// Multi Gateway.
require_once KNITPAY_DIR . 'gateways/multi-gateway/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/multi-gateway/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/multi-gateway/src/Config.php';

// PayUMoney
require_once KNITPAY_DIR . 'gateways/payumoney/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/Config.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/Client.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/Statuses.php';
require_once KNITPAY_DIR . 'gateways/payumoney/src/Listener.php';

// Razorpay
require_once KNITPAY_DIR . 'gateways/razorpay/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/Config.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/Statuses.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/Listener.php';
require_once KNITPAY_DIR . 'gateways/razorpay/src/Subs.php';

// Sodexo
require_once KNITPAY_DIR . 'gateways/sodexo/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/sodexo/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/sodexo/src/Config.php';
require_once KNITPAY_DIR . 'gateways/sodexo/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/sodexo/src/Statuses.php';

// Stripe
require_once KNITPAY_DIR . 'gateways/stripe/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/stripe/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/stripe/src/Config.php';
require_once KNITPAY_DIR . 'gateways/stripe/src/PaymentMethods.php';
require_once KNITPAY_DIR . 'gateways/stripe/src/Statuses.php';
// Stripe Connect
require_once KNITPAY_DIR . 'gateways/stripe-connect/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/stripe-connect/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/stripe-connect/src/Config.php';

// Test Gateway.
require_once KNITPAY_DIR . 'gateways/test/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/test/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/test/src/Config.php';

// UPI QR
require_once KNITPAY_DIR . 'gateways/upi-qr/src/Integration.php';
require_once KNITPAY_DIR . 'gateways/upi-qr/src/Gateway.php';
require_once KNITPAY_DIR . 'gateways/upi-qr/src/Config.php';
require_once KNITPAY_DIR . 'gateways/upi-qr/src/PaymentMethods.php';


// Extensions.
// AWP Classifieds
require_once KNITPAY_DIR . 'extensions/awp-classifieds/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/awp-classifieds/src/AWPCPDependency.php';

// Bookly Pro
require_once KNITPAY_DIR . 'extensions/bookly-pro/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/bookly-pro/src/BooklyProDependency.php';

// Events Manager Pro
require_once KNITPAY_DIR . 'extensions/events-manager-pro/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/events-manager-pro/src/EventsManagerProDependency.php';

// LearnDash
require_once KNITPAY_DIR . 'extensions/learndash/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/learndash/src/LearnDashDependency.php';

// LearnPress
require_once KNITPAY_DIR . 'extensions/learnpress/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/learnpress/src/LearnPressDependency.php';

// LifterLMS
require_once KNITPAY_DIR . 'extensions/lifterlms/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/lifterlms/src/LifterLMSDependency.php';

// MotoPress Hotel Booking.
require_once KNITPAY_DIR . 'extensions/motopress-hotel-booking/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/motopress-hotel-booking/src/MotoPressHotelBookingDependency.php';

// myCRED - buyCRED.
require_once KNITPAY_DIR . 'extensions/mycred-buycred/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/mycred-buycred/src/MyCredDependency.php';

// Paid Memberships Pro
require_once KNITPAY_DIR . 'extensions/paid-memberships-pro/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/paid-memberships-pro/src/PaidMembershipsProDependency.php';

// Registrations for The Events Calendar Pro.
require_once KNITPAY_DIR . 'extensions/registrations-for-the-events-calendar-pro/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/registrations-for-the-events-calendar-pro/src/Dependency.php';

// RestroPress
require_once KNITPAY_DIR . 'extensions/restropress/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/restropress/src/RestroPressDependency.php';

// Tour Master
 require_once KNITPAY_DIR . 'extensions/tourmaster/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/tourmaster/src/TourMasterDependency.php';

// WP Adverts
require_once KNITPAY_DIR . 'extensions/wpadverts/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/wpadverts/src/WPAdvertsDependency.php';

// WP Travel Engine
require_once KNITPAY_DIR . 'extensions/wp-travel-engine/src/Extension.php';
require_once KNITPAY_DIR . 'extensions/wp-travel-engine/src/WPTravelEngineDependency.php';

// Add Knit Pay Deactivate Confirmation Box on Plugin Page
require_once 'includes/plugin-deactivate-confirmation.php';

if ( ! function_exists( 'ppp' ) ) {
	function ppp( $a = '' ) {
		print_r( $a );
	}
}

if ( ! function_exists( 'ddd' ) ) {
	function ddd( $a = '' ) {
		echo nl2br( $a . "\r\n\n\n\n\n\n\n\n\n" );
		debug_print_backtrace();
		die( $a );
	}
}
