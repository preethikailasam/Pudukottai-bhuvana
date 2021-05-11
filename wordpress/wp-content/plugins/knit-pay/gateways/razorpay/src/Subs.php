<?php

namespace Razorpay\Api;

use Razorpay\Api\Subscription;

class Subs extends Subscription {

	protected function getEntityUrl() {
		return 'subscriptions/';
	}

	public function get_subscription_invoices( $subscription_id ) {
		$invoices = $this->request( 'GET', 'invoices?subscription_id=' . $subscription_id );
		return $invoices;
	}
}
