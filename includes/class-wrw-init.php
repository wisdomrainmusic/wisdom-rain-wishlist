<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WRW_Init {

    /**
     * Register scripts & styles (just register, not enqueue)
     */
    public static function register_assets() {

        // CSS
        wp_register_style(
            'wrw-wishlist',
            WRW_PLUGIN_URL . 'assets/css/wishlist.css',
            [],
            '2.0.0'
        );

        wp_register_style(
            'wrw-wishlist-button',
            WRW_PLUGIN_URL . 'assets/css/wishlist-button.css',
            [],
            '2.0.0'
        );

        // JS
        wp_register_script(
            'wrw-wishlist',
            WRW_PLUGIN_URL . 'assets/js/wishlist.js',
            [ 'jquery' ],
            '2.0.0',
            true
        );

        wp_register_script(
            'wrw-wishlist-ajax',
            WRW_PLUGIN_URL . 'assets/js/wishlist-ajax.js',
            [ 'jquery' ],
            '2.0.0',
            true
        );
    }

    /**
     * Enqueue scripts on frontend
     */
    public static function enqueue_styles_scripts() {

        wp_enqueue_style( 'wrw-wishlist' );
        wp_enqueue_style( 'wrw-wishlist-button' );

        wp_enqueue_script( 'wrw-wishlist' );
        wp_enqueue_script( 'wrw-wishlist-ajax' );

        // Localize script (ajax URL + nonce + session token)
        wp_localize_script( 'wrw-wishlist-ajax', 'WRW_VARS', [
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'wrw_nonce' ),
            'session'   => WRW_Session::get_session_id(),
            'user_id'   => get_current_user_id(),
        ]);
    }

    /**
     * Wishlist Shortcode
     * Usage: [wr_wishlist]
     */
    public static function register_shortcode() {
        add_shortcode( 'wr_wishlist', function() {
            ob_start();
            include WRW_PLUGIN_DIR . 'public/wishlist-template.php';
            return ob_get_clean();
        });
    }
}
