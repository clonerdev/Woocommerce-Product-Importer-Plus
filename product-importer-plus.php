<?php
/*
 * Plugin Name: Product Importer Plus
 * Description: Import products from various websites into WooCommerce with advanced SEO integration and custom database tables.
 * Version: 2.2
 * Author: Ali Karimi | nedayeweb
 * Author URI: https://nedayeweb.ir
 * WC requires at least: 6.4
 * Requires PHP: 7.4
 * License: GPLv2 or later
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('PIP_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once PIP_PLUGIN_DIR . 'includes/admin-form.php';
require_once PIP_PLUGIN_DIR . 'includes/scraper.php';
require_once PIP_PLUGIN_DIR . 'includes/seo-integration.php';
require_once PIP_PLUGIN_DIR . 'includes/db-handler.php';
require_once PIP_PLUGIN_DIR . 'includes/cache-handler.php';
require_once PIP_PLUGIN_DIR . 'includes/error-handler.php';

register_activation_hook(__FILE__, 'pip_activate');
function pip_activate() {
    pip_create_custom_tables();
    if (!wp_next_scheduled('pip_scheduled_import')) {
        wp_schedule_event(time(), 'hourly', 'pip_scheduled_import');
    }
}

register_deactivation_hook(__FILE__, 'pip_deactivate');
function pip_deactivate() {
    wp_clear_scheduled_hook('pip_scheduled_import');
}

add_action('pip_scheduled_import', 'pip_scheduled_import_function');
function pip_scheduled_import_function() {
    $options = get_option('pip_options');
    $product_links = explode(PHP_EOL, $options['pip_product_links']);
    foreach ($product_links as $link) {
        if (filter_var(trim($link), FILTER_VALIDATE_URL)) {
            pip_import_product(trim($link));
        }
    }
}

function pip_import_product($url) {
    $product_data = pip_get_cached_product($url);
    if (!$product_data) {
        $product_data = pip_scrape_product($url);
        pip_cache_product($url, $product_data);
    }
    if ($product_data) {
        pip_insert_product_to_db($product_data);
        pip_insert_seo_data($product_data);
    }
}

function pip_handle_error($message) {
    pip_log_error($message);
    wp_die($message);
}
?>
