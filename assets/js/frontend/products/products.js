import { QuickView } from "../product-carousel/quick-view";

var RAELProductsHandler = function($scope, $) {
    if ('undefined' === typeof $scope) {
        return;
    }

    elementorFrontend.hooks.doAction('RAELProductsQuickViewAddMarkup', $scope, $);
    
    var $widget = $scope.find('#rael-products'),
        widgetId = $widget.data('widget-id'),
        pageId = $widget.data('page-id'),
        nonce = $widget.data('nonce'),
        overlay = document.createElement('div'),
        body = document.getElementsByTagName('body')[0],
        $doc = $(document);
    
    overlay.classList.add('rael-products-compare__overlay');
    overlay.setAttribute('id', 'rael-products-overlay');
    body.appendChild(overlay);

    var overlayNode = document.getElementById('rael-products-overlay');

    var loader = false,
        compareBtn = false,
        hasCompareIcon = false,
        compareBtnSpan = false,
        requestType = false;  // compare | remove

    var iconBeforeCompare = '<i class="fas fa-exchange-alt"></i>';
    var iconAfterCompare = '<i class="fas fa-check-circle"></i>';

    var modalTemplate = `
        <div class="rael-products-compare-modal">
            <i title="Close" class="rael-products-compare-modal-close far fa-times-circle"></i>
            <div class="rael-products-compare-modal__content" id="rael-products-compare-modal-content"></div>
        </div>
    `;

    $(body).append(modalTemplate);

    var $modalContentWrapper = $('#rael-products-compare-modal-content');
    var modal = document.getElementsByClassName("rael-products-compare-modal")[0];

    var ajaxData = [
        {
            name: "action",
            value: "rael_products_compare"
        }, 
        {
            name: "widget_id",
            value: widgetId
        }, 
        {
            name: "page_id",
            value: pageId
        }, 
        {
            name: "nonce",
            value: nonce
        }
    ];

    var sendData = function(ajaxData, successCb, errorCb, beforeCb, completeCb) {
        $.ajax({
          url: localize.ajaxurl,
          type: "POST",
          dataType: "json",
          data: ajaxData,
          beforeSend: beforeCb,
          success: successCb,
          error: errorCb,
          complete: completeCb
        });
    };

    $doc.on('click', '.rael-wc-compare', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        requestType = 'compare';
        compareBtn = $(this);

        var compareBtnText = compareBtn.find('.rael-wc-compare-text');

        if (!compareBtnText.length) {
            hasCompareIcon = compareBtn.hasClass('rael-wc-compare-icon');
        }

        if (!hasCompareIcon) {
            loader = compareBtn.find('rael-products__loader');
            loader.show();
        }

        var productId = compareBtn.data('product-id');
        var oldProductIds = localStorage.getItem('productIds');
  
        if (oldProductIds) {
          oldProductIds = JSON.parse(oldProductIds);
          oldProductIds.push(productId);
        } else {
          oldProductIds = [productId];
        }
  
        ajaxData.push({
          name: "product_id",
          value: compareBtn.data('product-id')
        });

        ajaxData.push({
          name: "product_ids",
          value: JSON.stringify(oldProductIds)
        });

        sendData(ajaxData, handleSuccess, handleError);
    });

    $doc.on("click", ".rael-products-compare-modal", function (e) {
        modal.style.visibility = "hidden";
        modal.style.opacity = "0";
        overlayNode.style.visibility = "hidden";
        overlayNode.style.opacity = "0";
    });

    $doc.on('click', '.rael-wc-remove', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var $rBtn = $(this);
        var productId = $rBtn.data('product-id');
        $rBtn.addClass('disable');
        $rBtn.prop('disabled', true); // prevent additional ajax request
  
        var oldProductIds = localStorage.getItem('productIds');
  
        if (oldProductIds) {
          oldProductIds = JSON.parse(oldProductIds);
          oldProductIds.push(productId);
        } else {
          oldProductIds = [productId];
        }
  
        var rmData = Array.from(ajaxData);
        
        rmData.push({
            name: "product_id",
            value: productId
        });
        
        rmData.push({
            name: "remove_product",
            value: 1
        });
        
        rmData.push({
            name: "product_ids",
            value: JSON.stringify(oldProductIds)
        });

        requestType = 'remove';
        var compareBtn = $('button[data-product-id="' + productId + '"]');
        compareBtnSpan = compareBtn.find('.rael-wc-compare-text');
  
        if (!compareBtnSpan.length) {
            hasCompareIcon = compareBtn.hasClass('rael-wc-compare-icon');
        }
  
        sendData(rmData, handleSuccess, handleError);
    });

    function handleSuccess(data) {
        var success = data && data.success;
  
        if (success) {
          $modalContentWrapper.html(data.data.compare_table);
          modal.style.visibility = 'visible';
          modal.style.opacity = '1';
          overlayNode.style.visibility = 'visible';
          overlayNode.style.opacity = '1';
          localStorage.setItem('productIds', JSON.stringify(data.data.product_ids));
        }
  
        if (loader) {
          loader.hide();
        }
  
        if ('compare' === requestType) {
          if (compareBtnSpan && compareBtnSpan.length) {
            compareBtnSpan.text(localize.i18n.added);
          } else if (hasCompareIcon) {
            compareBtn.html(iconAfterCompare);
          }
        }
  
        if ('remove' === requestType) {
          if (compareBtnSpan && compareBtnSpan.length) {
            compareBtnSpan.text(localize.i18n.compare);
          } else if (hasCompareIcon) {
            compareBtn.html(iconBeforeCompare);
          }
        }
    }
  
    function handleError(xhr, err) {
        // Definition should be for development purpose only.
    }

    /* ---------------------------------------
     * Masonry Layout/ Isotope Init
     * ------------------------------------- */
    var $products = $('.rael-products .products', $scope);
    var $layout_mode = $products.data('layout-mode');	
    if ( 'masonry' === $layout_mode ) {
        // init isotope
        var $isotope_products = $products.isotope();
        $isotope_products.imagesLoaded().progress( function() {
            $isotope_products.isotope('layout');
        })

        $(window).on('resize', function() {
            $isotope_products.isotope('layout');
        });
    }

    $(".rael-products__pagination", $scope).on('click', 'a', function (e) {
        e.preventDefault();
        var $this = $(this),
            nth = $this.data('pnumber'),
            lmt = $this.data('plimit'),
            ajaxUrl = localize.ajaxurl,
            args = $this.data('args'),
            settings = $this.data('settings'),
            widgetId = $this.data('widget-id'),
            widgetClass = '.elementor-element-' + widgetId,
            templateInfo = $this.data('template');
        
        $.ajax({
            url: ajaxUrl,
            type: 'post',
            data: {
                action: 'rael_products_pagination_product',
                number: nth,
                limit: lmt,
                args: args,
                template: templateInfo,
                settings: settings,
                nonce: localize.nonce
            },
            beforeSend: function beforeSend() {
                $(widgetClass).addClass("rael-products__loader");
            },
            success: function success(response) {
                $(widgetClass + " .rael-products .products").html(response);
                $(widgetClass + " .woocommerce-product-gallery").each(function () {
                    $(this).wc_product_gallery();
                });

                $('html, body').animate({
                scrollTop: $(widgetClass + " .rael-products").offset().top - 50
                }, 500);
            },
            complete: function complete() {
                $(widgetClass).removeClass('rael-products__loader');
            }
        });

        $.ajax({
            url: ajaxUrl,
            type: 'post',
            data: {
                action: "rael_woo_product_pagination",
                number: nth,
                limit: lmt,
                args: args,
                settings: settings
            },
            success: function success(response) {
                $(widgetClass + " .rael-products .rael-products__pagination").html(response);
                $('html, body').animate({
                scrollTop: $(widgetClass + " .rael-products").offset().top - 50
                }, 500);
            }
        });
    });

    elementorFrontend.hooks.doAction('RAELProductsQuickViewPopupInit', $scope, $);

    if (elementorFrontend.isEditMode()) $('.rael-products__product-image-wrapper .woocommerce-product-gallery').css('opacity', '1');

    var popup = $(document).find('.rael-woocommerce-popup-view');

    if (popup.length < 1) {
        add_popup();
    }

    function add_popup() {
        var $markup = `
            <div style="display:none;" class="rael-woocommerce-popup-view rael-pc__product-popup rael-pc__product-zoom-in woocommerce">
                <div class="rael-pc__product-modal-bg"></div>
                <div class="rael-pc__popup-details-render rael-pc__slider-popup">
                    <div class="rael-pc__preloader"></div> 
                </div>
            </div>
        `;

        $('body').append($markup);
    }

    
}


jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/rael-woocommerce-products.default', RAELProductsHandler);

    elementorFrontend.hooks.addAction('RAELProductsQuickViewAddMarkup', QuickView.quickViewAddMarkup, 10);
    elementorFrontend.hooks.addAction('RAELProductsQuickViewPopupInit', QuickView.openPopup, 10);
    elementorFrontend.hooks.addAction('RAELProductsQuickViewPopupInit', QuickView.closePopup, 10);
    elementorFrontend.hooks.addAction('RAELProductsQuickViewPopupInit', QuickView.singlePageAddToCartButton, 10);
    elementorFrontend.hooks.addAction('RAELProductsQuickViewPopupInit', QuickView.preventStringInNumberField, 10);
});