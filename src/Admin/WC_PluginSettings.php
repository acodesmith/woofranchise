<?php


namespace WooFranchise\Admin;

/**
 * Class WC_PluginSettings
 * @package CaterWaiter\Admin
 */
class WC_PluginSettings
{
    const FILTER_SETTINGS = 'wc_settings_tab_woofranchise_settings';

    /**
     * WC_PluginSettings constructor.
     */
    public function __construct()
    {
        add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_section' ], 100, 1 );
        add_filter( 'woocommerce_settings_tabs_wc_woofranchise', [ $this, 'settings' ] );
        add_action( 'woocommerce_update_options_wc_woofranchise', [ $this, 'update_settings' ] );
    }

    /**
     * Add the Cater Waiter tab to the WooCommerce settings page.
     *
     * @param array $sections
     * @return array
     */
    public function add_section($sections)
    {
        $sections['wc_woofranchise'] = __( 'WooFranchise', 'woofranchise' );

        return $sections;
    }

    /**
     * Add settings to the Cater Waiter custome settings page.
     *
     * @uses woocommerce_admin_fields();
     * @uses self::get_settings();
     */
    public function settings()
    {
        woocommerce_admin_fields( self::get_settings() );
    }

    /**
     * Save the Cater Waiter custom settings.
     *
     * @uses woocommerce_update_options();
     * @uses self::get_settings();
     */
    public function update_settings()
    {
        woocommerce_update_options( self::get_settings() );
    }

    /**
     * Array of settings based on the woocommerce_admin_fields function requirements.
     *
     * @return mixed|void
     */
    public static function get_settings()
    {
        $settings = array(
            'section_online_ordering_settings' => array(
                'name'     => __( 'WooFranchise Settings', 'woofranchise' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_woofranchise_section_title'
            ),
            'google_maps_api' => array(
                'name' => __( 'Google Maps API Key', 'woofranchise' ),
                'type' => 'password',
                'desc' => __( 'Need to build lat/long values for radius based searching.', 'woofranchise' ),
                'id'   => 'wc_settings_woofranchise_google_maps_api_key',
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_woofranchise_section_end'
            ),
        );

        return apply_filters( self::FILTER_SETTINGS, $settings );
    }
}