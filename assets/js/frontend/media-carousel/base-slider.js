var Base;
Base = elementorModules.frontend.handlers.Base.extend({

    getDefaultSettings() {
        return {
            selectors: {
                mainSwiper: '.elementor-main-swiper',
                swiperSlide: '.swiper-slide'
            },
            slidesPerView: {
                desktop: 3,
                tablet: 2,
                mobile: 1
            }
        };
    },

    getDefaultElements() {
        var selectors = this.getSettings('selectors');
        var elements = {
            $mainSwiper: this.$element.find(selectors.mainSwiper)
        };
        elements.$mainSwiperSlides = elements.$mainSwiper.find(selectors.swiperSlide);
        return elements;
    },

    getSlidesCount() {
        return this.elements.$mainSwiperSlides.length;
    },

    getInitialSlide() {
        var editSettings = this.getEditSettings();
        return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
    },

    getEffect() {
        return this.getElementSettings('rael_effect');
    },

    getDeviceSlidesPerView(device) {
        var slidesPerViewKey = 'rael_slides_per_view' + ('desktop' === device ? '' : '_' + device);
        return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]);
    },

    getSlidesPerView(device) {
        if ('slide' === this.getEffect()) {
            return this.getDeviceSlidesPerView(device);
        }

        return 1;
    },

    getDesktopSlidesPerView() {
        return this.getSlidesPerView('desktop');
    },

    getTabletSlidesPerView() {
        return this.getSlidesPerView('tablet');
    },

    getMobileSlidesPerView() {
        return this.getSlidesPerView('mobile');
    },

    getDeviceSlidesToScroll(device) {
        var slidesToScrollKey = 'rael_slides_to_scroll' + ('desktop' === device ? '' : '_' + device);
        return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesToScrollKey) || 1);
    },

    getSlidesToScroll(device) {
        if ('slide' === this.getEffect()) {
            return this.getDeviceSlidesToScroll(device);
        }

        return 1;
    },

    getDesktopSlidesToScroll() {
        return this.getSlidesToScroll('desktop');
    },

    getTabletSlidesToScroll() {
        return this.getSlidesToScroll('tablet');
    },

    getMobileSlidesToScroll() {
        return this.getSlidesToScroll('mobile');
    },

    getSpaceBetween(device) {
        var propertyName = 'rael_space_between';

        if (device && 'desktop' !== device) {
            propertyName += '_' + device;
        }

        return this.getElementSettings(propertyName).size || 0;
    },

    getSwiperOptions() {
        var elementSettings = this.getElementSettings(); // TODO: Temp migration for old saved values since 2.2.0

        if ('progress' === elementSettings.rael_pagination) {
            elementSettings.rael_pagination = 'progressbar';
        }

        var swiperOptions = {
            grabCursor: true,
            initialSlide: this.getInitialSlide(),
            slidesPerView: this.getDesktopSlidesPerView(),
            slidesPerGroup: this.getDesktopSlidesToScroll(),
            spaceBetween: this.getSpaceBetween(),
            loop: 'yes' === elementSettings.rael_loop,
            speed: elementSettings.rael_speed,
            effect: this.getEffect(),
            preventClicksPropagation: false,
            slideToClickedSlide: true,
            handleElementorBreakpoints: true
        };

        if (elementSettings.rael_show_arrows) {
            swiperOptions.navigation = {
                prevEl: '.elementor-swiper-button-prev',
                nextEl: '.elementor-swiper-button-next'
            };
        }

        if (elementSettings.rael_pagination) {
            swiperOptions.pagination = {
                el: '.swiper-pagination',
                type: elementSettings.rael_pagination,
                clickable: true
            };
        }

        if ('cube' !== this.getEffect()) {
            var breakpointsSettings = {},
                breakpoints = elementorFrontend.config.breakpoints;
            breakpointsSettings[breakpoints.lg - 1] = {
                slidesPerView: this.getTabletSlidesPerView(),
                slidesPerGroup: this.getTabletSlidesToScroll(),
                spaceBetween: this.getSpaceBetween('tablet')
            };
            breakpointsSettings[breakpoints.md - 1] = {
                slidesPerView: this.getMobileSlidesPerView(),
                slidesPerGroup: this.getMobileSlidesToScroll(),
                spaceBetween: this.getSpaceBetween('mobile')
            };
            swiperOptions.breakpoints = breakpointsSettings;
        }

        if (!this.isEdit && elementSettings.rael_autoplay) {
            swiperOptions.autoplay = {
                delay: elementSettings.rael_autoplay_speed,
                disableOnInteraction: !!elementSettings.rael_pause_on_interaction
            };
        }

        return swiperOptions;
    },

    updateSpaceBetween(swiper, propertyName) {
        var deviceMatch = propertyName.match('rael_space_between_(.*)'),
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
    },

    onInit() {
        var _this = this;

        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        var elementSettings = this.getElementSettings();
        this.Swipers = {};

        if (1 >= this.getSlidesCount()) {
            return;
        }

        this.Swipers.main = new RAELSwiper(this.elements.$mainSwiper, this.getSwiperOptions()); // Expose the swiper instance in the frontend

        this.elements.$mainSwiper.data('swiper', this.Swipers.main);

        if (elementSettings.rael_pause_on_hover) {
            this.elements.$mainSwiper.on({
                mouseenter: function mouseenter() {
                    _this.Swipers.main.autoplay.stop();
                },
                mouseleave: function mouseleave() {
                    _this.Swipers.main.autoplay.start();
                }
            });
        }
    },

    onElementChange(propertyName) {
        if (1 >= this.getSlidesCount()) {
            return;
        }

        if (0 === propertyName.indexOf('width')) {
            this.Swipers.main.update();
        }

        if (0 === propertyName.indexOf('rael_space_between')) {
            this.updateSpaceBetween(this.Swipers.main, propertyName);
        }
    },

    onEditSettingsChange(propertyName) {
        if (1 >= this.getSlidesCount()) {
            return;
        }

        if ('activeItemIndex' === propertyName) {
            this.Swipers.main.slideToLoop(this.getEditSettings('activeItemIndex') - 1);
        }
    }

});

module.exports = Base;