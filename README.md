# Weather Posts Block

A custom WordPress Gutenberg block that displays two selected posts alongside a live weather snippet from OpenWeatherMap. Built as a dynamic server-side rendered block with a React-based editor UI.

---

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Composer
- Node.js 18+ / npm

---

## Installation

1. Clone or copy the plugin folder into `wp-content/plugins/weather-posts-block/`
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Build front-end assets:
   ```bash
   npm install
   npm run build
   ```
4. Activate the plugin in **WordPress Admin → Plugins**

---

## Configuration

1. Go to **Settings → Weather Posts Block**
2. Enter your [OpenWeatherMap API key](https://openweathermap.org/api)
3. Save changes

> A free OpenWeatherMap account provides up to 1,000 API calls/day. New API keys activate within ~10 minutes of registration.

---

## Usage

1. Open any page or post in the block editor
2. Add the **Weather Posts Block** (found under the Widgets category)
3. In the block sidebar configure:
   - **Posts** — search and select up to 2 posts to display
   - **Location** — enter latitude and longitude for weather data
   - **Weather Fields** — toggle visibility of individual weather fields
4. Publish the page

The block renders server-side on the frontend. The editor shows a native React preview with live post data fetched from the WordPress data store.

---

## Block Settings

| Setting | Description |
|---|---|
| Posts | Search and select up to 2 WordPress posts |
| Latitude / Longitude | Geographic coordinates for weather data |
| Location name | Show/hide the city name |
| Temperature | Show/hide current temperature (°C) |
| Feels like | Show/hide apparent temperature |
| Condition | Show/hide weather condition text |
| Humidity | Show/hide humidity percentage |
| Pressure | Show/hide atmospheric pressure (hPa) |
| Wind speed | Show/hide wind speed (m/s) |
| Sunrise / Sunset | Show/hide sunrise and sunset times |

Weather data is cached per latitude/longitude pair for **1 hour** to minimise API usage.

---

## WP-CLI

Force-clear the weather cache so the next page load fetches fresh data:

```bash
wp wpb clear-cache
```

---

## Project Structure

```
weather-posts-block/
├── build/                  # Compiled JS and CSS (committed for reviewers)
├── inc/
│   ├── Plugin.php          # Bootstraps all modules
│   ├── Admin.php           # Settings page (API key storage)
│   ├── Block.php           # Block registration and server-side render callback
│   ├── WeatherApi.php      # OpenWeatherMap API integration + transient cache
│   ├── Ajax.php            # AJAX endpoint for weather data
│   └── Cli.php             # WP-CLI clear-cache command
├── src/
│   ├── block.json          # Block metadata and attribute definitions
│   ├── index.js            # Block entry point
│   ├── edit.js             # React editor component (InspectorControls + native React preview)
│   ├── save.js             # Returns null — dynamic block, rendered by PHP
│   └── style.scss          # Frontend and editor styles
├── templates/
│   └── block-render.php    # Frontend HTML template
├── vendor/                 # Composer dependencies (PSR-4 autoloader)
├── composer.json
├── package.json
└── weather-posts-block.php # Plugin entry point
```

---

## Development

Start the build watcher during development:

```bash
npm run start
```

Lint JavaScript:

```bash
npm run lint:js
```

Production build:

```bash
npm run build
```

---

## Technical Stack

- **PHP 8.0** — OOP architecture, PSR-4 autoloading via Composer
- **WordPress Settings API** — admin configuration page
- **WordPress Transients API** — weather response caching (1 hour per coordinates pair)
- **`wp_remote_get()`** — HTTP requests (no direct cURL)
- **`WP_Query`** with `post__in` / `orderby: post__in` — post data fetching in user-defined order
- **`get_block_wrapper_attributes()`** — alignment support (`wide`, `full`) via block supports API
- **`wp_date()`** — timezone-aware sunrise/sunset formatting
- **React / @wordpress/scripts** — Gutenberg editor UI
- **`useSelect` / `@wordpress/core-data`** — native React editor preview via WordPress data stores
- **`CheckboxControl`, `ToggleControl`, `InspectorControls`** — native `@wordpress/components` sidebar UI
- **WP-CLI** — custom `wp wpb clear-cache` command
- **CSS Grid** — two-column responsive layout (`2fr 1fr`)
- **SCSS with `clamp()`** — fluid responsive typography and spacing
