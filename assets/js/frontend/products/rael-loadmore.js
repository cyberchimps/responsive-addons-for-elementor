(function($) {
    "use strict";

    $(document).on('click', '.rael-products__load-more-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    
        var $this = $(this),
            $loaderSpan = $('span', $this),
            $text = $loaderSpan.html(),
            $widgetId = $this.data('widget-id'),
            $pageId = $this.data('page-id'),
            $nonce = $this.data('nonce'),
            $scope = $(`.elementor-element-${$widgetId}`),
            $class = $this.data('class'),
            $args = $this.data('args'),
            $layout = $this.data('layout'),
            $template = $this.data('template'),
            $page = parseInt($this.data('page')) + 1,
            $maxPage = undefined != $this.data('maxPage') ? $this.data('maxPage') : false;
    
        if ('undefined' === typeof $widgetId || 'undefined' === typeof $args) {
            return;
        }
    
        var ob = {};
        var data = {
            action: 'rael_load_more',
            class: $class,
            args: $args,
            page: $page,
            page_id: $pageId,
            widget_id: $widgetId,
            nonce: $nonce,
            template: $template
        };
    
        String($args).split('&').forEach(function (item, index) {
            var arr = String(item).split('=');
            ob[arr[0]] = arr[1];
        });
    
        $this.addClass('rael-products__load-more-button--loading');
        $loaderSpan.html(localize.i18n.loading);
    
        $.ajax({
            url: localize.ajaxurl,
            type: "POST",
            data: data,
            success: function(response) {
                var $content = $(response);
    
                if ($content.hasClass('no-posts-found') || 0 === $content.length) {
                    $this.remove();
                } else {
                    // Load more implementation is currently for RAEL Products only.
                    $content = $content.filter('li');
                    $('.rael-products .products', $scope).append($content);

                    if ('masonry' === $layout) {
                        var dynamicID = `rael-products-${Date.now()}`;
                        var $isotope = $('.rael-products .products', $scope).isotope();
                        
                        $isotope.isotope('appended', $content).isotope('layout');
                        $isotope.imagesLoaded().progress(function() {
                            $isotope.isotope('layout');
                        });
                        $content.find('.woocommerce-product-gallery').addClass(dynamicID).addClass('rael-new-product');
                        $('.woocommerce-product-gallery.' + dynamicID, $scope).each(function() {
                            $(this).wc_product_gallery();
                        });
                    } else {
                        var _dynamicID = `rael-products-${Date.now()}`;
                        $content.find('.woocommerce-product-gallery').addClass(_dynamicID).addClass('rael-new-product');    
                        
                        $('.woocommerce-product-gallery.' + dynamicID, $scope).each(function() {
                            $(this).wc_product_gallery();
                        });
                    }
                    
                    $this.removeClass('rael-products__load-more-button--loading');
                    $loaderSpan.html($text);
    
                    $this.data('page', $page);
    
                    if ($maxPage && data.page >= $maxPage) {
                        $this.remove();
                    }
                }
            },
            error: function(response) {
                // Definition should be for development purpose only.
            }
        });
    });
})(jQuery);
