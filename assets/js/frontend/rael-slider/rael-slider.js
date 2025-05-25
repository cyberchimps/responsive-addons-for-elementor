class RaelSliderHandler extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
    return {
      selectors: {
        raelslider: ".responsive-slides-wrapper",
        slide: ".swiper-slide",
        slideBackground: ".swiper-slide-bg",
        slideInnerContents: ".swiper-slide-contents",
        activeSlide: ".swiper-slide-active",
        activeDuplicate: ".swiper-slide-duplicate-active",
      },
      classes: {
        animated: "animated",
        kenBurnsActive: "elementor-ken-burns--active",
      },
      attributes: {
        dataSliderOptions: "slider_options",
        dataAnimation: "animation",
      },
    };
  }

  getDefaultElements() {
    const selectors = this.getSettings("selectors");

    const elements = {
      $raelslider: this.$element.find(selectors.raelslider),
    };

    elements.$raelSwiperSlides = elements.$raelslider.find(selectors.slide);

    return elements;
  }

  getSlidesCount() {
    return this.elements.$raelSwiperSlides.length;
  }

  getInitialSlide() {
    const editSettings = this.getEditSettings();

    return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
  }

  getSwiperSettings() {
    const elementSettings = this.getElementSettings();

    const swiperOptions = {
      grabCursor: true,
      initialSlide: this.getInitialSlide(),
      slidesPerView: 1,
      slidesPerGroup: 1,
      loop: "yes" === elementSettings.infinite,
      speed: elementSettings.speed,
      handleElementorBreakpoints: true,
      speed: elementSettings.transition_speed,
      effect: elementSettings.transition,
      observeParents: true,
      observer: true,
      handleElementorBreakpoints: true,
      on: {
        slideChange: () => {
          this.handleKenBurns();
        },
      },
    };

    const showArrows =
      "arrows" === elementSettings.navigation ||
      "both" === elementSettings.navigation;

    const pagination =
      "dots" === elementSettings.navigation ||
      "both" === elementSettings.navigation;

    if (showArrows) {
      swiperOptions.navigation = {
        prevEl: ".responsive-swiper-button-prev",
        nextEl: ".responsive-swiper-button-next",
      };
    }

    if (pagination) {
      swiperOptions.pagination = {
        el: ".swiper-pagination",
        type: "bullets",
        clickable: true,
      };
    }
    if ("yes" === elementSettings.autoplay) {
      swiperOptions.autoplay = {
        delay: elementSettings.autoplay_speed,
        disableOnInteraction: !!elementSettings.pause_on_interaction,
      };
    }

    if (true === swiperOptions.loop) {
      swiperOptions.loopedSlides = this.getSlidesCount();
    }

    if ("fade" === swiperOptions.effect) {
      swiperOptions.fadeEffect = {
        crossFade: true,
      };
    }

    return swiperOptions;
  }

  handleKenBurns() {
    const settings = this.getSettings();

    if (this.$activeImageBg) {
      this.$activeImageBg.removeClass(settings.classes.kenBurnsActive);
    }

    this.activeItemIndex = this.swipers.raelslider
      ? this.swipers.raelslider.activeIndex
      : this.getInitialSlide();

    if (this.swipers.raelslider) {
      this.$activeImageBg = jQuery(
        this.swipers.raelslider.slides[this.activeItemIndex]
      ).children(settings.selectors.slideBackground);
    } else {
      this.$activeImageBg = jQuery(this.elements.$raelSwiperSlides[0]).children(
        settings.selectors.slideBackground
      );
    }

    if (this.$activeImageBg.data('ken-burns')) {
      this.$activeImageBg.addClass(settings.classes.kenBurnsActive);
    }
  }

  initSingleSlideAnimations() {
    const settings = this.getSettings();

    const animation = this.elements.$raelslider.data(
      settings.attributes.dataAnimation
    );

    this.elements.$raelslider
      .find(settings.selectors.slideBackground)
      .addClass(settings.classes.kenBurnsActive); // If there is an animation, get the container of the slide's inner contents and add the animation classes to it

    if (animation) {
      this.elements.$raelslider
        .find(settings.selectors.slideInnerContents)
        .addClass(settings.classes.animated + " " + animation);
    }
  }

  initSlider() {
    //need to update this line
    //super.initSlider();

    const $raelslider = this.elements.$raelslider;
    const settings = this.getSettings();
    const elementSettings = this.getElementSettings();
    const animation = $raelslider.data(settings.attributes.dataAnimation);

    if (!$raelslider.length) {
      return;
    }

    this.swipers = {};

    if (1 >= this.getSlidesCount()) {
      return;
    }

    this.swipers.raelslider = new RAELSwiper($raelslider, this.getSwiperSettings()); // Expose the swiper instance in the frontend

    $raelslider.data("swiper", this.swipers.raelslider); // The Ken Burns effect will only apply on the specific slides that toggled the effect ON,
    // since it depends on an additional class besides 'responsive-ken-burns--active'

    this.handleKenBurns();

    if ("yes" === elementSettings.pause_on_hover) {
      this.elements.$raelslider.on({
        mouseenter: () => {
          this.swipers.raelslider.autoplay.stop();
        },
        mouseleave: () => {
          this.swipers.raelslider.autoplay.start();
        },
      });
    }

    if (!animation) {
      return;
    }

    this.swipers.raelslider.on("slideChangeTransitionStart", function () {
      const $sliderContent = $raelslider.find(
        settings.selectors.slideInnerContents
      );
      $sliderContent
        .removeClass(settings.classes.animated + " " + animation)
        .hide();
    });

    this.swipers.raelslider.on("slideChangeTransitionEnd", function () {
      const $currentSlide = $raelslider.find(
        settings.selectors.slideInnerContents
      );
      $currentSlide
        .show()
        .addClass(settings.classes.animated + " " + animation);
    });
  }

  onInit(...args) {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(
      this,
      arguments
    );

    if (2 > this.getSlidesCount()) {
      this.initSingleSlideAnimations();
      return;
    }

    this.initSlider();
  }

  onElementChange(propertyName) {
    if (1 >= this.getSlidesCount()) {
      return;
    }

    if (0 === propertyName.indexOf("width")) {
      this.swipers.raelslider.update();
    }
  }

  onEditSettingsChange(propertyName) {
    if (1 >= this.getSlidesCount()) {
      return;
    }

    if ("activeItemIndex" === propertyName) {
      this.swipers.raelslider.slideToLoop(
        this.getEditSettings("activeItemIndex") - 1
      );
    }
  }
}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(RaelSliderHandler, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/rael-slider.default",
    addHandler
  );
});
