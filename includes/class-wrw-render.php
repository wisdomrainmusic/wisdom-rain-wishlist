<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WRW_Render {

    /**
     * Add wishlist button to WooCommerce product loop
     */
    public static function init() {
        add_action( 'woocommerce_before_shop_loop_item_title', [ __CLASS__, 'add_wishlist_button' ], 15 );
    }

    public static function add_wishlist_button() {
        global $product;

        if ( ! $product ) return;

        $product_id = $product->get_id();

        include WRW_PLUGIN_DIR . 'public/wishlist-button.php';
    }
}
