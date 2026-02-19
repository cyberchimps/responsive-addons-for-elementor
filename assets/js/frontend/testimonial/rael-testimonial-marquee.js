(function ($) {

    'use strict';

    function initMarquee($scope) {

        var $wrapper = $scope.find('.responsive-marquee-wrapper');
        if (!$wrapper.length) return;

        $wrapper.each(function () {

            var $this  = $(this);
            var $track = $this.find('.responsive-marquee-track');

            if (!$track.length) return;

            if ($this.hasClass('rael-marquee-initialized')) return;
            $this.addClass('rael-marquee-initialized');

            var speed     = parseFloat($this.data('marquee-speed')) || 50;
            var direction = String($this.data('marquee-direction') || 'ltr').toLowerCase();
            var gap       = parseFloat($this.data('marquee-gap')) || 0;
            var pause     = String($this.data('marquee-pause')).toLowerCase() === 'yes';

            var isVertical = (direction === 'ttb' || direction === 'btt');
            var isReverse =
                direction === 'ltr' ||
                direction === 'ttb';

            // Layout Mode

            if (isVertical) {
                $track.css({
                    display: 'flex',
                    flexDirection: 'column',
                    width: '100%',
                    height: '60vh',
                });
            } else {
                $track.css({
                    display: 'flex',
                    flexDirection: 'row',
                    width: 'max-content'
                });
            }

            if (gap > 0) {
                $track.css('gap', gap + 'px');
            }

            // Calculate Size

            var totalSize = isVertical
                ? $track[0].scrollHeight
                : $track[0].scrollWidth;

            if (!totalSize) return;

            var originalSize = totalSize / 2;
            var duration = originalSize / speed;

            // Apply Animation

            
            var animationName = isVertical
                ? 'rael-marquee-y'
                : 'rael-marquee-x';



            $track.css({
                animationName: animationName,
                animationTimingFunction: 'linear',
                animationIterationCount: 'infinite',
                animationDuration: duration + 's',
                animationDirection: isReverse ? 'reverse' : 'normal',
                animationPlayState: 'running'
            });

            // Pause on Hover

            $this.off('.raelMarqueePause');

            if (pause) {

                $this.on('mouseenter.raelMarqueePause', function () {
                    $track.css('animation-play-state', 'paused');
                });

                $this.on('mouseleave.raelMarqueePause', function () {
                    $track.css('animation-play-state', 'running');
                });

            }

            // Resize Recalculate

            $(window).off('resize.raelMarquee').on('resize.raelMarquee', function () {

                var newSize = isVertical
                    ? $track[0].scrollHeight
                    : $track[0].scrollWidth;

                var newOriginal = newSize;
                var newDuration = newOriginal / speed;

                $track.css('animation-duration', newDuration + 's');
            });

        });
    }

    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/rael-testimonial-slider.default',
            initMarquee
        );

    });

})(jQuery);