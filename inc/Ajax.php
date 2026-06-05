<?php

namespace WeatherPostsBlock;

class Ajax
{
	public function __construct()
	{
		add_action( 'wp_ajax_wpb_get_weather', array( $this, 'handle' ) );
		add_action( 'wp_ajax_nopriv_wpb_get_weather', array( $this, 'handle' ) );
	}

	public function handle(): void
	{
		check_ajax_referer( 'wpb_nonce', 'nonce' );

		$lat = isset( $_POST['lat'] ) ? (float) sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : 0.0;
		$lon = isset( $_POST['lon'] ) ? (float) sanitize_text_field( wp_unslash( $_POST['lon'] ) ) : 0.0;

		if ( $lat < -90 || $lat > 90 || $lon < -180 || $lon > 180 ) {
			wp_send_json_error( __( 'Invalid coordinates', 'weather-posts-block' ), 400 );
		}

		$api     = new WeatherApi();
		$weather = $api->get_weather( $lat, $lon );

		if ( empty( $weather ) ) {
			wp_send_json_error( __( 'Could not fetch weather data', 'weather-posts-block' ), 503 );
		}

		wp_send_json_success( $weather );
	}
}
