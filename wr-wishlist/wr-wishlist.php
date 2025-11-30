<?php
/**
 * Plugin Name: WR Wishlist
 * Description: Ultra lightweight AJAX wishlist system for WR Ecommerce Theme.
 * Version: 1.0.0
 * Author: Wisdom Rain
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WR_WL_DIR', plugin_dir_path( __FILE__ ) );
define( 'WR_WL_URL', plugin_dir_url( __FILE__ ) );

// Loader
require_once WR_WL_DIR . 'includes/class-wr-wishlist-loader.php';

register_activation_hook( __FILE__, ['WR_Wishlist_Loader', 'activate'] );
register_deactivation_hook( __FILE__, ['WR_Wishlist_Loader', 'deactivate'] );
