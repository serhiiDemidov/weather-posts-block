<?php

namespace WeatherPostsBlock;

class WeatherApi
{
	private string $api_key;

	public function __construct()
	{
		$this->api_key = get_option( 'wpb_openweather_api_key', '' );
	}

	public function get_weather( float $lat, float $lon ): array
	{
		if ( empty( $this->api_key ) ) {
			return array();
		}

		$cache_key = 'wpb_weather_' . md5( "{$lat}_{$lon}" );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$url = add_query_arg(
			array(
				'lat'   => $lat,
				'lon'   => $lon,
				'appid' => $this->api_key,
				'units' => 'metric',
			),
			'https://api.openweathermap.org/data/2.5/weather'
		);

		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body ) ) {
			return array();
		}

		$data = array(
			'temperature' => isset( $body['main']['temp'] ) ? (float) $body['main']['temp'] : null,
			'feels_like'  => isset( $body['main']['feels_like'] ) ? (float) $body['main']['feels_like'] : null,
			'condition'   => $body['weather'][0]['description'] ?? null,
			'icon'        => $body['weather'][0]['icon'] ?? null,
			'humidity'    => isset( $body['main']['humidity'] ) ? (int) $body['main']['humidity'] : null,
			'pressure'    => isset( $body['main']['pressure'] ) ? (int) $body['main']['pressure'] : null,
			'wind_speed'  => isset( $body['wind']['speed'] ) ? (float) $body['wind']['speed'] : null,
			'sunrise'     => isset( $body['sys']['sunrise'] ) ? wp_date( 'H:i', $body['sys']['sunrise'] ) : null,
			'sunset'      => isset( $body['sys']['sunset'] ) ? wp_date( 'H:i', $body['sys']['sunset'] ) : null,
			'location'    => $body['name'] ?? null,
		);

		set_transient( $cache_key, $data, HOUR_IN_SECONDS );

		return $data;
	}
}
