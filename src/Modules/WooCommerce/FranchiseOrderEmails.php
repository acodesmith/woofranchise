<?php


namespace WooFranchise\Modules\WooCommerce;

/**
 * Class FranchiseOrderEmails
 * @package WooFranchise\Modules\WooCommerce
 */
class FranchiseOrderEmails {

	public function __construct() {

		add_action( 'woocommerce_email_headers', [ $this, 'store_email' ], 100, 3 );
		add_action( 'woocommerce_email_headers', [ $this, 'district_manager_email' ], 100, 3 );

		//add_action('wp_mail', [ $this, 'debug_headers' ],100, 1);
	}

	public function debug_headers($args) {
		echo "<pre>".print_r($args, 1)."</pre>"; die;
	}

	/**
	 * @param $order_id
	 * @param null $post_met_key
	 *
	 * @return bool|mixed
	 */
	public function valid_order_location_post_meta($order_id, $post_met_key = null) {

		// Location ID associated with the Order
		$order_location_id = get_post_meta( $order_id, 'order_location_id', true );
		if ( ! $order_location_id )
			return false;

		// Confirm it's a valid post id
		$location = get_post( $order_location_id );
		if ( ! $location )
			return false;

		// Check for post meta value
		return get_post_meta( $order_location_id, $post_met_key, true );
	}

	/**
	 * Could not get Bcc to be delivered. Testing with Cc: for now...
	 *
	 * @param $headers
	 * @param $email
	 *
	 * @return mixed|string
	 */
	public function add_bcc($headers, $email, $name) {

		$headers[] = "Cc: $name <$email>";

		return $headers;
	}

	/**
	 * Valid the order and the order location.
	 * Check for the post meta value `wf_store_email`.
	 * Add the email address as a BCC.
	 *
	 * @param $headers
	 * @param $type
	 * @param \WC_Order $order
	 *
	 * @return string
	 */
	public function store_email( $headers, $type, $order ) {

		$wf_store_email = self::valid_order_location_post_meta($order->get_id(), 'wf_store_email');

		// Add Store Email as BCC
		if( ! empty( $wf_store_email ) )
			$headers = self::add_bcc( $headers, $wf_store_email, "Arby's Store Email" );

		return $headers;
	}

	/**
	 * Valid the order and the order location.
	 * Check for the post meta value `wf_district_manager_email`.
	 * Add the email address as a BCC.
	 *
	 * @param $headers
	 * @param $type
	 * @param \WC_Order $order
	 *
	 * @return mixed|string
	 */
	public function district_manager_email( $headers, $type, $order ) {

		if(is_string($headers))
			$headers = array_filter( explode(PHP_EOL, $headers) );

		$wf_district_manager_email = self::valid_order_location_post_meta($order->get_id(), 'wf_district_manager_email');

		// Add District Manager Email as BCC
		if( ! empty( $wf_district_manager_email ) )
			$headers = self::add_bcc( $headers, $wf_district_manager_email, "Arby's District Manager" );

		return $headers;
	}
}