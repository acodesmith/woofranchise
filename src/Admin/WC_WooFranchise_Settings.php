<?php


namespace WooFranchise\Admin;

use WooFranchise\Core\View;

/**
 * Class WC_ProductDataTabs
 * @package CaterWaiter\Admin
 */
class WC_WooFranchise_Settings
{
    public function __construct()
    {
        //add_filter( 'admin_init', [ $this, 'run' ] );
        add_filter( 'admin_menu', [ $this, 'menu' ], 40 );
    }

    public function menu()
    {
        add_submenu_page( 'woocommerce', __( 'Franchise Settings', 'woofranchise' ), __( 'Franchises', 'woofranchise' ), 'manage_options', 'franchise/settings', [ $this, 'view' ] );
    }

    public function view()
    {
        View::admin( 'settings' );
    }
}