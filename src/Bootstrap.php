<?php


namespace WooFranchise;

class Bootstrap
{
    const DEP_ADMIN_SCRIPT  = 'woofranchise_admin_script';

    const DEP_ADMIN_STYLE   = 'woofranchise_admin_style';

    public function __construct()
    {

    }

    public function admin_scripts()
    {
        wp_enqueue_script( self::DEP_ADMIN_SCRIPT, WOOFRANCHISE_PLUGIN_URL . '/dist/admin.js', [ 'jquery' ], '0.0.1', true );
        wp_enqueue_style( self::DEP_ADMIN_STYLE, WOOFRANCHISE_PLUGIN_URL . '/dist/admin.css', [], '0.0.1' );
    }
}