(function($) {
    /**
     * Function for Before After Slider animation.
     */
    var RAELBASlider = function($element) {
        $element.css('width', '100%');
        
        var closest_section = $element.closest('.elementor-section');
        if (0 !== closest_section.length) {
            $element.css('height', '');
        }
        var closest_container = $element.closest('.e-con');
        if (0 !== closest_container.length) {
            $element.css('height', '100%');
        }

        var max = -1;

        $element.find("img").each(function() {
            if(max < $(this).width()) {
                max = $(this).width();
            }
        });

        $element.css('width', max + 'px');
    }

    /**
     * Before After Slider handler Function.
     */
    var RAELBASliderHandler = function($scope) {
        if ('undefined' == typeof $scope) {
            return;
        }

        var selector = $scope.find('.rael-before-after-container');
        var initial_offset = selector.data('offset');
        var move_on_hover = selector.data('move-on-hover') === 'yes';
        var orientation = selector.data('orientation');
        
        $scope.css('width', '');
        $scope.css('height', '');

        if('yes' === move_on_hover) {
            move_slider_on_hover = true;
        } else {
            move_slider_on_hover = false;
        }

        $scope.imagesLoaded(function() {
            RAELBASlider($scope);

            selector.twentytwenty({
                default_offset_pct: initial_offset,
                move_slider_on_hover: move_on_hover,
                orientation: orientation,
            });

            $(window).on('resize', function() {
                RAELBASlider($scope);
            });
        });
    };

    $(window).on('elementor/frontend/init', function() {
        //remove condition
        if (typeof elementorFrontend !== 'undefined' && typeof elementorFrontend.hooks !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/rael-before-after-slider.default', RAELBASliderHandler);
        }
        if (elementorFrontend.isEditMode()) {
            $(window).on('load', function () {
                $('.elementor-widget-rael-before-after-slider').each(function () {
                    RAELBASliderHandler($(this));
                });
            });
        }
    });
})(jQuery);