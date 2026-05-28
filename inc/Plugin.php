<?php

namespace WeatherPostsBlock;

class Plugin
{
    public static function init(): void
    {
        new Admin();
        new Block();
        new Ajax();

        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            new Cli();
        }
    }
}
