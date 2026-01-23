var RAELWidgetLottieHandler;
RAELWidgetLottieHandler = elementorModules.frontend.handlers.Base.extend({
    getDefaultSettings() {
        return {
            selectors: {
                container: '.rael-lottie__container',
                containerLink: '.rael-lottie__container-link',
                animation: '.rael-lottie__animation',
                caption: '.rael-lottie__caption'
            },
            classes: {
                caption: 'rael-lottie__caption'
            }
        }
    },
    getDefaultElements() {
        var settings = this.getSettings(),
            selectors = settings.selectors;
  
        return {
          $widgetWrapper: this.$element,
          $container: this.$element.find(selectors.container),
          $containerLink: this.$element.find(selectors.containerLink),
          $animation: this.$element.find(selectors.animation),
          $caption: this.$element.find(selectors.caption),
          $sectionParent: this.$element.closest('.elementor-section'),
          $columnParent: this.$element.closest('.elementor-column')
        };
    },
    onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

        this.lottie = null;
        this.state = {
          isAnimationScrollUpdateNeededOnFirstLoad: true,
          isNewLoopCycle: false,
          isInViewport: false,
          loop: false,
          animationDirection: 'forward',
          currentAnimationTrigger: '',
          effectsRelativeTo: '',
          hoverOutMode: '',
          hoverArea: '',
          caption: '',
          playAnimationCount: 0,
          animationSpeed: 0,
          linkTimeout: 0,
          viewportOffset: {
            start: 0,
            end: 100
          }
        };
        this.intersectionObservers = {
          animation: {
            observer: null,
            element: null
          },
          lazyload: {
            observer: null,
            element: null
          }
        };
        this.animationFrameRequest = {
          timer: null,
          lastScrollY: 0
        };
        this.listeners = {
          collection: [],
          elements: {
            $widgetArea: {
              triggerAnimationHoverIn: null,
              triggerAnimationHoverOut: null
            },
            $container: {
              triggerAnimationClick: null
            }
          }
        };
        this.initLottie();
    },
    initLottie() {
        var lottieSettings = this.getLottieSettings();

        if (lottieSettings.rael_lottie_lazyload) {
            this.lazyloadLottie();
        } else {
            this.generateLottie();
        }     
    },
    lazyloadLottie() {
        var _this = this;

        var bufferHeightBeforeTriggerLottie = 200;
        this.intersectionObservers.lazyload.observer = elementorModules.utils.Scroll.scrollObserver({
          offset: "0px 0px ".concat(bufferHeightBeforeTriggerLottie, "px"),
          callback: function callback(event) {
            if (event.isInViewport) {
              _this.generateLottie();
  
              _this.intersectionObservers.lazyload.observer.unobserve(_this.intersectionObservers.lazyload.element);
            }
          }
        });
        this.intersectionObservers.lazyload.element = this.elements.$container[0];
        this.intersectionObservers.lazyload.observer.observe(this.intersectionObservers.lazyload.element);
    },
    generateLottie() {
        this.createLottieInstance();
        this.setLottieEvents();
    },
    createLottieInstance() {
        var settings = this.getLottieSettings();
        
        this.lottie = bodymovin.loadAnimation({
            container: this.elements.$animation[0],
            path: this.getAnimationPath(),
            renderer: settings.rael_lottie_renderer,
            autoplay: false,
            // We always want to trigger the animation manually for considering start/end frame.
            name: 'rael-lottie-widget'
        }); // Expose the lottie instance in the frontend.

        this.elements.$animation.data('lottie', this.lottie);
    },
    getAnimationPath() {
        var source1, source2;

        var lottieSettings = this.getLottieSettings();
  
        if ((( source1 = lottieSettings.rael_lottie_source_json) === null || source1 === void 0 ? void 0 : source1.url) && 'json' === lottieSettings.rael_lottie_source_json.url.toLowerCase().substr(-4)) {
          return lottieSettings.rael_lottie_source_json.url;
        } else if (( source2 = lottieSettings.rael_lottie_source_external_url) === null || source2 === void 0 ? void 0 : source2.url) {
          return lottieSettings.rael_lottie_source_external_url.url;
        } // Default animation path.
  
        return '';
    },
    setCaption() {
        var lottieSettings = this.getLottieSettings();

        if ('external_url' === lottieSettings.rael_lottie_source || 'media_file' === lottieSettings.rael_lottie_source && 'custom' === lottieSettings.rael_lottie_caption_source) {
          var $captionElement = this.getCaptionElement();
          $captionElement.text(lottieSettings.rael_lottie_caption);
        }
    },
    getCaptionElement() {
        if (!this.elements.$caption.length) {
            var _this = this.getSettings(),
                classes = _this.classes;
    
            this.elements.$caption = jQuery('<p>', {
              class: classes.caption
            });
            this.elements.$container.append(this.elements.$caption);
            return this.elements.$caption;
        }
    
          return this.elements.$caption;
    },
    setLottieEvents() {
        var _this = this;

        this.lottie.addEventListener('DOMLoaded', function () {
          return _this.onLottieDomLoaded();
        });
        this.lottie.addEventListener('complete', function () {
          return _this.onComplete();
        });
    },
    saveInitialValues() {
        var _lottieSettings$play_;

        var lottieSettings = this.getLottieSettings();
        /*
        These values of the animation are being changed during the animation runtime
        and saved in the lottie instance (and not in the state) for the instance expose in the frontend.
         */
  
        this.lottie.__initialTotalFrames = this.lottie.totalFrames;
        this.lottie.__initialFirstFrame = this.lottie.firstFrame;
        this.state.currentAnimationTrigger = lottieSettings.rael_lottie_trigger;
        this.state.effectsRelativeTo = lottieSettings.rael_lottie_effects_relative_to;
        this.state.viewportOffset.start = lottieSettings.rael_lottie_viewport ? lottieSettings.rael_lottie_viewport.sizes.start : 0;
        this.state.viewportOffset.end = lottieSettings.rael_lottie_viewport ? lottieSettings.rael_lottie_viewport.sizes.end : 100;
        this.state.animationSpeed = (_lottieSettings$play_ = lottieSettings.rael_lottie_animation_play_speed) === null || _lottieSettings$play_ === void 0 ? void 0 : _lottieSettings$play_.size;
        this.state.linkTimeout = lottieSettings.rael_lottie_link_timeout;
        this.state.caption = lottieSettings.rael_lottie_caption;
        this.state.loop = lottieSettings.rael_lottie_loop;
    },
    setAnimationFrame() {
        var frame = this.getAnimationFrames();
        /*
        We need to subtract the initial first frame from the first frame for handling scenarios
        when the animation first frame is not 0, this way we always get the relevant first frame.
        example: when start point is 70 and initial first frame is 60, the animation should start at 10.
         */
  
        frame.first = frame.first - this.lottie.__initialFirstFrame;
        this.lottie.goToAndStop(frame.first, true);
    },
    initAnimationTrigger() {
        var lottieSettings = this.getLottieSettings();

        switch (lottieSettings.rael_lottie_trigger) {
          case 'none':
            this.playLottie();
            break;
  
          case 'viewport':
            this.playAnimationWhenArrivingToViewport();
            break;
  
          case 'scroll':
            this.playAnimationWhenBindToScroll();
            break;
  
          case 'on_click':
            this.bindAnimationClickEvents();
            break;
  
          case 'on_hover':
            this.bindAnimationHoverEvents();
            break;
        }
    },
    playAnimationWhenArrivingToViewport() {
        var _this = this;
        var offset = this.getOffset();

        this.intersectionObservers.animation.observer = elementorModules.utils.Scroll.scrollObserver({
            offset: "".concat(offset.end, "% 0% ").concat(offset.start, "%"),
            callback: function callback(event) {
                if (event.isInViewport) {
                    _this.state.isInViewport = true;

                    _this.playLottie();
                } else {
                    _this.state.isInViewport = false;

                    _this.lottie.pause();
                }
            }
        });
        this.intersectionObservers.animation.element = this.elements.$widgetWrapper[0];
        this.intersectionObservers.animation.observer.observe(this.intersectionObservers.animation.element);
    },
    getOffset() {
        var lottieSettings = this.getLottieSettings(),
            start = -lottieSettings.rael_lottie_viewport.sizes.start || 0,
            end = -(100 - lottieSettings.rael_lottie_viewport.sizes.end) || 0;
    
        return {
            start: start,
            end: end
        };
    },
    playAnimationWhenBindToScroll() {
        var _this = this;

        var lottieSettings = this.getLottieSettings(),
            offset = this.getOffset(); // Generate scroll detection by Intersection Observer API
  
        this.intersectionObservers.animation.observer = elementorModules.utils.Scroll.scrollObserver({
          offset: "".concat(offset.end, "% 0% ").concat(offset.start, "%"),
          callback: function callback(event) {
            return _this.onLottieIntersection(event);
          }
        });
        this.intersectionObservers.animation.element = 'viewport' === lottieSettings.rael_lottie_effects_relative_to ? this.elements.$widgetWrapper[0] : document.documentElement;
        this.intersectionObservers.animation.observer.observe(this.intersectionObservers.animation.element);
    },
    updateAnimationByScrollPosition() {
        var lottieSettings = this.getLottieSettings();
        var percentage;
  
        if ('page' === lottieSettings.rael_lottie_effects_relative_to) {
          percentage = this.getLottiePagePercentage();
        } else if ('fixed' === this.getCurrentDeviceSetting('_position')) {
          percentage = this.getLottieViewportHeightPercentage();
        } else {
          percentage = this.getLottieViewportPercentage();
        }
  
        var nextFrameToPlay = this.getFrameNumberByPercent(percentage);
        nextFrameToPlay = nextFrameToPlay - this.lottie.__initialFirstFrame;
        this.lottie.goToAndStop(nextFrameToPlay, true);
    },
    getLottieViewportPercentage() {
        return elementorModules.utils.Scroll.getElementViewportPercentage(this.elements.$widgetWrapper, this.getOffset());
    },
    getLottiePagePercentage() {
        return elementorModules.utils.Scroll.getPageScrollPercentage(this.getOffset());
    },
    getLottieViewportHeightPercentage() {
        return elementorModules.utils.Scroll.getPageScrollPercentage(this.getOffset(), window.innerHeight);
    },
    getFrameNumberByPercent(percent) {
        var frame = this.getAnimationFrames();
        /*
        In mobile devices the document height can be 'stretched' at the top and bottom points of the document,
        this 'stretched' will make percent to be either negative or larger than 100, therefore we need to limit percent between 0-100.
        */
  
        percent = Math.min(100, Math.max(0, percent)); // Getting frame number by percent of range, considering start/end frame values if exist.
  
        return frame.first + (frame.last - frame.first) * percent / 100;
    },
    getAnimationFrames() {
        var lottieSettings = this.getLottieSettings(),
            currentFrame = this.getAnimationCurrentFrame(),
            startPoint = this.getAnimationRange().start,
            endPoint = this.getAnimationRange().end;
        var firstFrame = this.lottie.__initialFirstFrame,
            lastFrame = 0 === this.lottie.__initialFirstFrame ? this.lottie.__initialTotalFrames : this.lottie.__initialFirstFrame + this.lottie.__initialTotalFrames; // Limiting min start point to animation first frame.
  
        if (startPoint && startPoint > firstFrame) {
          firstFrame = startPoint;
        } // limiting max end point to animation last frame.
  
  
        if (endPoint && endPoint < lastFrame) {
          lastFrame = endPoint;
        }
        /*
        Getting the relevant first frame after loop complete and when not bind to scroll.
        when the animation is in progress (no when a new loop start), the first frame should be the current frame.
        when the trigger is scroll we DON'T need to get this functionality.
        */
  
  
        if (!this.state.isNewLoopCycle && 'scroll' !== lottieSettings.rael_lottie_trigger) {
          // When we have a custom start point, we need to check if the start point is larger than the last pause stop of the animation.
          firstFrame = startPoint && startPoint > currentFrame ? startPoint : currentFrame;
        } // Reverse Mode.
  
  
        if ('backward' === this.state.animationDirection && this.isReverseMode()) {
          firstFrame = currentFrame;
          lastFrame = startPoint && startPoint > this.lottie.__initialFirstFrame ? startPoint : this.lottie.__initialFirstFrame;
        }
  
        return {
          first: firstFrame,
          last: lastFrame,
          current: currentFrame,
          total: this.lottie.__initialTotalFrames
        };
    },
    getAnimationRange() {
        var lottieSettings = this.getLottieSettings();
        return {
          start: this.getInitialFrameNumberByPercent(lottieSettings.rael_lottie_start_point.size),
          end: this.getInitialFrameNumberByPercent(lottieSettings.rael_lottie_end_point.size)
        };
    },
    getInitialFrameNumberByPercent(percent) {
        percent = Math.min(100, Math.max(0, percent));
        return this.lottie.__initialFirstFrame + (this.lottie.__initialTotalFrames - this.lottie.__initialFirstFrame) * percent / 100;
    },
    getAnimationCurrentFrame() {
        // When pausing the animation (when out of viewport) the first frame of the animation changes.
        return 0 === this.lottie.firstFrame ? this.lottie.currentFrame : this.lottie.firstFrame + this.lottie.currentFrame;
    },
    setLinkTimeout() {
        var _lottieSettings$custom,
            _this = this;
  
        var lottieSettings = this.getLottieSettings();
  
        if ('on_click' === lottieSettings.rael_lottie_trigger && ((_lottieSettings$custom = lottieSettings.rael_lottie_custom_link) === null || _lottieSettings$custom === void 0 ? void 0 : _lottieSettings$custom.url) && lottieSettings.rael_lottie_link_timeout) {
          this.elements.$containerLink.on('click', function (event) {
            event.preventDefault();
  
            if (!_this.isEdit) {
              setTimeout(function () {
                var tabTarget = 'on' === lottieSettings.rael_lottie_custom_link.is_external ? '_blank' : '_self';
                window.open(lottieSettings.rael_lottie_custom_link.url, tabTarget);
              }, lottieSettings.rael_lottie_link_timeout);
            }
          });
        }
    },
    bindAnimationClickEvents() {
        var _this = this;
  
        this.listeners.elements.$container.triggerAnimationClick = function () {
          _this.playLottie();
        };
  
        this.addSessionEventListener(this.elements.$container, 'click', this.listeners.elements.$container.triggerAnimationClick);
    },
    getLottieSettings() {
        var lottieSettings = this.getElementSettings();
        return {
            ...lottieSettings,
            rael_lottie_lazyload: 'yes' === lottieSettings.rael_lottie_lazyload,
            rael_lottie_loop: 'yes' === lottieSettings.rael_lottie_loop
        };
    },
    playLottie() {
        var frame = this.getAnimationFrames();
        this.lottie.stop();
        this.lottie.playSegments([frame.first, frame.last], true); // We reset the loop cycle state after playing the animation.
  
        this.state.isNewLoopCycle = false;
    },
    bindAnimationHoverEvents() {
        this.createAnimationHoverInEvents();
        this.createAnimationHoverOutEvents();
    },
    createAnimationHoverInEvents() {
        var _this = this;
  
        var lottieSettings = this.getLottieSettings(),
            $widgetArea = this.getHoverAreaElement();
        this.state.hoverArea = lottieSettings.rael_lottie_hover_area;
  
        this.listeners.elements.$widgetArea.triggerAnimationHoverIn = function () {
          _this.state.animationDirection = 'forward';
  
          _this.playLottie();
        };
  
        this.addSessionEventListener($widgetArea, 'mouseenter', this.listeners.elements.$widgetArea.triggerAnimationHoverIn);
    },
    addSessionEventListener($el, event, callback) {
        $el.on(event, callback);
        this.listeners.collection.push({
          $el: $el,
          event: event,
          callback: callback
        });
    },
    createAnimationHoverOutEvents() {
        var _this = this;
  
        var lottieSettings = this.getLottieSettings(),
            $widgetArea = this.getHoverAreaElement();
  
        if ('pause' === lottieSettings.rael_lottie_on_hover_out || 'reverse' === lottieSettings.rael_lottie_on_hover_out) {
          this.state.hoverOutMode = lottieSettings.rael_lottie_on_hover_out;
  
          this.listeners.elements.$widgetArea.triggerAnimationHoverOut = function () {
            if ('pause' === lottieSettings.rael_lottie_on_hover_out) {
              _this.lottie.pause();
            } else {
              _this.state.animationDirection = 'backward';
  
              _this.playLottie();
            }
          };
  
          this.addSessionEventListener($widgetArea, 'mouseleave', this.listeners.elements.$widgetArea.triggerAnimationHoverOut);
        }
    },
    getHoverAreaElement() {
        var lottieSettings = this.getLottieSettings();
  
        if ('section' === lottieSettings.rael_lottie_hover_area) {
          return this.elements.$sectionParent;
        } else if ('column' === lottieSettings.rael_lottie_hover_area) {
          return this.elements.$columnParent;
        }
  
        return this.elements.$container;
    },
    setLoopOnAnimationComplete() {
        var lottieSettings = this.getLottieSettings();
        this.state.isNewLoopCycle = true;
  
        if (lottieSettings.rael_lottie_loop && !this.isReverseMode()) {
          this.setLoopWhenNotReverse();
        } else if (lottieSettings.rael_lottie_loop && this.isReverseMode()) {
          this.setReverseAnimationOnLoop();
        } else if (!lottieSettings.rael_lottie_loop && this.isReverseMode()) {
          this.setReverseAnimationOnSingleTrigger();
        }
    },
    isReverseMode() {
        var lottieSettings = this.getLottieSettings();
        return 'yes' === lottieSettings.rael_lottie_reverse_animation || 'reverse' === lottieSettings.rael_lottie_on_hover_out && 'backward' === this.state.animationDirection;
    },
    setLoopWhenNotReverse() {
        var lottieSettings = this.getLottieSettings();
  
        if (lottieSettings.rael_lottie_loop_times > 0) {
          this.state.playAnimationCount++;
  
          if (this.state.playAnimationCount < lottieSettings.rael_lottie_loop_times) {
            this.playLottie();
          } else {
            this.state.playAnimationCount = 0;
          }
        } else {
          this.playLottie();
        }
    },
    setReverseAnimationOnLoop() {
        var lottieSettings = this.getLottieSettings();
        /*
        We trigger the reverse animation:
        either when we don't have any value in the 'Number of Times" field, and then it will be an infinite forward/backward loop,
        or, when we have a value in the 'Number of Times" field and then we need to limit the number of times of the loop cycles.
         */
  
        if (!lottieSettings.rael_lottie_loop_times || this.state.playAnimationCount < lottieSettings.rael_lottie_loop_times) {
          this.state.animationDirection = 'forward' === this.state.animationDirection ? 'backward' : 'forward';
          this.playLottie();
          /*
          We need to increment the count only on the backward movements,
          because forward movement + backward movement are equal together to one full movement count.
          */
  
          if ('backward' === this.state.animationDirection) {
            this.state.playAnimationCount++;
          }
        } else {
          // Reset the values for the loop counting for the next trigger.
          this.state.playAnimationCount = 0;
          this.state.animationDirection = 'forward';
        }
    },
    setReverseAnimationOnSingleTrigger() {
        if (this.state.playAnimationCount < 1) {
          this.state.playAnimationCount++;
          this.state.animationDirection = 'backward';
          this.playLottie();
        } else if (this.state.playAnimationCount >= 1 && 'forward' === this.state.animationDirection) {
          this.state.animationDirection = 'backward';
          this.playLottie();
        } else {
          this.state.playAnimationCount = 0;
          this.state.animationDirection = 'forward';
        }
    },
    setAnimationSpeed() {
        var lottieSettings = this.getLottieSettings();
  
        if (lottieSettings.rael_lottie_animation_play_speed) {
          this.lottie.setSpeed(lottieSettings.rael_lottie_animation_play_speed.size);
        }
    },
    onElementChange() {
        this.updateLottieValues();
        this.resetAnimationTrigger();
    },
    updateLottieValues() {
        var _lottieSettings$play_2,
            _this = this;
  
        var lottieSettings = this.getLottieSettings(),
            valuesComparison = [{
          sourceVal: (_lottieSettings$play_2 = lottieSettings.rael_lottie_play_speed) === null || _lottieSettings$play_2 === void 0 ? void 0 : _lottieSettings$play_2.size,
          stateProp: 'animationSpeed',
          callback: function callback() {
            return _this.setAnimationSpeed();
          }
        }, {
          sourceVal: lottieSettings.rael_lottie_link_timeout,
          stateProp: 'linkTimeout',
          callback: function callback() {
            return _this.setLinkTimeout();
          }
        }, {
          sourceVal: lottieSettings.rael_lottie_caption,
          stateProp: 'caption',
          callback: function callback() {
            return _this.setCaption();
          }
        }, {
          sourceVal: lottieSettings.rael_lottie_effects_relative_to,
          stateProp: 'effectsRelativeTo',
          callback: function callback() {
            return _this.updateAnimationByScrollPosition();
          }
        }, {
          sourceVal: lottieSettings.rael_lottie_loop,
          stateProp: 'loop',
          callback: function callback() {
            return _this.onLoopStateChange();
          }
        }];
        valuesComparison.forEach(function (item) {
          if ('undefined' !== typeof item.sourceVal && item.sourceVal !== _this.state[item.stateProp]) {
            _this.state[item.stateProp] = item.sourceVal;
            item.callback();
          }
        });
    },
    onLoopStateChange() {
        var isInActiveViewportMode = 'viewport' === this.state.currentAnimationTrigger && this.state.isInViewport;
  
        if (this.state.loop && (isInActiveViewportMode || 'none' === this.state.currentAnimationTrigger)) {
          this.playLottie();
        }
    },
    resetAnimationTrigger() {
        var lottieSettings = this.getLottieSettings(),
            isTriggerChange = lottieSettings.rael_lottie_trigger !== this.state.currentAnimationTrigger,
            isViewportOffsetChange = lottieSettings.rael_lottie_viewport ? this.isViewportOffsetChange() : false,
            isHoverOutModeChange = lottieSettings.rael_lottie_on_hover_out ? this.isHoverOutModeChange() : false,
            isHoverAreaChange = lottieSettings.rael_lottie_hover_area ? this.isHoverAreaChange() : false;
  
        if (isTriggerChange || isViewportOffsetChange || isHoverOutModeChange || isHoverAreaChange) {
          this.removeAnimationFrameRequests();
          this.removeObservers();
          this.removeEventListeners();
          this.initAnimationTrigger();
        }
    },
    isViewportOffsetChange() {
        var lottieSettings = this.getLottieSettings(),
            isStartOffsetChange = lottieSettings.rael_lottie_viewport.sizes.start !== this.state.viewportOffset.start,
            isEndOffsetChange = lottieSettings.rael_lottie_viewport.sizes.end !== this.state.viewportOffset.end;
        return isStartOffsetChange || isEndOffsetChange;
    },
    isHoverOutModeChange() {
        var lottieSettings = this.getLottieSettings();
        return lottieSettings.rael_lottie_on_hover_out !== this.state.hoverOutMode;
    },
    isHoverAreaChange() {
        var lottieSettings = this.getLottieSettings();
        return lottieSettings.rael_lottie_hover_area !== this.state.hoverArea;
    },
    removeEventListeners() {
        this.listeners.collection.forEach(function (listener) {
          listener.$el.off(listener.event, null, listener.callback);
        });
    },
    removeObservers() {
        // Removing all observers.
        for (var type in this.intersectionObservers) {
          if (this.intersectionObservers[type].observer && this.intersectionObservers[type].element) {
            this.intersectionObservers[type].observer.unobserve(this.intersectionObservers[type].element);
          }
        }
    },
    removeAnimationFrameRequests() {
        cancelAnimationFrame(this.animationFrameRequest.timer);
    },
    onLottieDomLoaded() {
        this.saveInitialValues();
        this.setAnimationSpeed();
        this.setLinkTimeout();
        this.setCaption();
        this.setAnimationFirstFrame();
        this.initAnimationTrigger();
    },
    onComplete() {
        this.setLoopOnAnimationComplete();
    },
    onLottieIntersection(event) {
        var _this = this;
  
        if (event.isInViewport) {
          /*
          It's required to update the animation progress on first load when lottie is inside the viewport on load
          but, there is a problem when the browser is refreshed when the scroll bar is not in 0 position,
          in this scenario, after the refresh the browser will trigger 2 scroll events
          one trigger on immediate load and second after a f ew ms to move the scroll bar to previous position (before refresh)
          therefore, we use the this.state.isAnimationScrollUpdateNeededOnFirstLoad flag
          to make sure that this.updateAnimationByScrollPosition() function will be triggered only once.
           */
          if (this.state.isAnimationScrollUpdateNeededOnFirstLoad) {
            this.state.isAnimationScrollUpdateNeededOnFirstLoad = false;
            this.updateAnimationByScrollPosition();
          }
  
          this.animationFrameRequest.timer = requestAnimationFrame(function () {
            return _this.onAnimationFrameRequest();
          });
        } else {
          var frame = this.getAnimationFrames(),
              finalFrame = 'up' === event.intersectionScrollDirection ? frame.first : frame.last;
          this.state.isAnimationScrollUpdateNeededOnFirstLoad = false;
          cancelAnimationFrame(this.animationFrameRequest.timer); // Set the animation values to min/max when out of viewport.
  
          this.lottie.goToAndStop(finalFrame, true);
        }
    },
    setAnimationFirstFrame() {
        var frame = this.getAnimationFrames();
        /*
        We need to subtract the initial first frame from the first frame for handling scenarios
        when the animation first frame is not 0, this way we always get the relevant first frame.
        example: when start point is 70 and initial first frame is 60, the animation should start at 10.
         */
  
        frame.first = frame.first - this.lottie.__initialFirstFrame;
        this.lottie.goToAndStop(frame.first, true);
    },
    onAnimationFrameRequest() {
        var _this = this;
  
        // Making calculation only when there is a change with the scroll position.
        if (window.scrollY !== this.animationFrameRequest.lastScrollY) {
          this.updateAnimationByScrollPosition();
          this.animationFrameRequest.lastScrollY = window.scrollY;
        }
  
        this.animationFrameRequest.timer = requestAnimationFrame(function () {
          return _this.onAnimationFrameRequest();
        });
    }
});

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($scope) => {
        elementorFrontend.elementsHandler.addHandler(RAELWidgetLottieHandler, {
            $element : $scope,
        });
    };

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/rael-lottie.default",
        addHandler
    );
});