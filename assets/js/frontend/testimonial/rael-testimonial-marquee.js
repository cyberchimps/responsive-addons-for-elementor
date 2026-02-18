(function ($) {

    'use strict';

    function initMarquee($scope) {

        var $wrapper = $scope.find('.responsive-marquee-wrapper');

        if (!$wrapper.length) return;

        $wrapper.each(function () {

            var $this = $(this);
            var $track = $this.find('.responsive-marquee-track');

            if (!$track.length) return;

            // Prevent double initialization
            if ($this.hasClass('rael-marquee-initialized')) return;
            $this.addClass('rael-marquee-initialized');

            var speed = parseFloat($this.data('marquee-speed')) || 50;
            var direction = $this.data('marquee-direction') || 'left';

            // Total width (duplicated slides included)
            var totalWidth = $track[0].scrollWidth;

            if (!totalWidth) return;

            // Original slides width (because duplicated once)
            var originalWidth = totalWidth / 2;

            /*
             * Duration logic:
             * Higher speed value → faster movement
             * duration = width / speed
             */
            var duration = originalWidth / speed;

            // Apply animation dynamically
            $track.css({
                'animation-name': 'rael-marquee',
                'animation-timing-function': 'linear',
                'animation-iteration-count': 'infinite',
                'animation-duration': duration + 's'
            });

            // Direction control
            if (direction === 'rtl' || direction === 'right') {
                $track.css('animation-direction', 'reverse');
            } else {
                $track.css('animation-direction', 'normal');
            }

            // Recalculate on window resize (important)
            $(window).on('resize.raelMarquee', function () {

                var newWidth = $track[0].scrollWidth;
                var newOriginalWidth = newWidth / 2;
                var newDuration = newOriginalWidth / speed;

                $track.css('animation-duration', newDuration + 's');

            });

        });
    }

    /*
     * Elementor Hook (Editor + Frontend)
     */
    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/rael-testimonial-slider.default',
            initMarquee
        );

    });

    /*
     * Fallback for normal frontend load
     */
    $(window).on('load', function () {

        initMarquee($(document));

    });

})(jQuery);
