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

        wrw_render_wishlist_button( null, 'loop' );
    }
}

if ( ! function_exists( 'wrw_render_wishlist_button' ) ) {
    /**
     * Global helper to render wishlist button for a given product.
     *
     * @param int|\WC_Product|null $product Optional product or product ID.
     * @param string               $context Optional context string (e.g. 'grid', 'tabs', 'tab_grid', 'loop').
     */
    function wrw_render_wishlist_button( $product = null, $context = '' ) {
        if ( $product instanceof \WC_Product ) {
            $product_id = $product->get_id();
        } elseif ( is_numeric( $product ) ) {
            $product_id = (int) $product;
            $product    = wc_get_product( $product_id );
        } else {
            global $product;
            $product_id = $product instanceof \WC_Product ? $product->get_id() : 0;
        }

        if ( ! $product_id ) {
            return;
        }

        $session_id = WRW_Session::get_session_id();

        $wishlists = get_option( 'wrw_wishlists', [] );
        $current   = isset( $wishlists[ $session_id ] ) ? $wishlists[ $session_id ] : [];

        $is_active = in_array( $product_id, $current, true );

        $active_attr  = $is_active ? '1' : '0';
        $active_class = $is_active ? ' active' : '';

        ?>
        <div class="wrw-wishlist-btn"
             data-product="<?php echo esc_attr( $product_id ); ?>"
             data-active="<?php echo esc_attr( $active_attr ); ?>"
             data-context="<?php echo esc_attr( $context ); ?>">
            <span class="wrw-heart<?php echo esc_attr( $active_class ); ?>"></span>
        </div>
        <?php
    }
}
