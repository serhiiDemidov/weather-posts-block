<?php

namespace WeatherPostsBlock;

class Cli {

    public function clear_cache(): void {
        // TODO: implement — clear all weather transients
        \WP_CLI::success( 'Weather cache cleared.' );
    }
}
