<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$product_id = isset( $product_id ) ? absint( $product_id ) : null;
$context    = isset( $context ) ? $context : '';

wrw_render_wishlist_button( $product_id, $context );
