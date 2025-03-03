<?php
/*
 * Plugin Name: Elyamani Slider
 * Plugin URI: https://example.com/plugins/the-basics/
 * Description: this a sample plugin for creating a slider using slick slider library.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Elyamani hamid
 * Author URI: https://elyamanihamid.online/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain: my-basics-plugin
 * Domain Path: /languages
 */


if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('ELYAMANI_SLIDER_VERSION', '1.0.0');
define('ELYAMANI_SLIDER_PATH', plugin_dir_path(__FILE__));
define('ELYAMANI_SLIDER_URL', plugin_dir_url(__FILE__));
define('ELYAMANI_SLIDER_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_elyamani_slider()
{
    require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-activator.php';
    Elyamani_Slider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_elyamani_slider()
{
    require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-deactivator.php';
    Elyamani_Slider_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_elyamani_slider');
register_deactivation_hook(__FILE__, 'deactivate_elyamani_slider');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider.php';

/**
 * Begins execution of the plugin.
 */
function run_elyamani_slider()
{
    $plugin = new Elyamani_Slider();
    $plugin->run();
}
run_elyamani_slider();