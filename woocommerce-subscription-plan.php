<?php
/**
 * Plugin Name: WooCommerce Subscription Plan
 * Description: Adds a shipment plan product type to WooCommerce, which allows sellers to sell a plan that ship orders on a recurring basis.
 * Version: 1.0
 * Author: Pasindu Upendra
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Define plugin constants.
define( 'SUBSCRIPTION_WOOCOMMERCE_EXTENSION_VERSION', '1.0.0' );
define( 'SUBSCRIPTION_WOOCOMMERCE_EXTENSION_PATH', plugin_dir_path( __FILE__ ) );
define( 'SUBSCRIPTION_WOOCOMMERCE_EXTENSION_URL', plugin_dir_url( __FILE__ ) );

// Load the plugin files.
require_once SUBSCRIPTION_WOOCOMMERCE_EXTENSION_PATH . 'includes/subscription-woocommerce-extension-functions.php';
require_once SUBSCRIPTION_WOOCOMMERCE_EXTENSION_PATH . 'includes/subscription-woocommerce-extension-shortcodes.php';
require_once SUBSCRIPTION_WOOCOMMERCE_EXTENSION_PATH . 'includes/subscription-woocommerce-extension-admin.php';

?>