(function($) {
    'use strict';

    /* ---------------------------------------------------------
     * Utilities
     * --------------------------------------------------------- */

    function getRealContainer($scope) {
        if ($scope.hasClass('e-con')) return $scope;
        if ($scope.is('section.elementor-section')) return $scope;

        const $column = $scope.closest('.elementor-column');
        if ($column.length) return $column;

        return $scope;
    }

    function getElementSettings($scope) {
        if (!elementorFrontend.isEditMode()) {
            return $scope.data('settings') || {};
        }

        if ($scope.data('model-cid')) {
            const modelCID = $scope.data('model-cid');
            const elementData = elementorFrontend.config.elements?.data?.[modelCID];
            
            if (elementData) {
                return elementData.attributes || {};
            }
        }

        // Fallback: try to get settings from data attribute
        try {
            return JSON.parse($scope.attr('data-settings') || '{}');
        } catch (e) {
            return {};
        }
    }

    function buildEffectsFromSettings(settings) {
        const effects = {};
        
        // Check if scroll effects are enabled
        if (settings.rae_animations_scrolling_enable !== 'yes') {
            return null;
        }

        /* ------------------------------
         * Horizontal Scroll (Translate X)
         * ------------------------------ */
        if (settings.rae_animations_scroll_effects_type === 'horizontal_scroll') {
            const viewport = settings.rae_animations_horizontal_viewport?.sizes || {};
            
            effects.translateX = {
                type: 'horizontal',
                direction: settings.rae_animations_horizontal_direction || 'to_left',
                speed: parseFloat(settings.rae_animations_horizontal_speed?.size) || 4,
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        /* ------------------------------
         * Vertical Scroll (Translate Y)
         * ------------------------------ */
        if (settings.rae_animations_scroll_effects_type === 'vertical_scroll') {
            const viewport = settings.rae_animations_vertical_viewport?.sizes || {};
            
            effects.translateY = {
                type: 'vertical',
                direction: settings.rae_animations_vertical_direction || 'up',
                speed: parseFloat(settings.rae_animations_vertical_speed?.size) || 4,
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        /* ------------------------------
         * Transparency (Opacity)
         * ------------------------------ */
        if (settings.rae_animations_transparency_enable === 'yes') {
            const viewport = settings.rae_animations_transparency_viewport?.sizes || {};
            
            effects.opacity = {
                direction: settings.rae_animations_transparency_direction || 'fade_in',
                level: parseFloat(settings.rae_animations_transparency_level?.size) || 4,
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        /* ------------------------------
         * Blur
         * ------------------------------ */
        if (settings.rae_animations_blur_enable === 'yes') {
            const viewport = settings.rae_animations_blur_viewport?.sizes || {};
            
            effects.blur = {
                direction: settings.rae_animations_blur_direction || 'fade_in',
                level: parseFloat(settings.rae_animations_blur_level?.size) || 4,
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        /* ------------------------------
         * Scale
         * ------------------------------ */
        if (settings.rae_animations_scale_enable === 'yes') {
            const viewport = settings.rae_animations_scale_viewport?.sizes || {};
            
            effects.scale = {
                direction: settings.rae_animations_scale_direction || 'scale_up',
                speed: parseFloat(settings.rae_animations_scale_speed?.size) || 4,
                origin_x: settings.motion_fx_transform_x_anchor_point || 'center',
                origin_y: settings.motion_fx_transform_y_anchor_point || 'center',
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        /* ------------------------------
         * Rotate
         * ------------------------------ */
        if (settings.rae_animations_rotate_enable === 'yes') {
            const viewport = settings.rae_animations_rotate_viewport?.sizes || {};
            
            effects.rotate = {
                direction: settings.rae_animations_rotate_direction || 'to_left',
                speed: parseFloat(settings.rae_animations_rotate_speed?.size) || 4,
                start: parseInt(viewport.start) || 0,
                end: parseInt(viewport.end) || 100
            };
        }

        return Object.keys(effects).length ? effects : null;
    }

    /* ---------------------------------------------------------
     * Core Animation Engine
     * --------------------------------------------------------- */

    const RaelAnimations = {
        init() {
            this.isEditor = elementorFrontend.isEditMode();
            this.windowHeight = this.getViewportHeight();
            this.scrollTop = this.getScrollTop();
            
            // Setup listeners
            this.setupListeners();
            
            // Initial scan for elements
            this.scanElements();
            
            // Initial update
            this.update();
        },

        getViewportHeight() {
            return window.innerHeight;
        },

        getScrollTop() {
            if (this.isEditor) {
                // In editor mode, we need to find the preview iframe
                const previewFrame = document.querySelector('#elementor-preview-iframe, .elementor-preview-iframe');
                if (previewFrame && previewFrame.contentWindow) {
                    return previewFrame.contentWindow.pageYOffset || 
                           previewFrame.contentDocument?.documentElement.scrollTop || 0;
                }
                return 0;
            }
            return window.pageYOffset || document.documentElement.scrollTop;
        },

        getDocumentHeight() {
            // Get total document height
            return Math.max(
                document.body.scrollHeight,
                document.documentElement.scrollHeight,
                document.body.offsetHeight,
                document.documentElement.offsetHeight,
                document.body.clientHeight,
                document.documentElement.clientHeight
            );
        },

        setupListeners() {
            // Scroll listener
            if (this.isEditor) {
                // In editor mode, listen to iframe scroll
                const previewFrame = document.querySelector('#elementor-preview-iframe, .elementor-preview-iframe');
                if (previewFrame && previewFrame.contentWindow) {
                    $(previewFrame.contentWindow).on('scroll', () => {
                        this.onScroll();
                    });
                    $(previewFrame.contentWindow).on('resize', () => {
                        this.onResize();
                    });
                }
            } else {
                $(window).on('scroll', () => {
                    this.onScroll();
                });
                $(window).on('resize', () => {
                    this.onResize();
                });
            }

            // Elementor specific events
            $(window).on('elementor/frontend/init', () => {
                // Listen for new elements
                elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {
                    this.processElement($scope);
                });
                
                // Listen for settings changes in editor
                if (this.isEditor) {
                    elementor.channels.editor.on('change', (model) => {
                        setTimeout(() => {
                            this.scanElements();
                            this.update();
                            this.updateEntranceAnimations();
                        }, 50);
                    });
                }
            });

            // Use requestAnimationFrame for smooth updates
            this.rafUpdate = () => {
                this.update();
                this.rafId = requestAnimationFrame(this.rafUpdate);
            };
            
            // Start RAF updates
            this.rafId = requestAnimationFrame(this.rafUpdate);
        },

        onScroll() {
            this.scrollTop = this.getScrollTop();
            // RAF handles the update
        },

        onResize() {
            this.windowHeight = this.getViewportHeight();
            this.scanElements();
        },

        scanElements() {
            // Find all elements with scroll effects
            this.elements = [];
            
            document.querySelectorAll('.rael-scroll-effects').forEach(element => {
                this.elements.push(element);
            });
        },

        processElement($scope) {
            const $target = getRealContainer($scope);
            const settings = getElementSettings($scope);
            const effects = buildEffectsFromSettings(settings);

            if (effects) {
                $target.addClass('rael-scroll-effects');
                
                // Store effects in data attribute
                $target.attr('data-rael-scroll-effects', JSON.stringify({
                    effects: effects,
                    relativeTo: settings.rae_animations_effects_relative_to || 'viewport'
                }));
                
                // Add to elements array
                if (!this.elements.includes($target[0])) {
                    this.elements.push($target[0]);
                }
            }

            // Handle entrance animation
            this.setupEntranceAnimation($target, settings);
        },

        setupEntranceAnimation($element, settings) {
            if (settings.rae_animations_entrance && settings.rae_animations_entrance !== 'none') {
                // Remove any existing animation classes
                $element.removeClass('animated rael-animated');
                $element.removeClass(settings.rae_animations_entrance);
                
                // Add new animation class
                $element.addClass('rael-entrance');
                $element.attr('data-rae-entrance', settings.rae_animations_entrance);
                
                // Set animation duration
                const duration = settings.rae_animations_entrance_duration || '1000';
                $element.attr('data-rae-animation-duration', duration);
                
                // Remove old duration classes
                $element.removeClass('rae-duration-slow rae-duration-normal rae-duration-fast');
                
                // Add duration class based on value
                if (duration === '2000') {
                    $element.addClass('rae-duration-slow');
                } else if (duration === '1000') {
                    $element.addClass('rae-duration-normal');
                } else if (duration === '800') {
                    $element.addClass('rae-duration-fast');
                }
                
                // Set animation delay
                const delay = settings.rae_animations_entrance_animation_delay || '0';
                $element.attr('data-rae-animation-delay', delay);
                
                // Mark as not animated yet
                $element.removeClass('animated');
                $element[0].__raeEntranceDone = false;
            } else {
                $element.removeClass('rael-entrance animated rael-animated');
                $element.removeAttr('data-rae-entrance data-rae-animation-duration data-rae-animation-delay');
            }
        },

        update() {
            if (!this.elements.length) return;

            // Process each element
            this.elements.forEach(element => {
                this.applyEffects(element);
            });

            // Handle entrance animations
            this.handleEntranceAnimations();
        },

        applyEffects(element) {
            let effectsData;
            
            // Get effects data
            if (this.isEditor) {
                // In editor mode, get from current settings
                const $scope = $(element);
                const settings = getElementSettings($scope);
                const effects = buildEffectsFromSettings(settings);
                
                if (!effects) {
                    $scope.removeClass('rael-scroll-effects');
                    return;
                }
                
                effectsData = {
                    effects: effects,
                    relativeTo: settings.rae_animations_effects_relative_to || 'viewport'
                };
            } else {
                // Frontend mode - get from data attribute
                const rawData = element.getAttribute('data-rael-scroll-effects');
                if (!rawData) return;
                
                try {
                    effectsData = JSON.parse(rawData);
                } catch (e) {
                    return;
                }
            }

            const effects = effectsData.effects;
            const relativeTo = effectsData.relativeTo || 'viewport';
            
            if (!effects) return;

            // Calculate scroll progress
            const progress = this.calculateProgress(element, relativeTo);
            
            // Apply each effect
            this.applyEffect(element, 'translateX', effects.translateX, progress);
            this.applyEffect(element, 'translateY', effects.translateY, progress);
            this.applyEffect(element, 'opacity', effects.opacity, progress);
            this.applyEffect(element, 'blur', effects.blur, progress);
            this.applyEffect(element, 'scale', effects.scale, progress);
            this.applyEffect(element, 'rotate', effects.rotate, progress);
        },

        calculateProgress(element, relativeTo) {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + this.scrollTop;
            const elementHeight = rect.height;
            const elementBottom = elementTop + elementHeight;
            
            let progress = 0;
            
            switch(relativeTo) {
                case 'page':
                    // FULL PAGE MODE - Animation stretches from TOP to BOTTOM of page
                                       const viewportTop = this.scrollTop;
                    const viewportBottom = this.scrollTop + this.windowHeight;
                    
                    // Element starts appearing when its top reaches viewport bottom
                    const viewportElementStart = elementTop - this.windowHeight;
                    // Element completely disappears when its bottom reaches viewport top
                    const viewportElementEnd = elementBottom;
                    
                    // Total scroll distance where element is visible
                    const totalVisibleDistance = viewportElementEnd - viewportElementStart;
                    
                    if (totalVisibleDistance <= 0) {
                        progress = 0;
                        break;
                    }
                    
                    if (this.scrollTop <= viewportElementStart) {
                        // Element not yet visible
                        progress = 0;
                    } else if (this.scrollTop >= viewportElementEnd) {
                        // Element completely passed
                        progress = 1;
                    } else {
                        // Element is partially visible
                        progress = (this.scrollTop - viewportElementStart) / totalVisibleDistance;
                    }

                    break;

                case 'default':
                case 'viewport':
                default:
                    // VIEWPORT MODE - Animation only when element is near viewport

                    const documentHeight = this.getDocumentHeight();
                    
                    if (documentHeight <= 0) {
                        progress = 0;
                        break;
                    }
                    
                    // Calculate element's vertical center position (0 to 1)
                    const elementCenter = (elementTop + elementHeight / 2) / documentHeight;
                    
                    // Current scroll position in page coordinates (0 to 1)
                    const currentScrollProgress = this.scrollTop / documentHeight;
                    
                    // For FULL PAGE mode:
                    // - Animation starts when scroll is at 0 (top of page) 
                    // - Animation ends when scroll is at 1 (bottom of page)
                    // - But offset by element position so animation is centered on element
                    
                    // Adjust so animation is centered around element position
                    // If element is at 30% of page, animation will complete when scroll reaches 30%
                    const adjustedProgress = Math.max(0, Math.min(1, 
                        (currentScrollProgress - (elementCenter - 0.5)) / 1
                    ));
                    
                    // Alternative: Even more stretched version
                    // Animation happens over entire page but starts earlier and ends later
                    const stretchFactor = 0.8; // How much to stretch (0.8 = 80% of page)
                    const startOffset = elementCenter - (stretchFactor / 2);
                    const endOffset = elementCenter + (stretchFactor / 2);
                    
                    if (currentScrollProgress <= startOffset) {
                        progress = 0;
                    } else if (currentScrollProgress >= endOffset) {
                        progress = 1;
                    } else {
                        progress = (currentScrollProgress - startOffset) / (endOffset - startOffset);
                    }
                    
                    break;
            }
            
            // Ensure progress is between 0 and 1
            progress = Math.min(Math.max(progress, 0), 1);
            
            return progress;
        },

        applyEffect(element, effectType, effectConfig, progress) {
            if (!effectConfig) return;
            
            // Map progress to viewport range (0-100% to 0-1)
            const mappedProgress = this.range(progress, effectConfig.start, effectConfig.end);
            
            switch(effectType) {
                case 'translateX':
                    this.applyTranslateX(element, effectConfig, mappedProgress);
                    break;
                    
                case 'translateY':
                    this.applyTranslateY(element, effectConfig, mappedProgress);
                    break;
                    
                case 'opacity':
                    this.applyOpacity(element, effectConfig, mappedProgress);
                    break;
                    
                case 'blur':
                    this.applyBlur(element, effectConfig, mappedProgress);
                    break;
                    
                case 'scale':
                    this.applyScale(element, effectConfig, mappedProgress);
                    break;
                    
                case 'rotate':
                    this.applyRotate(element, effectConfig, mappedProgress);
                    break;
            }
        },

        applyTranslateX(element, config, progress) {
            if (!config || typeof config.speed === 'undefined') return;
            
            let value = (1 - progress) * config.speed * 20;
            
            // Adjust direction
            if (config.direction === 'to_left') {
                value = -Math.abs(value);
            } else if (config.direction === 'to_right') {
                value = Math.abs(value);
            }
            
            element.style.setProperty('--translateX', `${value}px`);
        },

        applyTranslateY(element, config, progress) {
            if (!config || typeof config.speed === 'undefined') return;
            
            let value = (1 - progress) * config.speed * 20;
            
            // Adjust direction
            if (config.direction === 'up') {
                value = Math.abs(value);
            } else if (config.direction === 'down') {
                value = -Math.abs(value);
            }
            
            element.style.setProperty('--translateY', `${value}px`);
        },

        applyOpacity(element, config, progress) {
            if (!config) return;
            
            let opacity = 1;
            const level = config.level || 1;
            
            switch(config.direction) {
                case 'fade_in':
                    opacity = progress * level;
                    break;
                    
                case 'fade_out':
                    opacity = 1 - (progress * level);
                    break;
                    
                case 'fade_out_in':
                    if (progress < 0.5) {
                        opacity = 1 - (progress * 2 * level);
                    } else {
                        opacity = (progress - 0.5) * 2 * level;
                    }
                    break;
                    
                case 'fade_in_out':
                    if (progress < 0.5) {
                        opacity = progress * 2 * level;
                    } else {
                        opacity = 1 - ((progress - 0.5) * 2 * level);
                    }
                    break;
            }
            
            opacity = Math.min(Math.max(opacity, 0), 1);
            element.style.setProperty('--opacity', opacity);
        },

        applyBlur(element, config, progress) {
            if (!config) return;
            
            let blur = 0;
            const level = config.level || 1;
            
            switch(config.direction) {
                case 'fade_in':
                    blur = progress * level * 10;
                    break;
                    
                case 'fade_out':
                    blur = (1 - progress) * level * 10;
                    break;
                    
                case 'fade_out_in':
                    if (progress < 0.5) {
                        blur = progress * 2 * level * 10;
                    } else {
                        blur = (1 - progress) * 2 * level * 10;
                    }
                    break;
                    
                case 'fade_in_out':
                    if (progress < 0.5) {
                        blur = (1 - progress * 2) * level * 10;
                    } else {
                        blur = ((progress - 0.5) * 2) * level * 10;
                    }
                    break;
            }
            
            element.style.setProperty('--blur', `${blur}px`);
        },

        applyScale(element, config, progress) {
            if (!config) return;
            
            let scale = 1;
            const speed = config.speed || 1;
            
            switch(config.direction) {
                case 'scale_up':
                    scale = 1 + (progress * speed * 0.2);
                    break;
                    
                case 'scale_down':
                    scale = 1 - (progress * speed * 0.2);
                    break;
                    
                case 'scale_down_up':
                    if (progress < 0.5) {
                        scale = 1 - (progress * 2 * speed * 0.2);
                    } else {
                        scale = 1 - ((1 - progress) * 2 * speed * 0.2);
                    }
                    break;
                    
                case 'scale_up_down':
                    if (progress < 0.5) {
                        scale = 1 + (progress * 2 * speed * 0.2);
                    } else {
                        scale = 1 + ((1 - progress) * 2 * speed * 0.2);
                    }
                    break;
            }
            
            scale = Math.max(scale, 0.1);
            element.style.setProperty('--scale', scale);
            
            // Set transform origin
            if (config.origin_x && config.origin_y) {
                element.style.transformOrigin = `${config.origin_x} ${config.origin_y}`;
            }
        },

        applyRotate(element, config, progress) {
            if (!config) return;
            
            let rotate = progress * config.speed * 20;
            
            // Adjust direction
            if (config.direction === 'to_left') {
                rotate = -Math.abs(rotate);
            } else if (config.direction === 'to_right') {
                rotate = Math.abs(rotate);
            }
            
            element.style.setProperty('--rotateZ', `${rotate}deg`);
        },

        range(progress, start, end) {
            const s = start / 100;
            const e = end / 100;
            
            if (progress <= s) return 0;
            if (progress >= e) return 1;
            
            return (progress - s) / (e - s);
        },

        handleEntranceAnimations() {
            const self = this;
            
            document.querySelectorAll('.rael-entrance:not(.animated)').forEach(element => {
                const animation = element.getAttribute('data-rae-entrance');
                if (!animation || animation === 'none') return;
                
                // Check if element is in viewport
                const rect = element.getBoundingClientRect();
                const isInViewport = (
                    rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.bottom >= 0 &&
                    rect.left <= (window.innerWidth || document.documentElement.clientWidth) &&
                    rect.right >= 0
                );
                
                if (isInViewport && !element.__raeEntranceDone) {
                    this.triggerEntranceAnimation(element);
                }
            });
        },

        triggerEntranceAnimation(element) {
            const animation = element.getAttribute('data-rae-entrance');
            const delay = element.getAttribute('data-rae-animation-delay') || 0;
            const duration = element.getAttribute('data-rae-animation-duration') || '1000';
            
            // Apply animation after delay
            setTimeout(() => {
                // Add animation classes
                element.classList.add('animated', animation, 'rael-animated');
                
                // Set animation duration from data attribute
                element.style.animationDuration = duration + 'ms';
                
                // Mark as animated
                element.__raeEntranceDone = true;
                
                // Remove animation class after completion
                const animationDuration = parseInt(duration) + parseInt(delay);
                setTimeout(() => {
                    element.classList.remove(animation);
                }, animationDuration);
                
            }, parseInt(delay));
        },

        updateEntranceAnimations() {
            // Reset all entrance animations when settings change
            document.querySelectorAll('.rael-entrance').forEach(element => {
                element.classList.remove('animated', 'rael-animated');
                
                // Remove any animation class
                const currentAnimation = element.getAttribute('data-rae-entrance');
                if (currentAnimation) {
                    element.classList.remove(currentAnimation);
                }
                
                // Reset flag
                element.__raeEntranceDone = false;
            });
        },

        destroy() {
            if (this.rafId) {
                cancelAnimationFrame(this.rafId);
            }
            
            // Remove event listeners
            $(window).off('scroll resize');
        }
    };

    /* ---------------------------------------------------------
     * Initialize
     * --------------------------------------------------------- */

    $(window).on('elementor/frontend/init', function() {
        // Initialize animations
        RaelAnimations.init();
        
        // Process existing elements
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
            RaelAnimations.processElement($scope);
            RaelAnimations.scanElements();
        });
        
        // Handle sections and containers specifically
        elementorFrontend.hooks.addAction('frontend/element_ready/section', function($scope) {
            RaelAnimations.processElement($scope);
            RaelAnimations.scanElements();
        });
        
        elementorFrontend.hooks.addAction('frontend/element_ready/container', function($scope) {
            RaelAnimations.processElement($scope);
            RaelAnimations.scanElements();
        });
        
        elementorFrontend.hooks.addAction('frontend/element_ready/column', function($scope) {
            RaelAnimations.processElement($scope);
            RaelAnimations.scanElements();
        });
    });

    // Initialize on DOM ready if Elementor is already loaded
    $(document).ready(function() {
        if (typeof elementorFrontend !== 'undefined') {
            $(window).trigger('elementor/frontend/init');
        }
    });

})(jQuery);