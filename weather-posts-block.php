<?php

/**
 * Plugin Name: Weather Posts Block
 * Description: Gutenberg block with posts and weather widget
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Text Domain: weather-posts-block
 */

if (! defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

WeatherPostsBlock\Plugin::init();
