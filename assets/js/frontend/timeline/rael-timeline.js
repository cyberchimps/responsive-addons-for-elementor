var RAELWidgetTimelineHandler = function ($scope, $) {
    if ( 'undefined' == typeof $scope ) {
        return;
    }

    var id = $scope.data('id'),
        timeline = $scope.find('.rael-timeline-wrapper'),
        dataScroll = timeline.data('scroll-tree'),
        items = timeline.find('.rael-timeline__item'),
        event = `scroll.timelineScroll${id} resize.timelineScroll${id}`;

    function scroll_tree() {
        items.each(function() {
            let item_height = $(this).height();
            let offsetTop = $(this).offset().top;
            let window_center_coords = $(window).scrollTop() + $(window).height();
            let tree_inner = $(this).find('.rael-timeline__tree-inner');
            let scroll_height = ($(window).scrollTop() - tree_inner.offset().top) + ($(window).outerHeight() / 2);

            if (offsetTop < window_center_coords) {
                $(this).addClass('rael-timeline-scroll-tree');
            } else {
                $(this).removeClass('rael-timeline-scroll-tree');
            }

            if (offsetTop < window_center_coords && items.hasClass('rael-timeline-scroll-tree')) {
                if (item_height > scroll_height) {
                    tree_inner.css({"height": scroll_height * 1.5 + "px"});
                } else {
                    tree_inner.css({"height": item_height * 1.2 + "px"});
                }
            } else {
                tree_inner.css({"height": "0"});
            }
        });
    }

    if ('yes' === dataScroll) {
        scroll_tree();
        $(window).on(event, scroll_tree);
    } else {
        $(window).off(event);
    }
};

jQuery(window).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/rael-timeline.default', RAELWidgetTimelineHandler );
});