export var QuickView = {
    quickViewAddMarkup: function($scope, jq) {
        var popupMarkup = `
            <div style="display: none;" class="rael-woocommerce-popup-view rael-pc__product-popup rael-pc__product-zoom-in woocommerce">
                <div class="rael-pc__product-modal-bg"></div>
                <div class="rael-pc__popup-details-render rael-pc__slider-popup">
                    <div class="rael-pc__preloader"></div>
                </div>
            </div>
            `;
    
        if (!jq('body > .rael-woocommerce-popup-view').length) {
          jq('body').prepend(popupMarkup);
        }
    },
    openPopup: function($scope, $) {
        // Quick view
        $scope.on('click', '.open-popup-link', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $this = $(this);
            var quickview_setting = $this.data('quickview-setting');
            var popup_view = $('.rael-woocommerce-popup-view');
            popup_view.find('.rael-pc__popup-details-render').html('<div class="rael-pc__preloader"></div>');
            popup_view.addClass('rael-pc__product-popup-ready').removeClass('rael-pc__product-modal-removing');
            popup_view.show();
            $.ajax({
            url: localize.ajaxurl,
            type: 'post',
            data: {
                action: 'rael_product_quickview_popup',
                ...quickview_setting,
                security: localize.nonce
            },
            success: function success(response) {
                if (response.success) {
                var product_preview = $(response.data);
                var popup_details = product_preview.children('.rael-pc__product-popup-details');
                popup_details.find('.variations_form').wc_variation_form();
                var popup_view_render = popup_view.find('.rael-pc__popup-details-render');
                popup_view.find('.rael-pc__popup-details-render').html(popup_details);
                var product_gallery = popup_view.find('.woocommerce-product-gallery');
                product_gallery.css('opacity', 1);
                popup_view_render.addClass('elementor-' + quickview_setting.page_id);
                popup_view_render.children().addClass('elementor-element elementor-element-' + quickview_setting.widget_id);

                if (popup_details.height() > 400) {
                    popup_details.css('height', '75vh');
                } else {
                    popup_details.css('height', 'auto');
                }

                setTimeout(function () {
                    product_gallery.wc_product_gallery();
                }, 1000);
                }
            }
            });
        });
    },
    closePopup: function($scope, jq) {
        jq(document).on('click', '.rael-pc__product-popup-close', function (event) {
          event.stopPropagation();
          QuickView.remove_product_popup(jq);
        });
        jq(document).on('click', function (event) {
          if (event.target.closest('.rael-pc__product-popup-details')) return;
          QuickView.remove_product_popup(jq);
        });
    },
    singlePageAddToCartButton: function($scope, $) {
        $(document).on('click', '.rael-pc__slider-popup .single_add_to_cart_button', function (e) {
          e.preventDefault();
          e.stopImmediatePropagation();
          var $this = $(this),
              product_id = $(this).val(),
              variation_id = $this.closest('form.cart').find('input[name="variation_id"]').val() || '',
              quantity = $this.closest('form.cart').find('input[name="quantity"]').val(),
              items = $this.closest('form.cart.grouped_form'),
              form = $this.closest('form.cart'),
              product_data = [];
          items = items.serializeArray();
    
          if (form.hasClass('variations_form')) {
            product_id = form.find('input[name="product_id"]').val();
          }
    
          if (items.length > 0) {
            items.forEach(function (item, index) {
              var p_id = parseInt(item.name.replace(/\D*/g, ''), 10);
    
              if (item.name.indexOf('quantity[') >= 0 && item.value != '' && p_id > 0) {
                product_data[product_data.length] = {
                  product_id: p_id,
                  quantity: item.value,
                  variation_id: 0
                };
              }
            });
          } else {
            product_data[0] = {
              product_id: product_id,
              quantity: quantity,
              variation_id: variation_id
            };
          }
    
          $this.removeClass('rael-pc-addtocart-added');
          $this.addClass('rael-pc-addtocart-loading');
          $.ajax({
            url: localize.ajaxurl,
            type: 'post',
            data: {
              action: 'rael_product_add_to_cart',
              product_data: product_data,
              nonce: localize.nonce,
              cart_item_data: form.serializeArray()
            },
            success: function success(response) {
              if (response.success) {
                $(document.body).trigger('wc_fragment_refresh');
                $this.removeClass('rael-pc-addtocart-loading');
                $this.addClass('rael-pc-addtocart-added');
              }
            }
          });
        });
    },
    preventStringInNumberField: function($scope, $) {
        $(document).on('keypress', '.rael-pc__product-details-wrapper input[type=number]', function (e) {
          var keyValue = e.keyCode || e.which;
          var regex = /^[0-9]+$/;
          var isValid = regex.test(String.fromCharCode(keyValue));
    
          if (!isValid) {
            return false;
          }
    
          return isValid;
        });
    },
    remove_product_popup: function(jq) {
        var selector = jq('.rael-pc__product-popup.rael-pc__product-zoom-in.rael-pc__product-popup-ready');
        selector.addClass('rael-pc__product-modal-removing').removeClass('rael-pc__product-popup-ready');
        selector.find('.rael-pc__popup-details-render').html('');
    }
}