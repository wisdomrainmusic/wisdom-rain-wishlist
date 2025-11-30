<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Wishlist_Loader {

    public static function init() {

        // Core files
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-core.php';
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-ajax.php';
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-render.php';

        // Assets
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'assets' ], 5 );

        // Shortcode
        add_shortcode( 'wr_wishlist_page', ['WR_Wishlist_Render', 'wishlist_page'] );
    }

    public static function assets() {

        // JS
        wp_enqueue_script(
            'wr-wishlist-js',
            WR_WL_URL . 'assets/js/wishlist.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize (MUST run after enqueue)
        wp_localize_script(
            'wr-wishlist-js',
            'WRWL',
            array(
                'ajax'  => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wr_wishlist_nonce'),
            )
        );

        // CSS
        wp_enqueue_style(
            'wr-wishlist-css',
            WR_WL_URL . 'assets/css/wishlist.css',
            array(),
            '1.0.0'
        );
    }

    public static function activate() {
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

WR_Wishlist_Loader::init();
