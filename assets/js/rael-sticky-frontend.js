/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 284);
/******/ })
/************************************************************************/
/******/ ({

/***/ 284:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


(function ($, elementor) {
  'use strict';

  var RaelSticky = {
    init: function init() {
      elementor.hooks.addAction('frontend/element_ready/column', RaelSticky.elementorColumn);
      elementor.hooks.addAction('frontend/element_ready/container', RaelSticky.elementorColumn);

      elementorFrontend.hooks.addAction('frontend/element_ready/section', RaelSticky.setStickySection);
      elementorFrontend.hooks.addAction('frontend/element_ready/container', RaelSticky.setStickySection);
      $(RaelSticky.stickySection);
    },
    elementorColumn: function( $scope ) {
			var $target  = $scope,
				$window  = $( window ),
				columnId = $target.data( 'id' ),
				editMode = Boolean( elementor.isEditMode() ),
				settings = {},
				stickyInstance = null,
				stickyInstanceOptions = {
					topSpacing: 50,
					bottomSpacing: 50,
					containerSelector: '.elementor-section',
					innerWrapperSelector: '.elementor-widget-wrap',
				};

			if ( ! editMode ) {
				settings = $target.data( 'rael-sticky-column-settings' );

				if ( $target.hasClass( 'rael-sticky-column-sticky' ) ) {
					if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
						$target.each( function() {

							var $this  = $( this ),

							elementType = $this.data( 'element_type' );

							if ( elementType !== 'container' ){
									stickyInstanceOptions.topSpacing = settings['topSpacing'];
									stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

									$target.data( 'stickyColumnInit', true );
									stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );

									$window.on( 'resize.RaelStickyColumnSticky orientationchange.RaelStickyColumnSticky', RaelStickyTools.debounce( 50, resizeDebounce ) );
							
							} else {
								$this.addClass( 'rael-sticky-container-sticky' );
								$this.css({ 
									'top': settings['topSpacing'], 
									'bottom': settings['bottomSpacing']
								});
							}
						});
					}
				}

			} else {
				return false;
			}

			function resizeDebounce() {
				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					availableDevices  = settings['stickyOn'] || [],
					isInit            = $target.data( 'stickyColumnInit' );

				if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {

					if ( ! isInit ) {
						$target.data( 'stickyColumnInit', true );
						stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
						stickyInstance.updateSticky();
					}
				} else {
					$target.data( 'stickyColumnInit', false );
					stickyInstance.destroy();
				}
			}

		},

		columnEditorSettings: function( columnId ) {
			var editorElements = null,
				columnData     = {};

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			$.each( editorElements.models, function( index, obj ) {

				$.each( obj.attributes.elements.models, function( index, obj ) {
					if ( columnId == obj.id ) {
						columnData = obj.attributes.settings.attributes;
					}
				} );

			} );
      const result = {
				'sticky': columnData['rael_sticky_column_sticky_enable'] || false,
				'topSpacing': columnData['rael_sticky_column_sticky_top_spacing'] || 50,
				'bottomSpacing': columnData['rael_sticky_column_sticky_bottom_spacing'] || 50,
				'stickyOn': columnData['rael_sticky_column_sticky_enable_on'] || [ 'desktop', 'tablet', 'mobile']
			}
			return result;
		},
    getStickySectionsDesktop: [],
    getStickySectionsTablet: [],
    getStickySectionsMobile: [],
    setStickySection: function setStickySection($scope) {
      var setStickySection = {
        target: $scope,
        isEditMode: Boolean(elementorFrontend.isEditMode()),
        scrollHandler: null,
        placeholder: null,
        isSticky: false,
        originalOffsetTop: null,
    
        init: function init() {
          var isStickyEnabled = this.getSectionSetting('rael_sticky_section_sticky') === 'yes';
    
          $(window).off('scroll.raelSticky-' + this.getUniqueID());
          $(window).off('resize.raelSticky-' + this.getUniqueID());
    
          this.removeStickyStyles();
    
          if (!isStickyEnabled) {
            return;
          }
    
          this.originalOffsetTop = this.target.offset().top;
    
          this.makeStickyOnScroll(this.target[0]);
    
          var self = this;
          $(window).on('resize.raelSticky-' + this.getUniqueID(), function resizeHandler() {
            self.originalOffsetTop = self.target.offset().top;
          });
    
          var devices = this.getSectionSetting('rael_sticky_section_sticky_visibility') || [];
          if (-1 !== devices.indexOf('desktop')) {
            RaelSticky.getStickySectionsDesktop.push($scope);
          }
          if (-1 !== devices.indexOf('tablet')) {
            RaelSticky.getStickySectionsTablet.push($scope);
          }
          if (-1 !== devices.indexOf('mobile')) {
            RaelSticky.getStickySectionsMobile.push($scope);
          }
        },
    
        makeStickyOnScroll: function makeStickyOnScroll(element) {
          var $el = $(element);
          this.placeholder = $('<div>').height($el.outerHeight()).hide();
    
          var self = this;
          this.scrollHandler = function scrollHandler() {
            var scrollTop = $(window).scrollTop();
    
            if (scrollTop >= self.originalOffsetTop && !self.isSticky) {
              $el.after(self.placeholder.show());
              $el.css({
                position: 'fixed',
                top: 0,
                width: $el.outerWidth() + 'px',
                zIndex: 1100
              });
              self.isSticky = true;
            } else if (scrollTop < self.originalOffsetTop && self.isSticky) {
              self.removeStickyStyles();
            }
          };
    
          $(window).on('scroll.raelSticky-' + this.getUniqueID(), this.scrollHandler);
        },
    
        removeStickyStyles: function removeStickyStyles() {
          var $el = $(this.target[0]);
          $el.css({
            position: '',
            top: '',
            width: '',
            zIndex: ''
          });
    
          if (this.placeholder) {
            this.placeholder.hide();
          }
          this.isSticky = false;
        },
    
        getUniqueID: function getUniqueID() {
          return this.target.data('model-cid') || this.target.index();
        },
    
        getSectionSetting: function getSectionSetting(setting) {
          var settings = {};
          var editMode = Boolean(elementorFrontend.isEditMode());
    
          if (editMode) {
            if (!elementorFrontend.config.hasOwnProperty('elements')) {
              return;
            }
            if (!elementorFrontend.config.elements.hasOwnProperty('data')) {
              return;
            }
            var modelCID = this.target.data('model-cid');
            var sectionData = elementorFrontend.config.elements.data[modelCID];
            if (!sectionData) {
              return;
            }
            if (!sectionData.hasOwnProperty('attributes')) {
              return;
            }
            settings = sectionData.attributes || {};
          } else {
            settings = this.target.data('settings') || {};
          }
    
          if (!settings[setting]) {
            return;
          }
    
          return settings[setting];
        }
      };
    
      setStickySection.init();
    },
               
    
    stickySection: function stickySection() {
      var stickySection = {
        isEditMode: Boolean(elementorFrontend.isEditMode()),
        correctionSelector: $('#wpadminbar'),
        initDesktop: false,
        initTablet: false,
        initMobile: false,
        init: function init() {
          this.run();
          $(window).on('resize.RaelStickySectionSticky orientationchange.RaelStickySectionSticky', this.run.bind(this));
        },
        getOffset: function getOffset() {
          var offset = 0;
          if (this.correctionSelector[0] && 'fixed' === this.correctionSelector.css('position')) {
            offset = this.correctionSelector.outerHeight(true);
          }
          return offset;
        },
        run: function run() {
          var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
            transitionIn = 'rael-sticky-transition-in',
            transitionOut = 'rael-sticky-transition-out',
            options = {
              stickyClass: 'rael-sticky-section-sticky--stuck',
              topSpacing: this.getOffset()
            };
          function initSticky(section, options) {
            section.jetStickySection(options).on('jetStickySection:stick', function (event) {
              $(event.target).addClass(transitionIn);
              setTimeout(function () {
                $(event.target).removeClass(transitionIn);
              }, 3000);
            }).on('jetStickySection:unstick', function (event) {
              $(event.target).addClass(transitionOut);
              setTimeout(function () {
                $(event.target).removeClass(transitionOut);
              }, 3000);
            });
            section.trigger('jetStickySection:activated');
          }
          if ('desktop' === currentDeviceMode && !this.initDesktop) {
            if (this.initTablet) {
              RaelSticky.getStickySectionsTablet.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initTablet = false;
            }
            if (this.initMobile) {
              RaelSticky.getStickySectionsMobile.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initMobile = false;
            }
            if (RaelSticky.getStickySectionsDesktop[0]) {
              RaelSticky.getStickySectionsDesktop.forEach(function (section, i) {
                if (RaelSticky.getStickySectionsDesktop[i + 1]) {
                  options.stopper = RaelSticky.getStickySectionsDesktop[i + 1];
                } else {
                  options.stopper = '';
                }
                initSticky(section, options);
              });
              this.initDesktop = true;
            }
          }
          if ('tablet' === currentDeviceMode && !this.initTablet) {
            if (this.initDesktop) {
              RaelSticky.getStickySectionsDesktop.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initDesktop = false;
            }
            if (this.initMobile) {
              RaelSticky.getStickySectionsMobile.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initMobile = false;
            }
            if (RaelSticky.getStickySectionsTablet[0]) {
              RaelSticky.getStickySectionsTablet.forEach(function (section, i) {
                if (RaelSticky.getStickySectionsTablet[i + 1]) {
                  options.stopper = RaelSticky.getStickySectionsTablet[i + 1];
                } else {
                  options.stopper = '';
                }
                initSticky(section, options);
              });
              this.initTablet = true;
            }
          }
          if ('mobile' === currentDeviceMode && !this.initMobile) {
            if (this.initDesktop) {
              RaelSticky.getStickySectionsDesktop.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initDesktop = false;
            }
            if (this.initTablet) {
              RaelSticky.getStickySectionsTablet.forEach(function (section, i) {
                section.trigger('jetStickySection:detach');
              });
              this.initTablet = false;
            }
            if (RaelSticky.getStickySectionsMobile[0]) {
              RaelSticky.getStickySectionsMobile.forEach(function (section, i) {
                if (RaelSticky.getStickySectionsMobile[i + 1]) {
                  options.stopper = RaelSticky.getStickySectionsMobile[i + 1];
                } else {
                  options.stopper = '';
                }
                initSticky(section, options);
              });
              this.initMobile = true;
            }
          }
        }
      };
      stickySection.init();
    }
  };
  $(window).on('elementor/frontend/init', RaelSticky.init);

  var RaelStickyTools = {
    debounce: function( threshold, callback ) {
      var timeout;

			return function debounced( $event ) {
				function delayed() {
					callback.call( this, $event );
					timeout = null;
				}

				if ( timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( delayed, threshold );
			};
		}
	}
})(jQuery, window.elementorFrontend);

/***/ })

/******/ });
//# sourceMappingURL=rael-sticky-frontend.js.map