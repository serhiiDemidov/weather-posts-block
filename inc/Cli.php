<?php

namespace WeatherPostsBlock;

class Cli
{
	public function __construct()
	{
		\WP_CLI::add_command( 'wpb clear-cache', array( $this, 'clear_cache' ) );
	}

	public function clear_cache(): void
	{
		global $wpdb;

		$deleted = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_wpb_weather_%',
				'_transient_timeout_wpb_weather_%'
			)
		);

		\WP_CLI::success(
			sprintf(
				/* translators: %d: number of cache entries removed */
				__( 'Weather cache cleared. %d entries removed.', 'weather-posts-block' ),
				$deleted
			)
		);
	}
}
