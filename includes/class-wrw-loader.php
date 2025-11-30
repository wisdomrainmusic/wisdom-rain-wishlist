<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Loader Class for Wisdom Rain Wishlist
 */
class WRW_Loader {

    /**
     * Init plugin
     */
    public static function init() {

        // Load required files
        self::load_dependencies();

        // Initialize core components
        add_action( 'plugins_loaded', [ __CLASS__, 'plugins_loaded' ] );
        add_action( 'init', [ 'WRW_Init', 'register_assets' ] );
        add_action( 'init', [ 'WRW_Init', 'register_shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ 'WRW_Init', 'enqueue_styles_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ 'WRW_Admin', 'enqueue_admin_assets' ] );

        // Ajax (logged-in + non-logged-in)
        add_action( 'wp_ajax_wrw_toggle_wishlist', [ 'WRW_Ajax', 'toggle_wishlist' ] );
        add_action( 'wp_ajax_nopriv_wrw_toggle_wishlist', [ 'WRW_Ajax', 'toggle_wishlist' ] );

        // REST API
        add_action( 'rest_api_init', [ 'WRW_Endpoints', 'register_routes' ] );
    }

    /**
     * Load all required PHP files
     */
    private static function load_dependencies() {

        $base = plugin_dir_path( __FILE__ );

        require_once $base . 'class-wrw-init.php';
        require_once $base . 'class-wrw-ajax.php';
        require_once $base . 'class-wrw-render.php';
        require_once $base . 'class-wrw-endpoints.php';
        require_once $base . 'class-wrw-session.php';
        require_once $base . 'helpers.php';

        // Admin
        require_once dirname( __FILE__ ) . '/../admin/class-wrw-admin.php';
    }

    /**
     * Runs when all plugins are loaded
     */
    public static function plugins_loaded() {
        // Future: translations, global hooks
    }
}
