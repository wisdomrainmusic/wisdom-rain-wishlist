<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Wishlist_Core {

    public static function get_wishlist() {
        $wishlist = get_user_meta( get_current_user_id(), '_wr_wishlist', true );
        return is_array($wishlist) ? $wishlist : [];
    }

    public static function toggle( $product_id ) {
        $wishlist = self::get_wishlist();

        if ( in_array( $product_id, $wishlist ) ) {
            $wishlist = array_diff( $wishlist, [ $product_id ] );
        } else {
            $wishlist[] = $product_id;
        }

        update_user_meta( get_current_user_id(), '_wr_wishlist', $wishlist );
        return $wishlist;
    }
}
