<?php


namespace WooFranchise\Models\Franchise;


use WooFranchise\Core\MetaBoxes;

/**
■ Address
■ City
■ State
■ Zip
■ Unit   Number
■ Store   Name
■ Phone   Number
■ Store   email   address
■ District   Manager   email   address
■ Tax   rate   for   the   store
 */
class FranchiseMetaBoxes extends MetaBoxes
{
    const META_KEY_PREFIX = 'wf_';

    const FILTER_NAMESPACE = 'woofranchise_meta_boxes_filter';

    public function boxes()
    {
        return apply_filters( self::FILTER_NAMESPACE, [
            [
                'id'        => self::META_KEY_PREFIX . 'contact_info',
                'name'      => __( 'Contact Info', 'woofranchise' ),
                'screen'    => FranchiseBootstrap::CPT_NAMESPACE,
                'priority'  => 'high',
                'callback'  => function(){
                    $this->render( self::META_KEY_PREFIX . 'contact_info' );
                },
                'fields'    => [
                    [
                        'id'        => self::META_KEY_PREFIX . 'phone_number',
                        'meta_key'  => self::META_KEY_PREFIX . 'phone_number',
                        'name'      => 'Phone Number',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'store_email',
                        'meta_key'  => self::META_KEY_PREFIX . 'store_email',
                        'name'      => 'Store Email Address',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'district_manager_email',
                        'meta_key'  => self::META_KEY_PREFIX . 'district_manager_email',
                        'name'      => 'District Manager Email',
                        'type'      => 'text',
                    ],
                ],
            ],
            [
                'id'        => self::META_KEY_PREFIX . 'franchise_info',
                'name'      => __( 'Franchise Info', 'woofranchise' ),
                'screen'    => FranchiseBootstrap::CPT_NAMESPACE,
                'priority'  => 'high',
                'callback'  => function(){
                    $this->render( self::META_KEY_PREFIX . 'franchise_info' );
                },
                'fields'    => [
                    [
                        'id'        => self::META_KEY_PREFIX . 'unit_number',
                        'meta_key'  => self::META_KEY_PREFIX . 'unit_number',
                        'name'      => 'Unity Number',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'tax_rate',
                        'meta_key'  => self::META_KEY_PREFIX . 'tax_rate',
                        'name'      => 'Tax Rate',
                        'type'      => 'number',
                        'attributes'=> [
                            'step'  => '0.01',
                            'min'   => 0,
                            'max'   => 100,
                        ]
                    ],
                ],
            ],
            [
                'id'        => self::META_KEY_PREFIX . 'address',
                'name'      => __( 'Address', 'woofranchise' ),
                'screen'    => FranchiseBootstrap::CPT_NAMESPACE,
                'priority'  => 'high',
                'callback'  => function(){
                    $this->render( self::META_KEY_PREFIX . 'address' );
                },
                'fields'    => [
                    [
                        'id'        => self::META_KEY_PREFIX . 'address_one',
                        'meta_key'  => self::META_KEY_PREFIX . 'address_one',
                        'name'      => 'Address Line One',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'address_two',
                        'meta_key'  => self::META_KEY_PREFIX . 'address_two',
                        'name'      => 'Address Line Two',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'city',
                        'meta_key'  => self::META_KEY_PREFIX . 'city',
                        'name'      => 'City',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'state',
                        'meta_key'  => self::META_KEY_PREFIX . 'state',
                        'name'      => 'State (Two Letter Abbreviation)',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'zip',
                        'meta_key'  => self::META_KEY_PREFIX . 'zip',
                        'name'      => 'Zip',
                        'type'      => 'text',
                    ],[
                        'id'        => self::META_KEY_PREFIX . 'country',
                        'meta_key'  => self::META_KEY_PREFIX . 'country',
                        'name'      => 'Country (Two Letter Abbreviation)',
                        'type'      => 'text',
                    ],
                ],
            ],
            [
            'id'        => self::META_KEY_PREFIX . 'franchise_hours',
            'name'      => __( 'Franchise Hours', 'woofranchise' ),
            'screen'    => FranchiseBootstrap::CPT_NAMESPACE,
            'priority'  => 'high',
            'callback'  => function(){
                $this->render( self::META_KEY_PREFIX . 'franchise_hours' );
            },
            'fields'    => [
                [
                    'id'        => self::META_KEY_PREFIX . 'pickup_time_start',
                    'meta_key'  => self::META_KEY_PREFIX . 'pickup_time_start',
                    'name'      => 'Start of Delivery Time',
                    'type'      => 'time',
                    'attributes'=> [
                        'pattern'=>"[0-9]{2}:[0-9]{2}",
                    ]
                ],[
                    'id'        => self::META_KEY_PREFIX . 'pickup_time_end',
                    'meta_key'  => self::META_KEY_PREFIX . 'pickup_time_end',
                    'name'      => 'End of Delivery Time',
                    'type'      => 'time',
                    'attributes'=> [
                        'pattern'=>"[0-9]{2}:[0-9]{2}",
                    ]
                ],
                [
                    'id'        => self::META_KEY_PREFIX . 'delivery_time_start',
                    'meta_key'  => self::META_KEY_PREFIX . 'delivery_time_start',
                    'name'      => 'Start of Delivery Time',
                    'type'      => 'time',
                    'attributes'=> [
                        'pattern'=>"[0-9]{2}:[0-9]{2}",
                    ]
                ],[
                    'id'        => self::META_KEY_PREFIX . 'delivery_time_end',
                    'meta_key'  => self::META_KEY_PREFIX . 'delivery_time_end',
                    'name'      => 'End of Delivery Time',
                    'type'      => 'time',
                    'attributes'=> [
                        'pattern'=>"[0-9]{2}:[0-9]{2}",
                    ]
                ],
            ],
        ],
        ] );
    }
}