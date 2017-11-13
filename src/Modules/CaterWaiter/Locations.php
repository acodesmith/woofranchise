<?php


namespace WooFranchise\Modules\CaterWaiter;

use WooFranchise\Models\Franchise\FranchiseBootstrap;

class Locations
{
    public function __construct()
    {
        add_filter( 'cater_waiter_filter_location', [ $this, 'single' ] );
    }

    public function single($id)
    {
        if( empty( $id ) )
            return [ 'error' => 'missing id!' ];

        $query = new \WP_Query([
            'p'         => $id,
            'post_type' => FranchiseBootstrap::CPT_NAMESPACE
        ]);

        if( ! $query->have_posts() )
            return [ 'error' => "no records found! $id" ];

        $post_meta = array_intersect_key( get_post_meta( $id ), [
            'wf_pickup_time_start'  => 1,
            'wf_pickup_time_end'    => 1,
            'wf_delivery_time_start'=> 1,
            'wf_delivery_time_end'  => 1,
        ] );

        if( count( $post_meta ) !== 4 )
            return [ 'error' => 'missing post_meta start and end times for delivery and pickup!' ];

        $query->post->post_meta = [
            'pickup_time_start'     => $post_meta[ 'wf_pickup_time_start' ][0],
            'pickup_time_end'       => $post_meta[ 'wf_pickup_time_end' ][0],
            'delivery_time_start'   => $post_meta[ 'wf_delivery_time_start' ][0],
            'delivery_time_end'     => $post_meta[ 'wf_delivery_time_end' ][0],
        ];

        return $query->post;
    }
}