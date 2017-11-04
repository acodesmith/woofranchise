<?php


namespace WooFranchise\Core;


class GeoLocation
{
    /**
     * @param $address
     * @param $city
     * @param $state
     * @param $zip
     * @param $api_key
     * @return bool|string
     */
    public static function get_lat_lng($address, $city, $state, $zip, $api_key) {

        $api_base   = 'https://maps.googleapis.com/maps/api/geocode/json';

        if ($api_key) {

            if (
                ! empty( $address ) &&
                ! empty( $city ) &&
                ! empty( $state )
            ) {
                // Combine the address...
                $full_address = implode(',', [ $address, $city, $state, $zip ]);

                // Replace any space with a '+' in the remainder of the string
                $full_address = str_replace(' ', '+', $full_address);

                // Build the request and send with wp_remote_get
                $api_request_string = "{$api_base}?address={$full_address}&key={$api_key}";
                $api_request = wp_remote_get($api_request_string);

                // Get the response
                $api_response = json_decode(wp_remote_retrieve_body($api_request));

                // If we have the lat & lng from Google Maps
                if (
                    ! empty( $api_response->results )
                    && ! empty( $api_response->results[0]->geometry )
                    && $api_response->results[0]->geometry->location
                ) {

                    $lat = $api_response->results[0]->geometry->location->lat;
                    $lng = $api_response->results[0]->geometry->location->lng;

                    if ($lat && $lng) {
                        return "{$lat},{$lng}";
                    }
                }
            }
        }

        return false;
    }
}