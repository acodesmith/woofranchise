<?php


namespace WooFranchise;

use WooFranchise\Models\Franchise\Franchise;

class Bootstrap
{
    const DEP_ADMIN_SCRIPT  = 'woofranchise_admin_script';

    const DEP_ADMIN_STYLE   = 'woofranchise_admin_style';

    public function __construct()
    {
        add_action( 'plugins_loaded', [ $this, 'run' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
    }

    public function admin_scripts()
    {
        wp_enqueue_script( self::DEP_ADMIN_SCRIPT, WOOFRANCHISE_PLUGIN_URL . 'dist/admin.js', [ 'jquery' ], '0.0.1', true );
        wp_enqueue_style( self::DEP_ADMIN_STYLE, WOOFRANCHISE_PLUGIN_URL . 'dist/admin.css', [], '0.0.1' );
    }

    public function run()
    {
        if ( ! class_exists( 'WooCommerce' ) ) {
            //@todo add admin error
            return;
        }

        //Add Custom Post Types
        new Models\Franchise\FranchiseBootstrap();
        new Models\Franchise\FranchiseMetaBoxes();

        //Customize the WordPress Admin
        //new Admin\WC_WooFranchise_Settings();

        //Customize Core or Third Party Plugins
        new Modules\WooCommerce\FranchiseTaxRates();
    }
}