<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WRW_Ajax {

    /**
     * ---------------------------------------------------------
     *  INTERNAL HELPERS
     * ---------------------------------------------------------
     */

    /**
     * Verify AJAX nonce.
     *
     * @param string $action
     */
    private static function verify_nonce( $action = 'wrw_nonce' ) {
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

        if ( ! wp_verify_nonce( $nonce, $action ) ) {
            wp_send_json_error(
                [
                    'message' => 'Security check failed.',
                ],
                403
            );
        }
    }

    /**
     * Get owner key and current wishlist list for that owner.
     *
     * @return array [ $owner_key, $wishlists, $current_list ]
     */
    private static function get_owner_lists() {

        // owner: user_123 / guest_xxx
        $owner_key = WRW_Session::get_session_id();

        // all wishlists
        $wishlists = get_option( 'wrw_wishlists', [] );
        if ( ! is_array( $wishlists ) ) {
            $wishlists = [];
        }

        // current user's list
        $current_list = isset( $wishlists[ $owner_key ] ) && is_array( $wishlists[ $owner_key ] )
            ? $wishlists[ $owner_key ]
            : [];

        return [ $owner_key, $wishlists, $current_list ];
    }

    /**
     * Save updated list for given owner.
     *
     * @param string $owner_key
     * @param array  $wishlists
     * @param array  $current_list
     */
    private static function save_owner_list( $owner_key, $wishlists, $current_list ) {

        $wishlists[ $owner_key ] = array_values( array_unique( array_map( 'absint', $current_list ) ) );

        // autoload = false → performans için
        update_option( 'wrw_wishlists', $wishlists, false );
    }

    /**
     * Small helper to validate incoming product_id.
     *
     * @return int
     */
    private static function validate_product_id() {

        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

        if ( ! $product_id ) {
            wp_send_json_error(
                [
                    'message' => 'Missing product ID.',
                ],
                400
            );
        }

        if ( 'product' !== get_post_type( $product_id ) ) {
            wp_send_json_error(
                [
                    'message' => 'Invalid product type.',
                ],
                400
            );
        }

        if ( function_exists( 'wc_get_product' ) ) {
            $product = wc_get_product( $product_id );
            if ( ! $product ) {
                wp_send_json_error(
                    [
                        'message' => 'Product not found.',
                    ],
                    404
                );
            }
        }

        return $product_id;
    }

    /**
     * ---------------------------------------------------------
     *  PUBLIC AJAX: TOGGLE
     * ---------------------------------------------------------
     *
     * Action: wrw_toggle_wishlist
     * Method: POST
     * Params: product_id, nonce
     */
    public static function toggle_wishlist() {

        // Nonce
        self::verify_nonce( 'wrw_nonce' );

        // Product
        $product_id = self::validate_product_id();

        // Owner + list
        list( $owner_key, $wishlists, $current_list ) = self::get_owner_lists();

        // Toggle logic
        $status = '';

        if ( in_array( $product_id, $current_list, true ) ) {
            // remove from list
            $current_list = array_values( array_diff( $current_list, [ $product_id ] ) );
            $status       = 'removed';
        } else {
            // add to list
            $current_list[] = $product_id;
            $status         = 'added';
        }

        // Save
        self::save_owner_list( $owner_key, $wishlists, $current_list );

        // Response
        wp_send_json_success(
            [
                'status'      => $status,
                'product_id'  => $product_id,
                'count'       => count( $current_list ),
                'owner'       => $owner_key,
            ]
        );
    }

    /**
     * ---------------------------------------------------------
     *  PUBLIC AJAX: REMOVE SINGLE ITEM (WISHLIST PAGE)
     * ---------------------------------------------------------
     *
     * Action: wrw_remove_wishlist
     * Method: POST
     * Params: product_id, nonce
     *
     * Bu endpoint /wishlist sayfasındaki "süpürge / çöp kutusu / kalp" butonunun
     * tek ürün silmesini sağlar.
     */
    public static function remove_item() {

        // Nonce
        self::verify_nonce( 'wrw_nonce' );

        // Product
        $product_id = self::validate_product_id();

        // Owner + list
        list( $owner_key, $wishlists, $current_list ) = self::get_owner_lists();

        if ( empty( $current_list ) ) {
            wp_send_json_success(
                [
                    'status'     => 'empty',
                    'product_id' => $product_id,
                    'count'      => 0,
                ]
            );
        }

        // Remove from list
        $new_list = array_values( array_diff( $current_list, [ $product_id ] ) );

        // Save
        self::save_owner_list( $owner_key, $wishlists, $new_list );

        wp_send_json_success(
            [
                'status'     => 'removed',
                'product_id' => $product_id,
                'count'      => count( $new_list ),
            ]
        );
    }
}
