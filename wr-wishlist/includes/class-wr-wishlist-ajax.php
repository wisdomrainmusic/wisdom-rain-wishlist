<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Wishlist_Ajax {

    public static function init() {
        add_action( 'wp_ajax_wr_toggle_wishlist', [ __CLASS__, 'toggle' ] );
        add_action( 'wp_ajax_nopriv_wr_toggle_wishlist', [ __CLASS__, 'guest_error' ] );
    }

    public static function guest_error() {
        wp_send_json_error([ 'message' => 'Login required.' ]);
    }

    public static function toggle() {

        check_ajax_referer( 'wr_wishlist_nonce', 'nonce' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( ['message' => 'Login required.'] );
        }

        $product_id = intval( $_POST['product_id'] ?? 0 );
        if ( $product_id <= 0 ) wp_send_json_error();

        $wishlist = WR_Wishlist_Core::toggle( $product_id );

        wp_send_json_success([
            'wishlist' => $wishlist,
            'count'    => count($wishlist)
        ]);
    }
}

WR_Wishlist_Ajax::init();
