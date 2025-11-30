<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$session_id = WRW_Session::get_session_id();

// Get all wishlists
$wishlists = get_option( 'wrw_wishlists', [] );

// Current user's wishlist
$current_list = isset( $wishlists[ $session_id ] ) ? $wishlists[ $session_id ] : [];

?>

<div class="wrw-wishlist-page">

    <h2 class="wrw-title">My Wishlist</h2>

    <?php if ( empty( $current_list ) ) : ?>

        <!-- EMPTY STATE -->
        <div class="wrw-empty-state">
            <p>Your wishlist is empty.</p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="wrw-btn">
                Go to Shop
            </a>
        </div>

    <?php else : ?>

        <ul class="wrw-grid">

        <?php foreach ( $current_list as $product_id ) : 
            
            $product = wc_get_product( $product_id );
            if ( ! $product ) continue;

            $image  = $product->get_image();
            $title  = $product->get_title();
            $price  = $product->get_price_html();
            $url    = get_permalink( $product_id );

        ?>
            <li class="wrw-item">

                <div class="wrw-thumb">
                    <a href="<?php echo esc_url( $url ); ?>">
                        <?php echo $image; ?>
                    </a>
                </div>

                <div class="wrw-info">
                    <h3 class="wrw-name">
                        <a href="<?php echo esc_url( $url ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                    </h3>

                    <div class="wrw-price">
                        <?php echo wp_kses_post( $price ); ?>
                    </div>

                    <div class="wrw-actions">

                        <!-- VIEW PRODUCT -->
                        <a href="<?php echo esc_url( $url ); ?>" class="wrw-btn">
                            View Product
                        </a>

                        <!-- REMOVE BUTTON (uses same toggle AJAX) -->
                        <button class="wrw-btn-remove wrw-wishlist-btn" 
                                data-product="<?php echo esc_attr( $product_id ); ?>"
                                data-active="1">
                            Remove
                        </button>

                    </div>
                </div>

            </li>

        <?php endforeach; ?>

        </ul>

    <?php endif; ?>

</div>
