<?php

namespace WeatherPostsBlock;

class WeatherApi {

    private string $api_key;

    public function __construct() {
        $this->api_key = get_option( 'wpb_openweather_api_key', '' );
    }

    public function get_weather( float $lat, float $lon ): array {
        // TODO: implement
        return [];
    }
}
