<?php


namespace WooFranchise\Models\Franchise;


class FranchiseBootstrap
{
    const CPT_NAMESPACE = 'franchise';

    public function __construct()
    {
        add_filter( 'init', [ $this, 'run' ] );
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
}