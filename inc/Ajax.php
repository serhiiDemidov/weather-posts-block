<?php

namespace WeatherPostsBlock;

class Ajax {

    public function __construct() {
        add_action( 'wp_ajax_wpb_get_weather', [ $this, 'handle' ] );
        add_action( 'wp_ajax_nopriv_wpb_get_weather', [ $this, 'handle' ] );
    }

    public function handle(): void {
        // TODO: implement
        wp_send_json_error( 'Not implemented', 501 );
    }
}
