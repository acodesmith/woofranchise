<?php


namespace WooFranchise\Core;

/**
 * Class View
 * @package CaterWaiter\Core
 */
class View
{
    public static function render($name, $data = [])
    {
        $full_path = WOOFRANCHISE_PLUGIN_PATH . "/resources/views/$name.php";

        if( file_exists( $full_path ) ) {

            extract( $data );

            include WOOFRANCHISE_PLUGIN_PATH . "/resources/views/$name.php";
        }else
            error_log( "Trying to render missing file: $full_path" );
    }

    public static function admin($name, $data = [])
    {
        self::render( "admin/$name", $data );
    }

    public static function front_end($name, $data = [])
    {
        self::render( "front_end/$name", $data );
    }
}