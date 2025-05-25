var RAELLogoCarouselHandler = function($scope, $) {
    if ('undefined' == typeof $scope) {
        return;
    }

    var $carousel = $scope.find('.rael-logo-carousel').eq(0),
        $effect = undefined !== $carousel.data('effect') ? $carousel.data('effect') : 'slide',
        $items = undefined !== $carousel.data('items') ? $carousel.data('items') : 3,
        $items_tablet = undefined !== $carousel.data('items-tablet') ? $carousel.data('items-tablet') : 3,
        $items_mobile = undefined !== $carousel.data('items-mobile') ? $carousel.data('items-mobile') : 1,
        $items_gap = undefined !== $carousel.data('items-gap') ? $carousel.data('items-gap') : 10,
        $items_gap_tablet = undefined !== $carousel.data('items-gap-tablet') ? $carousel.data('items-gap-tablet') : 10,
        $items_gap_mobile = undefined !== $carousel.data('items-gap-mobile') ? $carousel.data('items-gap-mobile') : 10,
        $speed = undefined !== $carousel.data('speed') ? $carousel.data('speed') : 400,
        $autoplay = undefined !== $carousel.data('autoplay-speed') ? $carousel.data('autoplay-speed') : 999999,
        $loop = undefined !== $carousel.data('loop') ? $carousel.data('loop') : 0,
        $grab_cursor = undefined !== $carousel.data('grab-cursor') ? $carousel.data('grab-cursor') : 0,
        $pagination = undefined !== $carousel.data('pagination') ? $carousel.data('pagination') : '.swiper-pagination',
        $arrow_next = undefined !== $carousel.data('arrow-next') ? $carousel.data('arrow-next') : '.swiper-button-next',
        $arrow_prev = undefined !== $carousel.data('arrow-prev') ? $carousel.data('arrow-prev') : '.swiper-button-prev',
        $pause_on_hover = undefined !== $carousel.data('pause-on-hover') ? $carousel.data('pause-on-hover') : '';
    
    var carousel_options = {
        direction: 'horizontal',
        autoHeight: true,
        effect: $effect,
        speed: $speed,
        grabCursor: $grab_cursor,
        paginationClickable: true,
        loop: $loop,
        observer: true,
        observeParents: true,
        autoplay: {
            delay: $autoplay,
            pauseOnMouseEnter: $pause_on_hover
        },
        pagination: {
            clickable: true,
            el: $pagination
        },
        navigation: {
            nextEl: $arrow_next,
            prevEl: $arrow_prev
        }
    };

    if ('slide' === $effect || 'coverflow' === $effect) {
        carousel_options.breakpoints = {
            320: {
                slidesPerView: $items_mobile,
                spaceBetween: $items_gap_mobile
            },
            768: {
                slidesPerView: $items_tablet,
                spaceBetween: $items_gap_tablet
            },
            1024: {
                slidesPerView: $items,
                spaceBetween: $items_gap
            }
        }
    } else {
        carousel_options.items = 1;
    }

    let swiper = new RAELSwiper($carousel, carousel_options);
  
    if ($pause_on_hover) {
        $carousel.on({
            mouseenter: () => {
                swiper.autoplay.stop();
            },
            mouseleave: () => {
                swiper.autoplay.start();
            }
        });
    }
}

jQuery(window).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/rael-logo-carousel.default', RAELLogoCarouselHandler );
});
