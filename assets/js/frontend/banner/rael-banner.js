var RAELBannerHandler = function ($scope, $) {
    var $bannerElement = $scope.find(".rael-banner"),
        $bannerImg = $bannerElement.find("img");


    if ($bannerElement.data("box-tilt")) {
        var reverse = $bannerElement.data("box-tilt-reverse");
        UniversalTilt.init({
            elements: $bannerElement,
            settings: {
                reverse: reverse
            },
            callbacks: {
                onMouseLeave: function (el) {
                    el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0)";
                },
                onDeviceMove: function (el) {
                    el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0.3)";
                }
            }
        });
    }


    $bannerElement.find(".rael-banner-image-banner").hover(function () {
        $bannerImg.addClass("active");
    }, function () {
        $bannerImg.removeClass("active");
    });
};


jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-banner.default", RAELBannerHandler);
})