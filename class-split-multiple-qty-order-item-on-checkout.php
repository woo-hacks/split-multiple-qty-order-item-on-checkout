<?php
/**
 * Main class file
 *
 *  @package Split_Multiple_Qty_Order_Item_On_Checkout
 */

/**
 * Split_Multiple_Qty_Order_Item_On_Checkout Class split product on checkout.
 */
class Split_Multiple_Qty_Order_Item_On_Checkout {

	/**
	 * Initialize Hooks.
	 *
	 * @access public
	 */
	public function run() {

		/**
		 * STEP 1 - Split product with multiple qty in cart into seperate order items after checkout
		 */
		// loc: woocommerce/includes/class-wc-checkout.php // woocommerce checkout.
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'create_order_line_items' ), 10, 4 );
	}

	/**
	 * If an product has multiple quantities split up after the order is placed.
	 *
	 * @param object $item                Cart item.
	 * @param string $cart_item_key       hash for cart item.
	 * @param array  $values              Other Values.
	 * @param object $order               quantity while adding to cart.
	 * @return void
	 */
	public function create_order_line_items( $item, $cart_item_key, $values, $order ) {

		/**
		 * Change the quantity of original item to 1.
		 *
		 * You also need to manually re calculate the subtotal,total,subtotal tax, total tax and other taxes(i have not handled taxes in the code, but you should).
		 *
		 * I have simply divided all the above cost variables with the number of qty and saved back again
		 * (at times the logic of cost calculation may change asper your scenerio)
		 */
		$qty = $item->get_quantity();
		$item->set_quantity( 1 );
		$item->set_subtotal( $values['line_subtotal'] / $qty );
		$item->set_total( $values['line_total'] / $qty );
		$item->set_subtotal_tax( $values['line_subtotal_tax'] / $qty );
		$item->set_total_tax( $values['line_tax'] / $qty );

		/**
		 * Here i am trying to create new items (one less than the above quantity).
		 */
		if ( $qty > 1 ) {
			for ( $i = 1; $i < $qty; $i++ ) {
				$new_item                       = new WC_Order_Item_Product();
				$product                        = $values['data'];
				$new_item->legacy_values        = $values; // @deprecated For legacy actions.
				$new_cart_item_key              = md5( microtime() . wp_rand( 1, 500 ) . '' );
				$new_item->legacy_cart_item_key = $new_cart_item_key; // @deprecated For legacy actions.
				$new_item->set_props(
					array(
						'quantity'     => 1,
						'variation'    => $values['variation'],
						'subtotal'     => $values['line_subtotal'] / $qty,
						'total'        => $values['line_total'] / $qty,
						'subtotal_tax' => $values['line_subtotal_tax'] / $qty,
						'total_tax'    => $values['line_tax'] / $qty,
						// 'taxes'     => $values['line_tax_data'],    //for demo this was not needed enable and recalculate if you need it.
					)
				);

				/**
				 * Setting product properties to cart item.
				 */
				if ( $product ) {
					$new_item->set_props(
						array(
							'name'         => $product->get_name(),
							'tax_class'    => $product->get_tax_class(),
							'product_id'   => $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id(),
							'variation_id' => $product->is_type( 'variation' ) ? $product->get_id() : 0,
						)
					);
				}
				$new_item->set_backorder_meta();
				$order->add_item( $new_item );
			}
		}
	}


}
