import Base from './base-slider'

var MediaCarouselHandler;
MediaCarouselHandler = Base.extend({

    slideshowSpecialElementSettings: [
        'rael_slides_per_view',
        'rael_slides_per_view_tablet',
        'rael_slides_per_view_mobile'
    ],

    isSlideshow() {
        return 'slideshow' === this.getElementSettings('rael_skin');
    },

    getDefaultSettings() {
        var defaultSettings = Base.prototype.getDefaultSettings.apply(this, arguments);

        if (this.isSlideshow()) {
            defaultSettings.selectors.thumbsSwiper = '.rael-elementor-thumbnails-swiper';
            defaultSettings.slidesPerView = {
                desktop: 5,
                tablet: 4,
                mobile: 3
            };
        }

        return defaultSettings;
    },

    getElementSettings(setting) {
        if (-1 !== this.slideshowSpecialElementSettings.indexOf(setting) && this.isSlideshow()) {
            setting = 'rael_slideshow_' + setting;
        }

        return Base.prototype.getElementSettings.call(this, setting);
    },

    getDefaultElements() {
        var selectors = this.getSettings('selectors'),
            defaultElements = Base.prototype.getDefaultElements.apply(this, arguments);

        if (this.isSlideshow()) {
            defaultElements.$thumbsSwiper = this.$element.find(selectors.thumbsSwiper);
        }

        return defaultElements;
    },

    getEffect() {
        if ('coverflow' === this.getElementSettings('rael_skin')) {
            return 'coverflow';
        }

        return Base.prototype.getEffect.apply(this, arguments);
    },

    getSlidesPerView(device) {
        if (this.isSlideshow()) {
            return 1;
        }

        if ('coverflow' === this.getElementSettings('rael_skin')) {
            return this.getDeviceSlidesPerView(device);
        }

        return Base.prototype.getSlidesPerView.apply(this, arguments);
    },

    getSwiperOptions() {
        var options = Base.prototype.getSwiperOptions.apply(this, arguments);

        if (this.isSlideshow()) {
            options.loopedSlides = this.getSlidesCount();
            delete options.pagination;
            delete options.breakpoints;
        }

        return options;
    },

    onInit() {
        Base.prototype.onInit.apply(this, arguments);
        var slidesCount = this.getSlidesCount();

        if (!this.isSlideshow() || 1 >= slidesCount) {
            return;
        }

        var elementSettings = this.getElementSettings(),
            loop = 'yes' === elementSettings.rael_loop,
            breakpointsSettings = {},
            breakpoints = elementorFrontend.config.breakpoints,
            desktopSlidesPerView = this.getDeviceSlidesPerView('desktop');
        breakpointsSettings[breakpoints.lg - 1] = {
            slidesPerView: this.getDeviceSlidesPerView('tablet'),
            spaceBetween: this.getSpaceBetween('tablet')
        };
        breakpointsSettings[breakpoints.md - 1] = {
            slidesPerView: this.getDeviceSlidesPerView('mobile'),
            spaceBetween: this.getSpaceBetween('mobile')
        };
        var thumbsSliderOptions = {
            slidesPerView: desktopSlidesPerView,
            initialSlide: this.getInitialSlide(),
            centeredSlides: elementSettings.rael_centered_slides,
            slideToClickedSlide: true,
            spaceBetween: this.getSpaceBetween(),
            loopedSlides: slidesCount,
            loop: loop,
            breakpoints: breakpointsSettings,
            handleElementorBreakpoints: true
        };
        this.Swipers.main.controller.control = this.Swipers.thumbs = new RAELSwiper(this.elements.$thumbsSwiper, thumbsSliderOptions);

        this.elements.$thumbsSwiper.data('swiper', this.Swipers.thumbs);
        this.Swipers.thumbs.controller.control = this.Swipers.main;
    },

    onElementChange(propertyName) {
        if (1 >= this.getSlidesCount()) {
            return;
        }

        if (!this.isSlideshow()) {
            Base.prototype.onElementChange.apply(this, arguments);
            return;
        }

        if (0 === propertyName.indexOf('width')) {
            this.Swipers.main.update();
            this.Swipers.thumbs.update();
        }

        if (0 === propertyName.indexOf('rael_space_between')) {
            this.updateSpaceBetween(this.Swipers.thumbs, propertyName);
        }
    }

});

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($scope) => {
        elementorFrontend.elementsHandler.addHandler(MediaCarouselHandler, {
            $element : $scope,
        });
    };

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/rael-media-carousel.default",
        addHandler
    );
});