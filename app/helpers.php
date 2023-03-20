<?php

 /**
     * Gets coordinates by address.
     *
     * @param string $address
     * @return array
     */

if (! function_exists('getCoordinateByAddress')) {
    function getCoordinateByAddress($address)
    {
        $response =  findAddressCoordinates($address);
        $response = json_decode($response);
        $latitude = $response->geometry->location->lat;
        $longitude = $response->geometry->location->lng;
        
        return[
           'latitude'=> $latitude,
           'longitude'=>$longitude
        ];

    }
}