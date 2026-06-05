<?php

namespace WeatherPostsBlock;

class Admin
{
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_page(): void
	{
		add_options_page(
			__( 'Weather Posts Block', 'weather-posts-block' ),
			__( 'Weather Posts Block', 'weather-posts-block' ),
			'manage_options',
			'weather-posts-block',
			array( $this, 'render_page' )
		);
	}

	public function register_settings(): void
	{
		register_setting(
			'wpb_settings',
			'wpb_openweather_api_key',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		add_settings_section(
			'wpb_main',
			__( 'API Settings', 'weather-posts-block' ),
			'__return_null',
			'weather-posts-block'
		);

		add_settings_field(
			'wpb_openweather_api_key',
			__( 'OpenWeatherMap API Key', 'weather-posts-block' ),
			array( $this, 'render_api_key_field' ),
			'weather-posts-block',
			'wpb_main'
		);
	}

	public function render_api_key_field(): void
	{
		$value = get_option( 'wpb_openweather_api_key', '' );
		?>
		<input
			type="text"
			name="wpb_openweather_api_key"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		/>
		<p class="description">
			<?php esc_html_e( 'Get your key at openweathermap.org', 'weather-posts-block' ); ?>
		</p>
		<?php
	}

	public function render_page(): void
	{
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'wpb_settings' );
				do_settings_sections( 'weather-posts-block' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
