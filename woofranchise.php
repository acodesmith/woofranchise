<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/acodesmith/cater-waiter
 * @since             0.0.1
 * @package           ACODESMITH\cater-waiter
 *
 * @wordpress-plugin
 * Plugin Name:       Cater Waiter
 * Plugin URI:        https://github.com/acodesmith/cater-waiter
 * Description:       WooCommerce for Online Catering Orders
 * Version:           0.0.1
 * Author:            ACODESMITH
 * Author URI:        https://acodesmith.com/
 * Text Domain:       acs
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Composer Autoload
 */
require ('vendor/autoload.php');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_woofranchise()
{
    if( ! defined( 'WOOFRANCHISE_PLUGIN_URL' ) ) define( 'WOOFRANCHISE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    if( ! defined( 'WOOFRANCHISE_PLUGIN_PATH' ) ) define( 'WOOFRANCHISE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

    new WooFranchise\Bootstrap();
}

run_woofranchise();