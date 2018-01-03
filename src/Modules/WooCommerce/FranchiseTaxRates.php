<?php


namespace WooFranchise\Modules\WooCommerce;


use CaterWaiter\Admin\Order;
use WooFranchise\Models\Franchise\FranchiseBootstrap;

class FranchiseTaxRates
{
    public function __construct()
    {
        add_filter( 'save_post', [ $this, 'sync_tax_rate' ], 1000, 1 );
        add_filter( 'untrash_post', [ $this, 'sync_tax_rate' ], 1000, 1 );
	    add_action( 'pmxi_saved_post', [ $this, 'sync_tax_rate' ], 1000, 1 );
        add_filter( 'wp_trash_post', [ $this, 'delete_synced_tax_rate' ], 10, 1 );

        add_filter( 'woocommerce_product_get_tax_class', [ $this, 'add_tax' ], 10, 1 );
        add_filter( 'woocommerce_product_variation_get_tax_class', [ $this, 'add_tax' ], 10, 1 );
        add_filter( 'woocommerce_countries_ex_tax_or_vat', [ $this, 'tax_label' ], 10, 1 );
        add_filter( 'woocommerce_get_sections_tax', [ $this, 'hide_tax_classes' ], 10, 1 );

        add_filter( 'wp_footer', [ $this, 'tax_label' ] );

        //add_action( 'admin_init', [ $this, 'debug' ] );
    }

    public function debug() {

    	self::sync_tax_rate( $_GET['post'] );
    }

    /**
     * @param $class
     * @return string
     */
    public function add_tax($class)
    {
    	if( \WC()->session ) {
		    $location_id = \WC()->session->get( Order::TAX_LOCATION_ID );

		    if ( ! empty( $location_id ) ) {
			    if ( $location = get_post( $location_id ) ) {
				    return $location->post_name;
			    }
		    }
	    }

        return $class;
    }

    /**
     * Hide our custom tax rate classes on the WooCommerce tax setting tab.
     *
     * @param $sections
     * @return array
     */
    public function hide_tax_classes($sections)
    {
        $query = new \WP_Query([
            'post_type'     => FranchiseBootstrap::CPT_NAMESPACE,
            'post_status'   => ['publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'],
            'posts_per_page'=> -1
        ]);

        if( $query->have_posts() && ! WP_DEBUG )
            return array_diff_key( $sections, array_flip( array_map( function($post) {
                /** @var \WP_Post $post */
                return $post->post_name;
            }, $query->posts ) ) );

        return $sections;
    }

    /**
     * Hack to not display the tax rate name, but just the word tax
     * @todo make better
     */
    public function tax_label(  )
    {
        $label = __("Tax", 'woofranchise');

        echo "<style>
        table.shop_table tr.tax-rate th {
            font-size: 0;
        }
        table.shop_table tr.tax-rate th:before {
            content: '$label';
            display: block;
            font-size: 1rem;
            padding: 1.25rem;
        }
        </style>";
    }

    /**
     * @param $post_id
     */
    public function delete_synced_tax_rate($post_id)
    {
	    $post = get_post( $post_id );

	    // You shall not pass!
	    if ( $post->post_type !== FranchiseBootstrap::CPT_NAMESPACE ) {
		    return;
	    }

	    // Delete the item from site option woocommerce_tax_classes
	    $woocommerce_tax_classes = explode( PHP_EOL, get_option( 'woocommerce_tax_classes' ) );

	    if ( $key = array_search( $post->post_name, $woocommerce_tax_classes ) ) {
		    unset( $woocommerce_tax_classes[ $key ] );

		    update_option( 'woocommerce_tax_classes', implode( "\n", $woocommerce_tax_classes ) );
	    }

	    $tax_rate_id = self::get_tax_rate_by_name( $post->post_name );

	    // Delete tax rate if no stored value and existing record.
	    if ( ! empty( $tax_rate_id ) ) {
		    \WC_Tax::_delete_tax_rate( $tax_rate_id );
	    }
    }

    /**
     * Insert tax class in the WooCommerce tax tables.
     * Update class if it is already set.
     *
     * @param $post_id
     */
    public function sync_tax_rate($post_id)
    {
    	/** @var \WP_Post $post */
        $post = get_post( $post_id );

        // You shall not pass!
        if( empty( $post ) || ( isset( $post->post_title ) && $post->post_title == 'Auto Draft' ) || $post->post_status == 'trash' )
            return;

        // You shall not pass!
        if( $post->post_type !== FranchiseBootstrap::CPT_NAMESPACE )
            return;

        $post_meta      = get_post_meta( $post_id );
        $tax_rate_id    = self::get_tax_rate_by_name( $post->post_name );

        // Tax Rate Classes
        $tax_rate_classes = explode(PHP_EOL, get_option( 'woocommerce_tax_classes' ) );

	    // Extract Post Meta
	    $tax_rate_meta_data = array_intersect_key( $post_meta,
		    [
			    'wf_country'        => 1,
			    'wf_state'          => 1,
			    'wf_tax_rate'       => 1,
			    'wf_zip'            => 1,
			    'wf_city'           => 1,
		    ]
	    );

	    $tax_rate_meta_data = array_map( 'array_shift', $tax_rate_meta_data );

	    // You shall not pass!
	    if( empty( $tax_rate_meta_data['wf_tax_rate'] ) )
		    return;

        if( ! in_array( $post->post_name, $tax_rate_classes ) ) {
            $tax_rate_classes[] = $post->post_name;

            update_option( 'woocommerce_tax_classes', implode( "\n", $tax_rate_classes ) );
        }

        // Build array to sync to DB
        $tax_rate_data = [
            'tax_rate_country'  => ! empty( $tax_rate_meta_data['wf_country'] ) ? $tax_rate_meta_data['wf_country'] : 'US',
            'tax_rate_state'    => $tax_rate_meta_data['wf_state'],
            'tax_rate'          => wc_format_decimal( $tax_rate_meta_data['wf_tax_rate'] ),
            'tax_rate_name'     => $post->post_name,
            'tax_rate_priority' => 1,
            'tax_rate_compound' => 0,
            'tax_rate_shipping' => 1,
            'tax_rate_order'    => 0,
            'tax_rate_class'    => $post->post_name
        ];

        // Create new tax rate or update
        if( empty( $tax_rate_id ) ) {
            $tax_rate_id = \WC_Tax::_insert_tax_rate( $tax_rate_data );
        } else {
            \WC_Tax::_update_tax_rate( $tax_rate_id, $tax_rate_data );
        }

        // Sync Tax Rate Location Data, zip and city.
        if ( ! empty( $tax_rate_meta_data['wf_zip'] ) ) {
            $postcode = array_map( 'wc_clean', [ $tax_rate_meta_data['wf_zip'] ] );
            $postcode = array_map( 'wc_normalize_postcode', $postcode );
            \WC_Tax::_update_tax_rate_postcodes( $tax_rate_id, implode(';', $postcode) );
        }

        if ( ! empty( $tax_rate_meta_data['wf_city'] ) ) {
        	$city = array_map( 'wc_clean', [ $tax_rate_meta_data['wf_city'] ] );
            \WC_Tax::_update_tax_rate_cities( $tax_rate_id, reset($city) );
        }
    }

    /**
     * @param $tax_rate_name
     * @return null|string
     */
    public static function get_tax_rate_by_name($tax_rate_name)
    {
        global $wpdb;

        return $wpdb->get_var( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate_name = '%s'", $tax_rate_name ) );
    }
}