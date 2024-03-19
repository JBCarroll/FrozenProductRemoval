
jQuery(document).ready(function($) {
  console.log('LOADED')
  // Listen for changes in the shipping form
  $(document.body).on('submit', 'form.woocommerce-shipping-calculator', function() {
      // Trigger AJAX request to update cart
      $(document.body).trigger('update_frozen_tag_products');
  });

  // Custom event to update cart for frozen tag products
  $(document.body).on('update_frozen_tag_products', function(e) {
    console.log(e)
      // Get selected state value
      var selectedState = $('select#calc_shipping_state').val();
      // Check if selected state is not 'FS'
      if (selectedState !== 'FS') {
        $.ajax({
            type: 'POST',
            url: wc_cart_params.ajax_url,
            data: {
                action: 'update_frozen_tag_products'
            },
            success: function(response) {
                // Refresh cart fragments
                if (response && response.fragments) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    $(document.body).trigger('wc_fragment_refresh');
                }
            }
        });
      }
  });
});
