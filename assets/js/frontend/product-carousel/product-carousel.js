import { QuickView } from "./quick-view";

var RAELProductCarouselHandler = function($scope, $) {
    if ('undefined' === typeof $scope) {
        return;
    }

    elementorFrontend.hooks.doAction('quickViewAddMarkup', $scope, $);

    var $productCarousel = $scope.find('.rael-woo-product-carousel').eq(0),
        type = $productCarousel.data('type'),
        autoplay = (undefined !== $productCarousel.data('autoplay')) ? $productCarousel.data('autoplay') : 999999,
        pagination = (undefined !== $productCarousel.data('pagination')) ? $productCarousel.data('pagination') : '.swiper-pagination',
        arrowNext = (undefined !== $productCarousel.data('arrow-next')) ? $productCarousel.data('arrow-next') : '.swiper-button-next',
        arrowPrev = (undefined !== $productCarousel.data('arrow-prev')) ? $productCarousel.data('arrow-prev') : '.swiper-button-prev',
        items = (undefined !== $productCarousel.data('items')) ? $productCarousel.data('items') : 3,
        itemsTablet = (undefined !== $productCarousel.data('items-tablet')) ? $productCarousel.data('items-tablet') : 3,
        itemsMobile = (undefined !== $productCarousel.data('items-mobile')) ? $productCarousel.data('items-mobile') : 3,
        margin = (undefined !== $productCarousel.data('margin')) ? $productCarousel.data('margin') : 10,
        marginTablet = (undefined !== $productCarousel.data('margin-tablet')) ? $productCarousel.data('margin-tablet') : 10,
        marginMobile = (undefined !== $productCarousel.data('margin-mobile')) ? $productCarousel.data('margin-mobile') : 0,
        effect = (undefined !== $productCarousel.data('effect')) ? $productCarousel.data('effect') : 'slide',
        speed = (undefined !== $productCarousel.data('speed')) ? $productCarousel.data('speed') : 400,
        loop = (undefined !== $productCarousel.data('loop')) ? $productCarousel.data('loop') : 0,
        grabCursor = (undefined !== $productCarousel.data('grab-cursor')) ? $productCarousel.data('grab-cursor') : 0,
        pauseOnHover = (undefined !== $productCarousel.data('pause-on-hover')) ? $productCarousel.data('pause-on-hover') : '',
        centeredSlides = 'coverflow' === effect ? true : false,
        depth = (undefined !== $productCarousel.data('depth')) ? $productCarousel.data('depth') : 100,
        rotate = (undefined !== $productCarousel.data('rotate')) ? $productCarousel.data('rotate') : 50,
        stretch = (undefined !== $productCarousel.data('stretch')) ? $productCarousel.data('stretch') : 10;
    
    var swiperLoader = function(swiperElement, swiperConfig) {
        if ('undefined' === typeof Swiper) {
            var asyncSwiper = elementorFrontend.utils.swiper;
            return new asyncSwiper(swiperElement, swiperConfig).then(function(newSwiperInstance) {
                return newSwiperInstance;
            });
        } else {
            return swiperPromise(swiperElement, swiperConfig);
        }
    }

    var swiperPromise = function(swiperElement, swiperConfig) {
        return new Promise(function(resolve, reject) {
            var swiperInstance = new RAELSwiper(swiperElement, swiperConfig);
            resolve(swiperInstance);
        })
    };

    var options = {
        direction: "horizontal",
        speed: speed,
        effect: effect,
        centeredSlides: centeredSlides,
        grabCursor: grabCursor,
        autoHeight: true,
        loop: loop,
        slidesPerGroup: 1,
        autoplay: {
            delay: autoplay,
            disableOnInteraction: false,
        },
        pagination: {
            el: pagination,
            clickable: true,
        },
        navigation: {
            nextEl: arrowNext,
            prevEl: arrowPrev,
        },
        slidesPerView: items
    };

    if ('slide' === effect) {
        options.breakpoints = {
            1024: {
                slidesPerView: items,
                spaceBetween: margin,
            },
            768: {
                slidesPerView: itemsTablet,
                spaceBetween: marginTablet,
            },
            320: {
                slidesPerView: itemsMobile,
                spaceBetween: marginMobile,
            }
        };
    }

    if ('coverflow' === effect) {
        options.coverflowEffect = {
            rotate: rotate,
            stretch: stretch,
            depth: depth,
            modifier: 1,
            slideShadow: false,
        };

        options.breakpoints = {
            1024: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 1,
            },
            320: {
                slidesPerView: 1,
            }
        };
    }

    swiperLoader($productCarousel, options).then(function(carousel) {
        if (0 === autoplay) {
            carousel.autoplay.stop();
        }

        if (pauseOnHover && 0 !== autoplay) {
            $productCarousel.on('mouseenter', function() {
                carousel.autoplay.stop();
            });
            $productCarousel.on('mouseleave', function() {
                carousel.autoplay.start();
            })
        }

        var $paginationGallerySelector = $scope.find('.rael-product-carousel-container .rael-pc__gallery-pagination').eq(0); 
    
        if ($paginationGallerySelector.length > 0) {
            swiperLoader($paginationGallerySelector, {
                spaceBetween: 20,
                centeredSlides: centeredSlides,
                touchRatio: 0.2,
                slideToClickedSlide: true,
                loop: loop,
                slidesPerGroup: 1,
                slidesPerView: 3,
            }).then(function($paginationGallerySlider) {
                carousel.controller.control = $paginationGallerySlider;
                $paginationGallerySlider.controller.control = carousel;
            });
        }
    });

    elementorFrontend.hooks.doAction('quickViewPopupInit', $scope, $);

    if (elementorFrontend.isEditMode()) $('.rael-pc__product-image-wrapper .woocommerce-product-gallery').css('opacity', '1');

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
    
    // Hides the anchor tag for the product which has no price.
    for (let i = 0; i < $('.rael-product-carousel .add-to-cart a').length; i++) {
        let $currentAnchor = $($('.rael-product-carousel .add-to-cart a')[i]);
        if ($currentAnchor.hasClass('product_type_simple') && !$currentAnchor.hasClass('add_to_cart_button')) {
            $currentAnchor.hide();
        }
    }    

}

jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/rael-product-carousel.default', RAELProductCarouselHandler);

    elementorFrontend.hooks.addAction('quickViewAddMarkup', QuickView.quickViewAddMarkup, 10);
    elementorFrontend.hooks.addAction('quickViewPopupInit', QuickView.openPopup, 10);
    elementorFrontend.hooks.addAction('quickViewPopupInit', QuickView.closePopup, 10);
    elementorFrontend.hooks.addAction('quickViewPopupInit', QuickView.singlePageAddToCartButton, 10);
    elementorFrontend.hooks.addAction('quickViewPopupInit', QuickView.preventStringInNumberField, 10);
});