class TestimonialSliderHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        var defaultSettings = {
            selectors: {
                mainSwiper: '.responsive-testimonial-swiper',
                swiperSlide: '.swiper-slide'
            },
            slidesPerView: {
                desktop: 1,
                tablet: 1,
                mobile: 1
            }
        };
        if (defaultSettings.loop) {
            defaultSettings.loopedSlides = this.getSlidesCount();
        }
        return defaultSettings;
    }

    getEffect() {
        return 'slide';
    }

    getDefaultElements() {
        var selectors = this.getSettings('selectors');
        var elements = {
            $mainSwiper: this.$element.find(selectors.mainSwiper)
        };
        elements.$mainSwiperSlides = elements.$mainSwiper.find(selectors.swiperSlide);
        return elements;
    }

    getSlidesCount() {
        return this.elements.$mainSwiperSlides.length;
    }

    getInitialSlide() {
        var editSettings = this.getEditSettings();
        return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
    }

    getDeviceSlidesPerView(device) {
        var slidesPerViewKey = 'slides_per_view' + ('desktop' === device ? '' : '_' + device);
        return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]);
    }

    getSlidesPerView(device) {
        if ('slide' === this.getEffect()) {
            var settingKey = ('desktop' === device) ? '' : '_' + device,
                settings = this.getElementSettings(),
                slides = +settings[`slides_per_view${settingKey}`];

            switch(device) {
                case 'desktop': slides = slides || 4;
                break;
                case 'tablet': slides = slides || 3;
                break;
                case 'mobile': slides = slides || 2;
                break;
            }

            return Math.min(this.getSlidesCount(), slides);
        }
        return 1;
    }

    getSlidesToScroll(device) {
        if ('slide' === this.getEffect()) {
            var settingKey = ('desktop' === device) ? '' : '_' + device,
                settings = this.getElementSettings(),
                slides = +settings[`slides_to_scroll${settingKey}`];

            slides = slides || 1;

            return Math.min(this.getSlidesCount(), slides)
        }

        return 1;
    }

    getDesktopSlidesPerView() {
        return this.getSlidesPerView('desktop');
    }

    getTabletSlidesPerView() {
        return this.getSlidesPerView('tablet');
    }

    getMobileSlidesPerView() {
        return this.getSlidesPerView('mobile');
    }

    getDeviceSlidesToScroll(device) {
        var slidesToScrollKey = 'slides_to_scroll' + ('desktop' === device ? '' : '_' + device);
        return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesToScrollKey) || 1);
    }

    getDesktopSlidesToScroll() {
        return this.getSlidesToScroll('desktop');
    }

    getTabletSlidesToScroll() {
        return this.getSlidesToScroll('tablet');
    }

    getMobileSlidesToScroll() {
        return this.getSlidesToScroll('mobile');
    }

    getSpaceBetween(device) {
        var propertyName = 'space_between';

        if (device && 'desktop' !== device) {
            propertyName += '_' + device;
        }

        return this.getElementSettings(propertyName).size || 0;
    }

    getSwiperOptions() {
        var elementSettings = this.getElementSettings(); // TODO: Temp migration for old saved values since 2.2.0

        if ('progress' === elementSettings.pagination) {
            elementSettings.pagination = 'progressbar';
        }

        var swiperOptions = {
            grabCursor: true,
            initialSlide: this.getInitialSlide(),
            slidesPerView: this.getSlidesPerView('mobile'),
            slidesPerGroup: this.getSlidesToScroll('mobile'),
            spaceBetween: this.getSpaceBetween('mobile'),
            loop: 'yes' === elementSettings.loop,
            speed: elementSettings.speed,
            effect: this.getEffect(),
            preventClicksPropagation: false,
            slideToClickedSlide: true,
            handleElementorBreakpoints: false
        };

        if (elementSettings.show_arrows) {
            swiperOptions.navigation = {
                prevEl: '.responsive-swiper-button-prev',
                nextEl: '.responsive-swiper-button-next'
            };
        }

        if (elementSettings.pagination) {
            swiperOptions.pagination = {
                el: '.swiper-pagination',
                type: elementSettings.pagination,
                clickable: true
            };
        }

        if ('cube' !== this.getEffect()) {
            var breakpointsSettings = {},
                breakpoints = elementorFrontend.config.breakpoints;

            breakpointsSettings[breakpoints.md] = {
                slidesPerView: this.getSlidesPerView('tablet'),
                slidesPerGroup: this.getSlidesToScroll('tablet'),
                spaceBetween: this.getSpaceBetween('tablet')
            };
                breakpointsSettings[breakpoints.lg - 1] = {
                slidesPerView: this.getSlidesPerView('desktop'),
                slidesPerGroup: this.getSlidesToScroll('desktop'),
                spaceBetween: this.getSpaceBetween('desktop')
            };

            swiperOptions.breakpoints = breakpointsSettings;
        }


        if (elementSettings.autoplay) {
            swiperOptions.autoplay = {
                delay: elementSettings.autoplay_speed,
                disableOnInteraction: !!elementSettings.pause_on_interaction
            };
        }

        return swiperOptions;
    }

    updateSpaceBetween(swiper, propertyName) {
        var deviceMatch = propertyName.match('space_between_(.*)'),
            device = deviceMatch ? deviceMatch[1] : 'desktop',
            newSpaceBetween = this.getSpaceBetween(device),
            breakpoints = elementorFrontend.config.breakpoints;

        if ('desktop' !== device) {
            var breakpointDictionary = {
                tablet: breakpoints.lg - 1,
                mobile: breakpoints.md - 1
            };
            swiper.params.breakpoints[breakpointDictionary[device]].spaceBetween = newSpaceBetween;
        } else {
            swiper.originalParams.spaceBetween = newSpaceBetween;
        }

        swiper.params.spaceBetween = newSpaceBetween;
        swiper.update();
    }

    onInit() {
        var _this = this;

        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        var elementSettings = this.getElementSettings();
        this.swipers = {};

        if (1 >= this.getSlidesCount()) {
            return;
        }

        this.swipers.main = new RAELSwiper(this.elements.$mainSwiper, this.getSwiperOptions()); // Expose the swiper instance in the frontend

        this.elements.$mainSwiper.data('swiper', this.swipers.main);

        if (elementSettings.pause_on_hover) {
            this.elements.$mainSwiper.on({
                mouseenter: function mouseenter() {
                    _this.swipers.main.autoplay.stop();
                },
                mouseleave: function mouseleave() {
                    _this.swipers.main.autoplay.start();
                }
            });
        }
    }

    onElementChange(propertyName) {
        if (1 >= this.getSlidesCount()) {
            return;
        }

        if (0 === propertyName.indexOf('width')) {
            this.swipers.main.update();
        }

        if (0 === propertyName.indexOf('space_between')) {
            this.updateSpaceBetween(this.swipers.main, propertyName);
        }
    }

    onEditSettingsChange(propertyName) {
        if (1 >= this.getSlidesCount()) {
            return;
        }

        if ('activeItemIndex' === propertyName) {
            this.swipers.main.slideToLoop(this.getEditSettings('activeItemIndex') - 1);
        }
    }
}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(TestimonialSliderHandler, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/rael-testimonial-slider.default",
        addHandler
    );

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/rael-reviews.default",
        addHandler
    );
});