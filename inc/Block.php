<?php

namespace WeatherPostsBlock;

class Block
{
    public function __construct()
    {
        add_action( 'init', [ $this, 'register' ] );
    }

    public function register(): void
    {
        register_block_type(
            __DIR__ . '/../build',
            [
                'render_callback' => [ $this, 'render' ],
            ]
        );
    }

    public function render( array $attributes ): string
    {
        $post_ids = $attributes['postIds'] ?? [];
        $lat      = (float) ( $attributes['latitude'] ?? 0 );
        $lon      = (float) ( $attributes['longitude'] ?? 0 );

        $posts = [];
        if ( ! empty( $post_ids ) ) {
            $query = new \WP_Query( [
                'post__in'       => $post_ids,
                'posts_per_page' => count( $post_ids ),
                'post_status'    => 'publish',
                'orderby'        => 'post__in',
            ] );
            $posts = $query->posts;
            wp_reset_postdata();
        }

        $weather = [];
        if ( $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180 && ( $lat || $lon ) ) {
            $api     = new WeatherApi();
            $weather = $api->get_weather( $lat, $lon );
        }

        ob_start();
        include __DIR__ . '/../templates/block-render.php';
        return ob_get_clean();
    }
}
