(function($) {

    function RAELWidgetTwitterFeedHandler($scope) {
        $scope.find('.rael-twitter-feed--masonry').each(function() {
            var $container = $(this);

            if (!$container.length) {
                return;
            }

            var gutter = parseInt($container.data('gutter')) || 10;
            var $grid = $container.isotope({
                itemSelector: '.rael-twitter-feed__item',
                layoutMode: 'masonry',
                masonry: {
                    columnWidth: ".rael-twitter-feed__item",
                    gutter: gutter
                }
            });

            $grid.imagesLoaded().progress(function() {
                $grid.isotope('layout');
            });
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/rael-twitter-feed.default',
            function($scope) {
                RAELWidgetTwitterFeedHandler($scope);
            }
        );

        RAELWidgetTwitterFeedHandler($(document));
    });

})(jQuery);
