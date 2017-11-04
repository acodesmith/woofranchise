<?php


namespace WooFranchise\Models\Franchise;


use WooFranchise\Core\GeoLocation;

class FranchiseBootstrap
{
    const CPT_NAMESPACE = 'franchise';

    const META_KEY_LAT = 'wf_franchise_lat';

    const META_KEY_LNG = 'wf_franchise_lng';

    public function __construct()
    {
        add_filter( 'init', [ $this, 'run' ] );
        add_filter( 'save_post', [ $this, 'geolocation' ] );
    }

    public function run()
    {
        $this->register_post_type();
    }

    public function register_post_type()
    {
        $labels = array(
            'name' 				=> _x( 'Franchises', 'post type general name', 'WooFranchise' ),
            'singular_name'		=> _x( 'Franchise', 'post type singular name', 'WooFranchise' ),
            'add_new' 			=> _x( 'Add New', 'Add New', 'WooFranchise'),
            'add_new_item' 		=> __( 'Add New Franchise ', 'WooFranchise'),
            'edit_item' 		=> __( 'Edit Franchise', 'WooFranchise'),
            'new_item' 			=> __( 'New Franchise ', 'WooFranchise'),
            'view_item' 		=> __( 'View Franchise', 'WooFranchise'),
            'search_items' 		=> __( 'Search Franchises', 'WooFranchise'),
            'not_found' 		=> __( 'No Franchises found', 'WooFranchise' ),
            'not_found_in_trash'=> __( 'No Franchises found in Trash', 'WooFranchise' ),
            'parent_item_colon' => ''
        );

        $post_type_args = array(
            'labels' 			=> $labels,
            'singular_label' 	=> __('Franchise', 'WooFranchise'),
            'public' 			=> true,
            'show_ui' 			=> true,
            'publicly_queryable'=> true,
            'query_var'			=> true,
            'capability_type' 	=> 'post',
            'has_archive' 		=> false,
            'hierarchical' 		=> true,
            'rewrite' 			=> ['slug' => 'franchise', 'with_front' => false ],
            'supports' 			=> ['title','thumbnail',],
            'menu_position' 	=> 20,
            'menu_icon' 		=> 'dashicons-admin-multisite',
            'taxonomies'		=> []
        );

        register_post_type( self::CPT_NAMESPACE, $post_type_args );
    }

    public function geolocation($post_id)
    {
        $post = get_post( $post_id );

        if( $post->post_type !== self::CPT_NAMESPACE )
            return;

        $post_meta = get_post_meta( $post_id );

        $api_key = apply_filters( 'WooFranchise/Google/api-key', \WC_Admin_Settings::get_option( 'wc_settings_woofranchise_google_maps_api_key' ) );

        $geolocation_required_values = array_intersect_key( $post_meta, [
            'wf_address_one'    => 1,
            'wf_address_two'    => 1,
            'wf_city'           => 1,
            'wf_state'          => 1,
            'wf_zip'            => 1,
        ] );

        if( count( $geolocation_required_values ) == 5 && ! empty( $api_key ) ) {

            if( $latlong = GeoLocation::get_lat_lng(
                $geolocation_required_values['wf_address_one'][0] . " " . $geolocation_required_values['wf_address_two'][0],
                $geolocation_required_values['wf_city'][0],
                $geolocation_required_values['wf_state'][0],
                $geolocation_required_values['wf_zip'][0],
                $api_key
            ) ) {

                $latlong = explode( ',', $latlong );

                update_post_meta( $post_id, self::META_KEY_LAT, $latlong[0] );
                update_post_meta( $post_id, self::META_KEY_LNG, $latlong[1] );
            }
        }
    }
}
