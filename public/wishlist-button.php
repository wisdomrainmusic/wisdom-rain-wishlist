<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$product_id = isset( $product_id ) ? absint( $product_id ) : get_the_ID();
$session_id = WRW_Session::get_session_id();

$wishlists = get_option( 'wrw_wishlists', [] );
$current   = isset( $wishlists[ $session_id ] ) ? $wishlists[ $session_id ] : [];

$is_active = in_array( $product_id, $current, true );
?>

<div class="wrw-wishlist-btn" 
     data-product="<?php echo esc_attr( $product_id ); ?>"
     data-active="<?php echo $is_active ? '1' : '0'; ?>">

    <span class="wrw-heart <?php echo $is_active ? 'active' : ''; ?>"></span>
</div>
