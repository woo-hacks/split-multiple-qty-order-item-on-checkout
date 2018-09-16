<?php
/**
 * Split after checkout.
 *
 * @package Split_Multiple_Qty_Order_Item_On_Checkout
 */

/**
 * Unit test case for split cart.
 */
class Split_Multiple_Qty_Order_Item_On_Checkout_Test extends WC_Unit_Test_Case {

	/**
	 * Test test_woo_split_when_multiple_quanity_added().
	 *
	 * @since 0.0.2
	 */
	public function test_split_multiple_qty_order_item_on_checkout() {
		$order = new WC_Order();

		$product = WC_Helper_Product::create_simple_product();

		// $order = WC_Helper_Order::create_order(); this add a order item by default.
		$order = new WC_Order();

		$checkout = WC()->checkout();

		// empty cart before adding new item.
		wc_empty_cart();

		// add the product with 3 quantity.
		WC()->cart->add_to_cart( $product->get_id(), 3 );

		$this->assertEquals( 3, WC()->cart->get_cart_contents_count() );

		// Assert there is no order item present in the order before create_order_line_items.
		$this->assertEquals( 0, count( $order->get_items() ) );

		// create order line item from the cart item.
		$checkout->create_order_line_items( $order, WC()->cart );

		// Asset the count of items to check if new items where created.
		$this->assertEquals( 3, count( $order->get_items() ) );

		/* you must also add assertions for the price calculated. */

	}
}
