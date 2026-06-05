<?php

namespace WeatherPostsBlock;

class Block
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register(): void
	{
		register_block_type(
			__DIR__ . '/../build',
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	public function render( array $attributes ): string
	{
		$post_ids = $attributes['postIds'] ?? array();
		$lat      = (float) ( $attributes['latitude'] ?? 0 );
		$lon      = (float) ( $attributes['longitude'] ?? 0 );

		$posts = array();
		if ( ! empty( $post_ids ) ) {
			$query = new \WP_Query(
				array(
					'post__in'       => $post_ids,
					'posts_per_page' => count( $post_ids ),
					'post_status'    => 'publish',
					'orderby'        => 'post__in',
				)
			);
			$posts = $query->posts;
			wp_reset_postdata();
		}

		$weather = array();
		if ( $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180 && ( $lat || $lon ) ) {
			$api     = new WeatherApi();
			$weather = $api->get_weather( $lat, $lon );
		}

		ob_start();
		include __DIR__ . '/../templates/block-render.php';
		return ob_get_clean();
	}
}
