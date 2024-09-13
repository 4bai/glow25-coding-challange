<?php

/**
 * External Orders
 *
 * @package       EXORDERS
 * @author        Benjamin Albrecht
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Requires Plugins: woocommerce, elementor
 * Plugin Name:   External Orders
 * Plugin URI:    https://glow25.de/
 * Description:   Coding Challange for Glow25. Load Orders from an external WooCommerce Shop via API. Display the Orders in Elementor.
 * Version:       1.0.0
 * Author:        Benjamin Albrecht
 * Author URI:    https://albrightdesign.de
 * Text Domain:   external-orders
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

define('EXORDERS__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EXORDERS__CONFIG', include_once( EXORDERS__PLUGIN_DIR . 'config.php'));

/**
 * Registers external orders widgets with the Elementor widgets manager.
 *
 * @param object $widgets_manager The Elementor widgets manager instance.
 * @return void
 */
function register_external_orders($widgets_manager)
{

    require_once(EXORDERS__PLUGIN_DIR . '/widgets/elementor-external-orders-widget.php');
    $widgets_manager->register(new \Elementor_External_Orders_Widget());

    require_once(EXORDERS__PLUGIN_DIR . '/widgets/elementor-external-orders-detail-widget.php');
    $widgets_manager->register(new \Elementor_External_Orders_Detail_Widget());
}
add_action('elementor/widgets/register', 'register_external_orders');



/**
 * Registers the 'order_id' query variable.
 *
 * @param array $query_vars An array of query variables.
 * @return array The updated array of query variables.
 */
function registering_exorders_query_var($query_vars)
{
    $query_vars[] = 'order_id';
    return $query_vars;
}
add_filter('query_vars', 'registering_exorders_query_var');



/* Display external orders on account page */
function display_orders_from_external_shop()
{
    require_once(EXORDERS__PLUGIN_DIR . 'core/frontend/order-list.php');
    display_orders();
}
add_action('woocommerce_before_account_orders', 'display_orders_from_external_shop');

/* Create shortcode */
function external_orders_list_shortcode()
{
    require_once(EXORDERS__PLUGIN_DIR . 'core/frontend/order-list.php');
    display_orders();
}
add_shortcode('external_orders_list', 'external_orders_list_shortcode');
