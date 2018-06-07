<?php


namespace WooFranchise\Modules\WooCommerce;

/**
 * Class FranchiseOrderEmails
 * @package WooFranchise\Modules\WooCommerce
 */
class FranchiseOrderEmails {

	public function __construct() {

		add_filter( 'woocommerce_email_headers', [ $this, 'store_email' ], 10000, 3 );
		add_filter( 'woocommerce_email_headers', [ $this, 'district_manager_email' ], 100000, 3 );
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
	 * @param $headers
	 * @param $email
	 *
	 * @return mixed|string
	 */
	public function add_bcc($headers, $email) {

		$pos = strpos( $headers, 'Bcc: ' );

		if( false === $pos )
			$headers .= "Bcc: $email\r\n";
		else{
			$headers = str_replace( 'Bcc: ', "Bcc: $email, ", $headers );
		}

		return $headers;
	}

	/**
	 * Valid the order and the order location.
	 * Check for the post meta value `wf_store_email`.
	 * Add the email address as a BCC.
	 *
	 * @param $headers
	 * @param $email_id
	 * @param \WC_Order $order
	 *
	 * @return string
	 */
	public function store_email( $headers, $email_id, $order ) {

		$order_id = $order->get_id();
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return $headers;
		}

		if ( 'completed' !== $order->get_status() ) {
			return $headers;
		}

		$wf_store_email = self::valid_order_location_post_meta($order_id, 'wf_store_email');

		// Add Store Email as BCC
		if( ! empty( $wf_store_email ) )
			$headers = self::add_bcc( $headers, $wf_store_email );

		return $headers;
	}

	/**
	 * Valid the order and the order location.
	 * Check for the post meta value `wf_district_manager_email`.
	 * Add the email address as a BCC.
	 *
	 * @param $headers
	 * @param $email_id
	 * @param \WC_Order $order
	 *
	 * @return mixed|string
	 */
	public function district_manager_email( $headers, $email_id, $order ) {

		$order_id = $order->get_id();
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return $headers;
		}

		if ( 'completed' !== $order->get_status() ) {
			return $headers;
		}

		$wf_district_manager_email = self::valid_order_location_post_meta($order_id, 'wf_district_manager_email');

		// Add District Manager Email as BCC
		if( ! empty( $wf_district_manager_email ) )
			$headers = self::add_bcc( $headers, $wf_district_manager_email );

		return $headers;
	}
}