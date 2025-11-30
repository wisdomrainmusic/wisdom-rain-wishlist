<?php
/**
 * Plugin Name: Wisdom Rain Wishlist
 * Plugin URI: https://wisdomrain.com
 * Description: A fully optimized wishlist system for the Wisdom Rain ecosystem. Supports AJAX, Elementor, WooCommerce, and guest sessions.
 * Version: 2.0.0
 * Author: Wisdom Rain
 * Text Domain: wisdom-rain-wishlist
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define plugin constants
 */
define( 'WRW_PLUGIN_FILE', __FILE__ );
define( 'WRW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WRW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load Loader Class
 */
require_once WRW_PLUGIN_DIR . 'includes/class-wrw-loader.php';

/**
 * Kickoff
 */
WRW_Loader::init();
