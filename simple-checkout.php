<?php
/**
 * Plugin Name: Simple Checkout Widget
 * Description: A simplified WooCommerce checkout widget for Elementor
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: simple-checkout-widget
 * Requires WooCommerce: 3.0
 * Requires Elementor: 3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check required plugins
function simple_checkout_widget_check_requirements() {
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    
    // Check for Elementor
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible"><p>' . 
                 __('Simple Checkout Widget requires Elementor plugin to be active.', 'simple-checkout-widget') . 
                 '</p></div>';
        });
        return false;
    }

    // Check for WooCommerce
    if (!in_array('woocommerce/woocommerce.php', $active_plugins) && !class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible"><p>' . 
                 __('Simple Checkout Widget requires WooCommerce plugin to be active.', 'simple-checkout-widget') . 
                 '</p></div>';
        });
        return false;
    }

    return true;
}

// Register Widget
function register_simple_checkout_widget() {
    if (!simple_checkout_widget_check_requirements()) {
        return;
    }

    require_once(__DIR__ . '/widgets/simple-checkout-widget.php');
    \Elementor\Plugin::instance()->widgets_manager->register(new \Simple_Checkout_Widget());
}
add_action('elementor/widgets/register', 'register_simple_checkout_widget');

// Enqueue CSS and JS
function simple_checkout_widget_assets() {
    wp_enqueue_style(
        'simple-checkout-widget-style',
        plugins_url('assets/css/simple-checkout-widget.css', __FILE__),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'simple-checkout-widget-script',
        plugins_url('assets/js/simple-checkout-widget.js', __FILE__),
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'simple_checkout_widget_assets');

// Add body class for better styling
function simple_checkout_widget_body_class($classes) {
    if (is_page() && has_shortcode(get_post()->post_content, 'simple_checkout')) {
        $classes[] = 'has-simple-checkout';
    }
    return $classes;
}
add_filter('body_class', 'simple_checkout_widget_body_class');
