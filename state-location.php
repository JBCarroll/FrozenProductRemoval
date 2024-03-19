<?php

function formCheckerScripts() {
  wp_enqueue_script( 'formChecker', plugin_dir_url( __FILE__ ) . 'formChecker.js', array( 'jquery' ), '1.0', true );
  wp_localize_script('formChecker', 'wc_cart_params', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'formCheckerScripts' );


// Hook into AJAX action to update frozen tag products
add_action('wp_ajax_update_frozen_tag_products', 'remove_frozen_tag_products_from_cart');
add_action('wp_ajax_nopriv_update_frozen_tag_products', 'remove_frozen_tag_products_from_cart');

// Function to remove Frozen tag products from the cart
function remove_frozen_tag_products_from_cart() {
  // Check if the cart is empty or not
  if (WC()->cart->is_empty()) {
      return;
  }
  $shipping_state = WC()->customer->get_shipping_state();
  // Get the cart contents
  $cart_items = WC()->cart->get_cart();

  if($shipping_state !== 'FS'){
    // Loop through each cart item
      foreach ($cart_items as $cart_item_key => $cart_item) {
        // Check if the product has the 'Frozen' tag
        if (has_term('Frozen', 'product_tag', $cart_item['product_id'])) {
            // Remove the product from the cart
            print_r($cart_item['product_id'].'ID');
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }
  }
}

?>
