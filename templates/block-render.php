<?php
/**
 * Frontend template for Weather Posts Block.
 *
 * @var array $attributes  Block attributes from editor
 * @var array $weather     Weather data from OpenWeatherMap
 * @var array $posts       Array of WP_Post objects
 */

$first_post  = $posts[0] ?? null;
$second_post = $posts[1] ?? null;
?>

<div <?php echo get_block_wrapper_attributes( [ 'class' => 'wpb-block' ] ); ?>>

    <div class="wpb-block__weather-panel">
        <?php if ( ! empty( $weather ) ) : ?>

            <?php if ( ! empty( $weather['icon'] ) ) : ?>
                <img
                    src="https://openweathermap.org/img/wn/<?php echo esc_attr( $weather['icon'] ); ?>@4x.png"
                    alt="<?php echo esc_attr( ucfirst( $weather['condition'] ?? '' ) ); ?>"
                    class="wpb-weather__icon"
                    width="100"
                    height="100"
                />
            <?php endif; ?>

            <?php if ( ! empty( $attributes['showLocation'] ) && ! empty( $weather['location'] ) ) : ?>
                <div class="wpb-weather__location"><?php echo esc_html( $weather['location'] ); ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $attributes['showTemperature'] ) && isset( $weather['temperature'] ) ) : ?>
                <div class="wpb-weather__temp"><?php echo esc_html( round( $weather['temperature'], 1 ) ); ?><span>°C</span></div>
            <?php endif; ?>

            <?php if ( ! empty( $attributes['showCondition'] ) && isset( $weather['condition'] ) ) : ?>
                <div class="wpb-weather__condition"><?php echo esc_html( ucfirst( $weather['condition'] ) ); ?></div>
            <?php endif; ?>

            <ul class="wpb-weather__list">
                <?php if ( ! empty( $attributes['showFeelsLike'] ) && isset( $weather['feels_like'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Feels like', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( round( $weather['feels_like'], 1 ) ); ?>°C</span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['showHumidity'] ) && isset( $weather['humidity'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Humidity', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( $weather['humidity'] ); ?>%</span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['showPressure'] ) && isset( $weather['pressure'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Pressure', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( $weather['pressure'] ); ?> hPa</span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['showWindSpeed'] ) && isset( $weather['wind_speed'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Wind', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( $weather['wind_speed'] ); ?> m/s</span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['showSunrise'] ) && isset( $weather['sunrise'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Sunrise', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( $weather['sunrise'] ); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $attributes['showSunset'] ) && isset( $weather['sunset'] ) ) : ?>
                    <li class="wpb-weather__item">
                        <span class="wpb-weather__label"><?php esc_html_e( 'Sunset', 'weather-posts-block' ); ?></span>
                        <span class="wpb-weather__value"><?php echo esc_html( $weather['sunset'] ); ?></span>
                    </li>
                <?php endif; ?>
            </ul>

        <?php else : ?>
            <p class="wpb-weather__no-data"><?php esc_html_e( 'Weather data unavailable.', 'weather-posts-block' ); ?></p>
        <?php endif; ?>
    </div>

    <div class="wpb-block__posts">

        <?php foreach ( [ $first_post, $second_post ] as $post ) :
            if ( ! $post ) continue;
            $id       = $post->ID;
            $title    = get_the_title( $id );
            $url      = get_permalink( $id );
            $thumb_id = get_post_thumbnail_id( $id );
            $excerpt  = wp_trim_words( get_the_excerpt( $id ), 18 );
            $cats     = get_the_category( $id );
            $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
        ?>
            <article class="wpb-post-card">
                <?php if ( $thumb_id ) : ?>
                    <a href="<?php echo esc_url( $url ); ?>" class="wpb-post-card__image-wrap" tabindex="-1" aria-hidden="true">
                        <?php echo wp_get_attachment_image( $thumb_id, 'medium_large', false, [
                            'class' => 'wpb-post-card__image',
                            'alt'   => '',
                        ] ); ?>
                    </a>
                <?php endif; ?>
                <div class="wpb-post-card__body">
                    <?php if ( $cat_name ) : ?>
                        <span class="wpb-post-card__category"><?php echo esc_html( $cat_name ); ?></span>
                    <?php endif; ?>
                    <h3 class="wpb-post-card__title">
                        <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a>
                    </h3>
                    <?php if ( $excerpt ) : ?>
                        <p class="wpb-post-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>

    </div>

</div>
