<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Wishlist_Loader {

    public static function init() {
        // Core files
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-core.php';
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-ajax.php';
        require_once WR_WL_DIR . 'includes/class-wr-wishlist-render.php';

        // Enqueue
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'assets' ] );

        // Shortcode
        add_shortcode( 'wr_wishlist_page', ['WR_Wishlist_Render', 'wishlist_page'] );
    }

    public static function assets() {
        $js_path  = WR_WL_DIR . 'assets/js/wishlist.js';
        $css_path = WR_WL_DIR . 'assets/css/wishlist.css';

        $version_js  = file_exists( $js_path ) ? filemtime( $js_path ) : '1.0.0';
        $version_css = file_exists( $css_path ) ? filemtime( $css_path ) : '1.0.0';

        wp_enqueue_script(
            'wr-wishlist-js',
            WR_WL_URL . 'assets/js/wishlist.js',
            ['jquery'],
            $version_js,
            true
        );

        wp_localize_script( 'wr-wishlist-js', 'WRWL', [
            'ajax' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wr_wishlist_nonce')
        ]);

        wp_enqueue_style(
            'wr-wishlist-css',
            WR_WL_URL . 'assets/css/wishlist.css',
            [],
            $version_css
        );
    }

    public static function activate() {
        // Create user meta if needed, flush rewrite
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

WR_Wishlist_Loader::init();
