<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$session_id = WRW_Session::get_session_id();
$wishlists  = get_option( 'wrw_wishlists', [] );
$current    = isset( $wishlists[ $session_id ] ) && is_array( $wishlists[ $session_id ] )
    ? array_filter( array_map( 'absint', $wishlists[ $session_id ] ) )
    : [];
?>

<div class="wrw-wishlist-page">
    <div class="wrw-wishlist-wrapper">
        <div class="wrw-wishlist-header">
            <h2><?php esc_html_e( 'My Wishlist', 'wisdom-rain-wishlist' ); ?></h2>
            <span class="wrw-wishlist-count"><?php echo esc_html( count( $current ) ); ?></span>
        </div>

        <?php if ( empty( $current ) ) : ?>
            <p class="wrw-empty"><?php esc_html_e( 'Your wishlist is empty.', 'wisdom-rain-wishlist' ); ?></p>
        <?php else : ?>
            <ul class="wrw-wishlist-items">
                <?php foreach ( $current as $product_id ) :
                    $title = get_the_title( $product_id );

                    if ( ! $title ) {
                        continue;
                    }

                    $permalink = get_permalink( $product_id );
                    ?>
                    <li class="wrw-wishlist-item">
                        <a href="<?php echo esc_url( $permalink ); ?>">
                            <?php echo esc_html( $title ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
