<?php
/**
 * Plugin Name:       Weather Posts Block
 * Plugin URI:        https://github.com/serhiiDemidov/weather-posts-block
 * Description:       Gutenberg block displaying posts with live weather data.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Serhii Demidov
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       weather-posts-block
 * Domain Path:       /languages
 *
 * @package WeatherPostsBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

WeatherPostsBlock\Plugin::init();
