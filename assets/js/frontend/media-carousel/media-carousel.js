(function ($) {
  "use strict";

  var MediaCarouselHandler = elementorModules.frontend.handlers.Base.extend({
    getDefaultSettings: function () {
      return {
        selectors: {
          mainSwiper: ".elementor-main-swiper, .rael-main-swiper",
          swiperSlide: ".swiper-slide",
        },
        slidesPerView: {
          desktop: 3,
          tablet: 2,
          mobile: 1,
        },
      };
    },

    getDefaultElements: function () {
      var selectors = this.getSettings("selectors");
      return {
        $mainSwiper: this.$element.find(selectors.mainSwiper),
      };
    },

    // Robust device detection supporting multiple Elementor versions
    getCurrentDevice: function () {
      var fn =
        (elementorFrontend && elementorFrontend.getCurrentDeviceMode) ||
        (elementorFrontend &&
          elementorFrontend.breakpoints &&
          elementorFrontend.breakpoints.getActive) ||
        null;
      try {
        return typeof fn === "function" ? fn() || "desktop" : "desktop";
      } catch (e) {
        return "desktop";
      }
    },

    getSpaceBetween: function (device) {
      device = device || this.getCurrentDevice();
      var key =
        "rael_space_between" + (device !== "desktop" ? "_" + device : "");
      var setting = this.getElementSettings(key);
      var val =
        typeof setting === "object" && setting !== null && "size" in setting
          ? parseInt(setting.size, 10)
          : parseInt(setting, 10);
      return isNaN(val) ? 0 : val;
    },

    getSlidesPerViewFor: function (device) {
      var key =
        "rael_slides_per_view" + (device !== "desktop" ? "_" + device : "");
      var raw = this.getElementSettings(key);
      var val;
      if (typeof raw === "object" && raw !== null && "size" in raw)
        val = raw.size;
      else val = parseInt(raw, 10);
      if (!isNaN(val) && val > 0) return val;
      return this.getSettings("slidesPerView")[device] || 1;
    },

    getSlidesPerView: function () {
      return this.getSlidesPerViewFor(this.getCurrentDevice());
    },

    getSwiperOptions: function () {
      var settings = this.getElementSettings();
      var bp = (elementorFrontend &&
        elementorFrontend.config &&
        elementorFrontend.config.breakpoints) || { md: 768, lg: 1025 };

      var desktop = this.getSlidesPerViewFor("desktop");
      var tablet = this.getSlidesPerViewFor("tablet");
      var mobile = this.getSlidesPerViewFor("mobile");

      var loopedSlides = Math.max(desktop, tablet, mobile);

      var navNext =
        this.$element.find(".elementor-swiper-button-next")[0] ||
        this.$element.find(".swiper-button-next")[0] ||
        null;
      var navPrev =
        this.$element.find(".elementor-swiper-button-prev")[0] ||
        this.$element.find(".swiper-button-prev")[0] ||
        null;
      var paginationEl =
        this.$element.find(".swiper-pagination")[0] ||
        this.$element.find(".elementor-swiper-pagination")[0] ||
        null;

      var options = {
        slidesPerView: this.getSlidesPerView(),
        spaceBetween: this.getSpaceBetween(),
        centeredSlides: false,
        loop: settings.rael_loop === "yes",
        loopedSlides: settings.rael_loop === "yes" ? loopedSlides : 0,
        speed: parseInt(settings.rael_speed || 500, 10),
        cssMode: false,
        resistanceRatio: 0.85,
        grabCursor: true,
        watchOverflow: true,
        observer: true,
        observeParents: true,
        roundLengths: true,
        useTransform: true,
        preventInteractionOnTransition: true,
        autoplay:
          settings.rael_autoplay === "yes"
            ? {
                delay: parseInt(settings.rael_autoplay_speed || 3000, 10),
                disableOnInteraction: false,
              }
            : false,
        navigation:
          settings.rael_show_arrows === "yes" && navNext && navPrev
            ? {
                nextEl: navNext,
                prevEl: navPrev,
              }
            : false,
        pagination:
          settings.rael_pagination === "yes" && paginationEl
            ? {
                el: paginationEl,
                clickable: true,
              }
            : false,
        breakpoints: {
          [bp.md]: {
            slidesPerView: tablet,
            spaceBetween: this.getSpaceBetween("tablet"),
          },
          [bp.lg]: {
            slidesPerView: desktop,
            spaceBetween: this.getSpaceBetween("desktop"),
          },
        },
        // helpful in editor iframe environment
        resizeObserver: true,
      };

      return options;
    },

    _destroySwiperSafe: function () {
      if (this.swiper && this.swiper.destroy) {
        try {
          this.swiper.destroy(true, true);
        } catch (e) {
          // ignore
        }
      }
      this.swiper = null;

      if (
        this.elements &&
        this.elements.$mainSwiper &&
        this.elements.$mainSwiper.length
      ) {
        this.elements.$mainSwiper.find(".swiper-slide").each(function () {
          this.removeAttribute("style");
        });
        this.elements.$mainSwiper.find(".swiper-wrapper").removeAttr("style");
      }
    },

    buildSwiper: function () {
      if (typeof Swiper === "undefined") {
        console.error("Swiper is not loaded.");
        return;
      }

      var $main = this.elements.$mainSwiper;
      if (!$main.length) return;

      // destroy if exists
      this._destroySwiperSafe();

      var options = this.getSwiperOptions();

      try {
        this.swiper = new Swiper($main[0], options);
        $main.data("swiper", this.swiper);

        var self = this;
        this.swiper.on("transitionStart", function () {
          self._isTransitioning = true;
        });
        this.swiper.on("transitionEnd", function () {
          self._isTransitioning = false;
          if (self.swiper && !self.swiper.destroyed) self.swiper.update();
        });

        // force update in editor after short delay
        if (elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
          setTimeout(function () {
            if (self.swiper && !self.swiper.destroyed) self.swiper.update();
          }, 150);
        }
      } catch (err) {
        console.error("Swiper init failed:", err);
      }
    },

    _observeSlidesForEditor: function () {
      // prevent multiple observers
      if (this._slidesObserver) return;

      var $main = this.elements.$mainSwiper;
      if (!$main.length) return;

      var wrapper = $main.find(".swiper-wrapper")[0];
      if (!wrapper) {
        var selfFallback = this;
        setTimeout(function () {
          selfFallback.buildSwiper();
        }, 160);
        return;
      }

      var self = this;

      // If slides already present -> build shortly
      if (wrapper.children && wrapper.children.length > 0) {
        setTimeout(function () {
          self.buildSwiper();
        }, 60);
        return;
      }

      // Observe for children addition and init once present
      this._slidesObserver = new MutationObserver(function () {
        if (wrapper.children && wrapper.children.length > 0) {
          try {
            if (self._slidesObserver) {
              self._slidesObserver.disconnect();
              self._slidesObserver = null;
            }
          } catch (e) {}
          setTimeout(function () {
            self.buildSwiper();
          }, 40);
        }
      });

      try {
        this._slidesObserver.observe(wrapper, { childList: true });
      } catch (e) {
        setTimeout(function () {
          self.buildSwiper();
        }, 160);
      }
    },

    onInit: function () {
      // parent onInit
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(
        this,
        arguments
      );

      // add resize handler
      var that = this;
      this._resizeHandler = function () {
        if (that.swiper && !that.swiper.destroyed) that.swiper.update();
      };
      window.addEventListener("resize", this._resizeHandler, { passive: true });

      // In editor use MutationObserver to wait for slides; on frontend init immediately
      try {
        if (
          elementorFrontend &&
          elementorFrontend.isEditMode &&
          elementorFrontend.isEditMode()
        ) {
          this._observeSlidesForEditor();
        } else {
          this.buildSwiper();
        }
      } catch (e) {
        // fallback
        this.buildSwiper();
      }
    },

    onElementChange: function (propertyName) {
      // Only reinit for relevant props
      if (
        propertyName &&
        (propertyName.indexOf("rael_slides_per_view") === 0 ||
          propertyName.indexOf("rael_space_between") === 0 ||
          propertyName === "rael_loop" ||
          propertyName === "rael_autoplay" ||
          propertyName === "rael_show_arrows" ||
          propertyName === "rael_pagination" ||
          propertyName === "rael_speed")
      ) {
        var self = this;
        clearTimeout(this._reinitTimer);
        this._reinitTimer = setTimeout(function () {
          if (
            elementorFrontend &&
            elementorFrontend.isEditMode &&
            elementorFrontend.isEditMode()
          ) {
            self._observeSlidesForEditor();
          } else {
            self.buildSwiper();
          }
        }, 120);
      }

      elementorModules.frontend.handlers.Base.prototype.onElementChange.apply(
        this,
        arguments
      );
    },

    onDestroy: function () {
      if (this._resizeHandler)
        window.removeEventListener("resize", this._resizeHandler);
      if (this._slidesObserver) {
        try {
          this._slidesObserver.disconnect();
        } catch (e) {}
        this._slidesObserver = null;
      }
      this._destroySwiperSafe();
      elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(
        this,
        arguments
      );
    },
  });

  // Register handler for frontend — must exactly match widget get_name()
  $(window).on("elementor/frontend/init", function () {
    if (!elementorFrontend || !elementorModules) {
      // If Elementor not ready yet, wait a tick to avoid ReferenceErrors
      setTimeout(function () {
        if (elementorFrontend && elementorModules) {
          elementorFrontend.elementsHandler.attachHandler(
            "rael-media-carousel",
            MediaCarouselHandler
          );
        }
      }, 50);
    } else {
      elementorFrontend.elementsHandler.attachHandler(
        "rael-media-carousel",
        MediaCarouselHandler
      );
    }
  });
})(jQuery);
