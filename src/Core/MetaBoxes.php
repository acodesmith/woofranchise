<?php


namespace WooFranchise\Core;

class MetaBoxes
{
    const NONCE_KEY = 'wf_nonce_key';

    const NONCE_NAME = 'wf_meta_box_flag';

    public function __construct()
    {
        add_filter( 'add_meta_boxes', [ $this, 'add' ], 1 );
        add_filter( 'save_post', [ $this, 'save' ], 10, 1 );
    }

    public function boxes()
    {
        return [];
    }

    public function field_keys()
    {
        $box_fields = array_map( function( $box ) {
            return $box['fields'];
        }, $this->boxes() );

        $fields = [];

        foreach( $box_fields as $box_field ) {
            foreach( $box_field as $field )
            $fields[ $field['meta_key'] ] = $field;
        }

        return $fields;
    }

    public function add()
    {
        foreach( $this->boxes() as $box ) {

            add_meta_box(
                $box['id'],
                $box['name'],
                $box['callback'],
                $box['screen'],
                'advanced',
                ! empty( $box['priority'] ) ? $box['priority'] : 'default'
            );
        }
    }

    public function render($id = null)
    {
        global $post;

        $boxes = array_filter( $this->boxes(), function($box) use ($id) {
            return $box['id'] === $id;
        });

        if( ! empty( $boxes ) ) {

            $post_meta = get_post_meta( $post->ID );

            foreach ( array_shift( $boxes )['fields'] as $field )
                View::admin( 'meta_box_field', [
                    'field'     => $field,
                    'post_meta' => $post_meta,
                ] );

            $nonce = wp_create_nonce( self::NONCE_KEY );
            echo "<input name='". self::NONCE_NAME ."' type='hidden' value='$nonce'>";
        }
    }

    public function save($post_id)
    {
        if( ! empty( $_REQUEST[ self::NONCE_NAME ] )
            && wp_verify_nonce( $_REQUEST[ self::NONCE_NAME ], self::NONCE_KEY ) ) {

            $field_keys = $this->field_keys();

            $field_form_keys = array_filter( array_keys( $_REQUEST ), function( $key ) use ( $field_keys ) {
                return in_array( $key, array_keys( $field_keys ) );
            });

            foreach ( $field_form_keys as $field_form_key ) {
                update_post_meta( $post_id, $field_form_key, $_REQUEST[ $field_form_key ] );
            }
        }
    }
}