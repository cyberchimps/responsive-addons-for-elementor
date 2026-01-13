var WooCheckout = function ( $scope, $ )
{
  $.blockUI.defaults.overlayCSS.cursor = "default";

  function render_order_review_template()
  {
    var wooCheckout = $( ".rael-woo-checkout" );

    setTimeout( function ()
    {
      $( ".rael-checkout-review-order-table" )
        .addClass( "processing" )
        .block(
        {
          message: null,
          overlayCSS:
          {
            background: "#fff",
            opacity: 0.6,
          },
        } );
      $.ajax(
      {
        type: "POST",
        url: localize.ajaxurl,
        data:
        {
          action: "woo_checkout_update_order_review",
          orderReviewData: wooCheckout.data( "checkout" ),
        },

        success: function ( data )
        {
          $( ".rael-checkout-review-order-table" ).replaceWith( data.order_review );
          setTimeout( function ()
          {
            $( ".rael-checkout-review-order-table" ).removeClass( "processing" ).unblock();
          }, 3000 );
        },
      } );
    }, 2000 );
  }

  $( document ).on( "click", ".woocommerce-remove-coupon", function ( e )
  {
    render_order_review_template();
  } );

  $( "form.checkout_coupon" ).submit( function ( event )
  {
    render_order_review_template();
  } );
  var wooCheckout = $( ".rael-woo-checkout" );
  wooCheckout.on( 'change', 'select.shipping_method, input[name^="shipping_method"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type="radio"], .update_totals_on_change input[type="checkbox"]', function ()
  {
    $( document.body ).trigger( 'update_checkout' );
    render_order_review_template();
  } ); // eslint-disable-line max-len

  $( document.body ).bind( 'update_checkout', () =>
  {
    render_order_review_template();
  } );

  //move coupon remove message to coupon box (multi step and split layout)
  $( document.body ).on( 'removed_coupon_in_checkout', function ()
  {
    let message = $( '.rael-woo-checkout .ms-tabs-content > .woocommerce-message,.rael-woo-checkout .split-tabs-content > .woocommerce-message' ).remove();
    $( '.rael-woo-checkout .checkout_coupon.woocommerce-form-coupon' ).before( message );
  } );
};

jQuery( window ).on( "elementor/frontend/init", function ()
{
  elementorFrontend.hooks.addAction( "frontend/element_ready/rael-woocommerce-checkout.default", WooCheckout );
} );
