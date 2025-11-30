<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WRW_Ajax {

    /**
     * Main AJAX handler for toggling wishlist items
     *
     * Action: wrw_toggle_wishlist
     * Method: POST
     * Params: product_id, nonce
     */
    public static function toggle_wishlist() {

        // -----------------------------
        // Nonce check
        // -----------------------------
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

        if ( ! wp_verify_nonce( $nonce, 'wrw_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Security check failed.',
            ], 403 );
        }

        // -----------------------------
        // Product validation
        // -----------------------------
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

        if ( ! $product_id ) {
            wp_send_json_error( [
                'message' => 'Missing product ID.',
            ], 400 );
        }

        // WooCommerce product check
        if ( 'product' !== get_post_type( $product_id ) ) {
            wp_send_json_error( [
                'message' => 'Invalid product type.',
            ], 400 );
        }

        if ( function_exists( 'wc_get_product' ) ) {
            $product = wc_get_product( $product_id );
            if ( ! $product ) {
                wp_send_json_error( [
                    'message' => 'Product not found.',
                ], 404 );
            }
        }

        // -----------------------------
        // Resolve owner key (user or session)
        // -----------------------------
        $owner_key = WRW_Session::get_session_id(); // zaten user_ / guest_ formatında

        // -----------------------------
        // Load existing wishlists
        // -----------------------------
        $wishlists = get_option( 'wrw_wishlists', [] );

        if ( ! is_array( $wishlists ) ) {
            $wishlists = [];
        }

        $current_list = isset( $wishlists[ $owner_key ] ) && is_array( $wishlists[ $owner_key ] )
            ? $wishlists[ $owner_key ]
            : [];

        // -----------------------------
        // Toggle logic (add / remove)
        // -----------------------------
        $status = '';

        if ( in_array( $product_id, $current_list, true ) ) {
            // Remove from wishlist
            $current_list = array_values( array_diff( $current_list, [ $product_id ] ) );
            $status       = 'removed';
        } else {
            // Add to wishlist
            $current_list[] = $product_id;
            $current_list   = array_values( array_unique( $current_list ) );
            $status         = 'added';
        }

        // -----------------------------
        // Save back to option
        // -----------------------------
        $wishlists[ $owner_key ] = $current_list;

        update_option( 'wrw_wishlists', $wishlists, false );

        // -----------------------------
        // Response
        // -----------------------------
        wp_send_json_success( [
            'status'      => $status,
            'product_id'  => $product_id,
            'count'       => count( $current_list ),
            'owner'       => $owner_key,
        ] );
    }
}
