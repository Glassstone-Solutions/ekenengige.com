<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ){
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
$classes[] = 'w-item';
if( intval($woocommerce_loop['columns']) == 5 ){
    $classes[] = 'five-cols';
}else{
   $classes[] = 'col-'.  absint( floor(12/ intval( $woocommerce_loop['columns'] ) ) ); 
}

?>
<li <?php post_class( $classes ); ?>>
    <figure>            
        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <?php
		/**
			* woocommerce_before_shop_loop_item_title hook
			*
			* @hooked woocommerce_show_product_loop_sale_flash - 10
			* @hooked woocommerce_template_loop_product_thumbnail - 10
			*/
		    do_action( 'woocommerce_before_shop_loop_item_title' );
	    ?>
        <figcaption>
	        <h3><?php the_title(); ?></h3>
	        <?php
		        /**
			        * woocommerce_after_shop_loop_item_title hook
			        *
			        * @hooked woocommerce_template_loop_rating - 5
			        * @hooked woocommerce_template_loop_price - 10
			        */
		        do_action( 'woocommerce_after_shop_loop_item_title' );
	        ?>
        </figcaption>
        <?php

		    /**
		        * woocommerce_after_shop_loop_item hook
		        *
		        * @hooked woocommerce_template_loop_add_to_cart - 10
		        */
		    do_action( 'woocommerce_after_shop_loop_item' ); 

	    ?>
    </figure>
</li>