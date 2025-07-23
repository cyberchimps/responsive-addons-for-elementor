import Base from '../media-carousel/base-slider';

class GalleryCarousel extends Base {
    getDefaultSettings() {
      return {
        selectors: {
          gallery: ".gallery-carousel",
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
        $gallery: this.$element.find(selectors.gallery),
      };
  
      elements.$raelSwiperSlides = elements.$gallery.find(selectors.slide);
  
      return elements;
    }
  
    getSlidesCount() {
      return this.elements.$raelSwiperSlides.length;
    }
  
    getInitialSlide() {
      const editSettings = this.getEditSettings();
  
      return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : 0;
    }
  
    getDeviceSlidesPerView(device) {
      var settingKey = ('desktop' === device) ? '' : '_' + device,
          settings = this.getElementSettings(),
          slides = +settings[`rael_images_to_show${settingKey}`];

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

    getDeviceSlidesToScroll(device) {
      var settingKey = ('desktop' === device) ? '' : '_' + device,
          settings = this.getElementSettings(),
          slides = +settings[`rael_images_to_scroll${settingKey}`];

      slides = slides || 1;

      return Math.min(this.getSlidesCount(), slides)
    }

    getSwiperSettings() {
      const elementSettings = this.getElementSettings();

      const swiperOptions = {
        grabCursor: true,
        initialSlide: this.getInitialSlide(),
        slidesPerView: this.getDeviceSlidesPerView('mobile'),
        slidesPerGroup: this.getDeviceSlidesToScroll('mobile'),
        loop: "yes" === elementSettings.rael_infinite_loop,
        speed: elementSettings.rael_transition_speed,
        observeParents: true,
        observer: true,
        breakpoints: {
          768: {
            slidesPerView: this.getDeviceSlidesPerView('tablet'),
            slidesPerGroup: this.getDeviceSlidesToScroll('tablet'),
          },
          1024: {
            slidesPerView: this.getDeviceSlidesPerView('desktop'),
            slidesPerGroup: this.getDeviceSlidesToScroll('desktop'),
          }
        },
        on: {
          slideChange: () => {
            this.handleKenBurns();
          },
        },
      };
  
      const showArrows =
        "arrows" === elementSettings.rael_navigation ||
        "both" === elementSettings.rael_navigation;
  
      const pagination =
        "dots" === elementSettings.rael_navigation ||
        "both" === elementSettings.rael_navigation;
  
      if (showArrows) {
        swiperOptions.navigation = {
          prevEl: ".carousel-button-prev",
          nextEl: ".carousel-button-next",
        };
      }
  
      if (pagination) {
        swiperOptions.pagination = {
          el: ".swiper-pagination",
          type: "bullets",
          clickable: true,
        };
      }

      if ("yes" === elementSettings.rael_autoplay) {
        swiperOptions.autoplay = {
          delay: elementSettings.rael_autoplay_speed,
          pauseOnMouseEnter: !!elementSettings.rael_pause_on_hover,
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
  
      this.activeItemIndex = this.swipers.gallery
        ? this.swipers.gallery.activeIndex
        : this.getInitialSlide();
  
      if (this.swipers.gallery) {
        this.$activeImageBg = jQuery(
          this.swipers.gallery.slides[this.activeItemIndex]
        ).children(settings.selectors.slideBackground);
      } else {
        this.$activeImageBg = jQuery(this.elements.$raelSwiperSlides[0]).children(
          settings.selectors.slideBackground
        );
      }
  
      this.$activeImageBg.addClass(settings.classes.kenBurnsActive);
    }
  
    initSingleSlideAnimations() {
      const settings = this.getSettings();
  
      const animation = this.elements.$gallery.data(
        settings.attributes.dataAnimation
      );
  
      this.elements.$gallery
        .find(settings.selectors.slideBackground)
        .addClass(settings.classes.kenBurnsActive); // If there is an animation, get the container of the slide's inner contents and add the animation classes to it
  
      if (animation) {
        this.elements.$gallery
          .find(settings.selectors.slideInnerContents)
          .addClass(settings.classes.animated + " " + animation);
      }
    }
  
    initSlider() {
      //need to update this line
      //super.initSlider();
  
      const $gallery = this.elements.$gallery;
      const settings = this.getSettings();
      const elementSettings = this.getElementSettings();
      const animation = $gallery.data(settings.attributes.dataAnimation);

      if (!$gallery.length) {
        return;
      }
  
      this.swipers = {};
  
      if (1 >= this.getSlidesCount()) {
        return;
      }
  
      this.swipers.gallery = new RAELSwiper($gallery, this.getSwiperSettings()); // Expose the swiper instance in the frontend
      
      $gallery.data("swiper", this.swipers.gallery); // The Ken Burns effect will only apply on the specific slides that toggled the effect ON,
      // since it depends on an additional class besides 'responsive-ken-burns--active'
  
      this.handleKenBurns();
  
      if ("yes" === elementSettings.rael_pause_on_hover) {
        this.elements.$gallery.on({
          mouseenter: () => {
            this.swipers.gallery.autoplay.stop();
          },
          mouseleave: () => {
            this.swipers.gallery.autoplay.start();
          },
        });
      }
  
      if (!animation) {
        return;
      }
  
      this.swipers.gallery.on("slideChangeTransitionStart", function () {
        const $sliderContent = $gallery.find(
          settings.selectors.slideInnerContents
        );
        $sliderContent
          .removeClass(settings.classes.animated + " " + animation)
          .hide();
      });
  
      this.swipers.gallery.on("slideChangeTransitionEnd", function () {
        const $currentSlide = $gallery.find(
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
        this.swipers.gallery.update();
      }
    }
  
    onEditSettingsChange(propertyName) {
      if (1 >= this.getSlidesCount()) {
        return;
      }
  
      if ("activeItemIndex" === propertyName) {
        this.swipers.gallery.slideToLoop(
          this.getEditSettings("activeItemIndex") - 1
        );
      }
    }
  }

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/rael-image-gallery.default', function ($scope, $) {

        var id = window.location.hash.substring( 1 );
        var pattern = new RegExp( "^[\\w\\-]+$" );
        var sanitize_input = pattern.test( id );
        var isEditMode = false;

        // Sanitize data-caption attributes to prevent XSS attacks
        document.querySelectorAll("[data-caption]").forEach((el) => {
          var val = el.getAttribute("data-caption");      
          if (val) {
              var sanitizedVal = val
                  .replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/\"/g, "&quot;")
                  .replace(/'/g, "&#039;");
      
              el.setAttribute("data-caption", sanitizedVal);
          }
      });

        if ( 'undefined' == typeof $scope ) {
            return;
        }

        if ( elementorFrontend.isEditMode() ) {
            isEditMode = true;
        }

        var $justified_selector	= $scope.find('.rael-img-justified-wrap');
        var row_height			= $justified_selector.data( 'rowheight' );
        var lastrow				= $justified_selector.data( 'lastrow' );
        var $tabs_dropdown 		= $scope.find('.rael-filters-dropdown-list');

        var img_gallery	 		= $scope.find('.rael-image-lightbox-wrap');
        var lightbox_actions 	= [];
        var fancybox_node_id 	= 'rael-fancybox-gallery-' + $scope.data( 'id' );

        if( img_gallery.length > 0 ) {
            lightbox_actions = JSON.parse( img_gallery.attr('data-lightbox_actions') );
        }

        $scope.find( '[data-fancybox="rael-gallery"]' ).fancybox({
            buttons: lightbox_actions,
            animationEffect: "fade",
            baseClass: fancybox_node_id,
        });

        if ( $justified_selector.length > 0 ) {
            $justified_selector.imagesLoaded( function() {
            })
                .done(function( instance ) {
                    $justified_selector.justifiedGallery({
                        rowHeight : row_height,
                        lastRow : lastrow,
                        selector : 'div',
                        waitThumbnailsLoad : true,
                    });
                });
        }

        $('html').click(function() {
            $tabs_dropdown.removeClass( 'show-list' );
        });

        $scope.on( 'click', '.rael-filters-dropdown-button', function(e) {
            e.stopPropagation();
            $tabs_dropdown.addClass( 'show-list' );
        });

        /* Grid */
        if ( ! isEditMode ) {

            var selector = $scope.find( '.rael-img-grid-masonry-wrap' );

            if ( selector.length < 1 ) {
                return;
            }

            if ( ! ( selector.hasClass('rael-masonry') || selector.hasClass('rael-cat-filters') ) ) {
                return;
            }

            var layoutMode = 'fitRows';
            var filter_cat;

            if ( selector.hasClass('rael-masonry') ) {
                layoutMode = 'masonry';
            }

            var filters = $scope.find('.rael-masonry-filters');
            var def_cat = '*';

            if( '' !== id && sanitize_input ) {
                var select_filter = filters.find("[data-filter='" + '.' + id.toLowerCase() + "']");

                if ( select_filter.length > 0 ) {
                    def_cat 	= '.' + id.toLowerCase();
                    select_filter.siblings().removeClass('rael-current');
                    select_filter.addClass('rael-current');
                }
            }

            if ( filters.length > 0 ) {

                var def_filter = filters.attr('data-default');
                var def_cat_sel;

                if ( '' !== def_filter ) {

                    def_cat 	= def_filter;
                    def_cat_sel = filters.find('[data-filter="'+def_filter+'"]');

                    if ( def_cat_sel.length > 0 ) {
                        def_cat_sel.siblings().removeClass('rael-current');
                        def_cat_sel.addClass('rael-current');
                    }
                }
            }

            if ( $justified_selector.length > 0 ) {
                $justified_selector.imagesLoaded( function() {
                })
                    .done(function( instance ) {
                        $justified_selector.justifiedGallery({
                            filter: def_cat,
                            rowHeight : row_height,
                            lastRow : lastrow,
                            selector : 'div',
                        });
                    });
            } else {
                var masonaryArgs = {
                    // set itemSelector so .grid-sizer is not used in layout
                    filter 			: def_cat,
                    itemSelector	: '.rael-grid-item',
                    percentPosition : true,
                    layoutMode		: layoutMode,
                    hiddenStyle 	: {
                        opacity 	: 0,
                    },
                };

                var $isotopeObj = {};

                $scope.imagesLoaded( function(e) {
                    $isotopeObj = selector.isotope( masonaryArgs );
                });
            }

            // bind filter button click
            $scope.on( 'click', '.rael-masonry-filter', function() {

                var $this 		= $(this);
                var filterValue = $this.attr('data-filter');

                $this.siblings().removeClass('rael-current');
                $this.addClass('rael-current');

                if( '*' === filterValue ) {
                    filter_cat = $scope.find('.rael-img-gallery-wrap').data('filter-default');
                } else {
                    filter_cat = filterValue.substr(1);
                }

                if( $scope.find( '.rael-masonry-filters' ).data( 'default' ) ){
                    var def_filter = $scope.find( '.rael-masonry-filters' ).data( 'default' );
                }
                else{
                    var def_filter = '.' + $scope.find('.rael-img-gallery-wrap').data( 'filter-default' );
                }
                var str_img_text = $scope.find('.rael-current').text();
                str_img_text = str_img_text.substring( def_filter.length - 1, str_img_text.length );
                $scope.find( '.rael-filters-dropdown-button' ).text( str_img_text );

                if ( $justified_selector.length > 0 ) {
                    $justified_selector.justifiedGallery({
                        filter: filterValue,
                        rowHeight : row_height,
                        lastRow : lastrow,
                        selector : 'div',
                    });
                } else {
                    $isotopeObj.isotope({ filter: filterValue });
                }
            });
            if( $scope.find( '.rael-masonry-filters' ).data( 'default' ) ){
                var def_filter = $scope.find( '.rael-masonry-filters' ).data( 'default' );
            }
            else{
                var def_filter = '.' + $scope.find('.rael-img-gallery-wrap').data( 'filter-default' );
            }

            var str_img_text = $scope.find('.rael-current').text();
            str_img_text = str_img_text.substring( def_filter.length - 1, str_img_text.length );
            $scope.find( '.rael-filters-dropdown-button' ).text( str_img_text );
        }
    });

    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(GalleryCarousel, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/rael-image-gallery.default",
      addHandler
    );
});