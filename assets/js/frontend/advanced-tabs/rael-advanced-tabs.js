var RAELAdvancedTabsHandler = function RAELAdvancedTabsHandler($scope, $) {
    var $currentTab = $scope.find('.rael-advanced-tabs'),
        $currentTabId = '#' + $currentTab.attr('id').toString();
    $($currentTabId + ' > .rael-tabs-nav ul li').each(function (index) {
        if ($(this).hasClass('active-default')) {
            $($currentTabId + ' .rael-tabs-nav > ul li').removeClass('active').addClass('inactive');
            $(this).removeClass('inactive');
        } else {
            if (index == 0) {
                $(this).removeClass('inactive').addClass('active');
            }
        }
    });
    $($currentTabId + ' > .rael-tabs-content > div').each(function (index) {
        if ($(this).hasClass('active-default')) {
            $($currentTabId + ' > .rael-tabs-content > div').removeClass('active');
        } else {
            if (index == 0) {
                $(this).removeClass('inactive').addClass('active');
            }
        }
    });
    $($currentTabId + ' .rael-tabs-nav ul li').click(function () {
        var currentTabIndex = $(this).index();
        var tabsContainer = $(this).closest('.rael-advanced-tabs');
        var tabsNav = $(tabsContainer).children('.rael-tabs-nav').children('ul').children('li');
        var tabsContent = $(tabsContainer).children('.rael-tabs-content').children('div');
        $(this).parent('li').addClass('active');
        $(tabsNav).removeClass('active active-default').addClass('inactive');
        $(this).addClass('active').removeClass('inactive');
        $(tabsContent).removeClass('active').addClass('inactive');
        $(tabsContent).eq(currentTabIndex).addClass('active').removeClass('inactive');

        $(tabsContent).each(function (index) {
            $(this).removeClass('active-default');
        });
    });
};

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/rael-advanced-tabs.default', RAELAdvancedTabsHandler)
})