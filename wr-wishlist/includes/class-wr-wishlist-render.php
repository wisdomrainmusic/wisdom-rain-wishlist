<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WR_Wishlist_Render {

    public static function wishlist_page() {
        $items = WR_Wishlist_Core::get_wishlist();

        ob_start();
        ?>
        <div class="wr-wishlist-page">
            <h2>Your Wishlist</h2>

            <?php if ( empty( $items ) ) : ?>
                <p>No items yet.</p>
            <?php else: ?>
                <ul class="wr-wishlist-list">
                    <?php foreach ( $items as $id ) : ?>
                        <li><?php echo get_the_title( $id ); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
