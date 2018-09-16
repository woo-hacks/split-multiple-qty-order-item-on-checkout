<?php
/**
 * Plugin Name:   Woo-Tip #3 - Split Product with more than 1 quanitity on checkout
 * Description:  Try to checkout more item with more than 1 qty and it will be split to multiple order item after checkout.
 * Version:     0.0.3
 * Author:      KT-12
 * Author URI:  https://kt12.in/
 *
 * @package Split_Multiple_Qty_Order_Item_On_Checkout
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'class-split-multiple-qty-order-item-on-checkout.php';
$smqoioc = new Split_Multiple_Qty_Order_Item_On_Checkout();
$smqoioc->run();
