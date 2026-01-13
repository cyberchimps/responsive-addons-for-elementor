var FancyText = function FancyText($scope, $) {

    var $fancyText = $scope.find(".rael-fancy-text-container").eq(0),
        $id = $fancyText.data("fancy-text-id") !== undefined ? $fancyText.data("fancy-text-id") : "",
        $fancy_text = $fancyText.data("fancy-text") !== undefined ? $fancyText.data("fancy-text") : "",
        $transition_type = $fancyText.data("fancy-text-transition-type") !== undefined ? $fancyText.data("fancy-text-transition-type") : "",
        $fancy_text_speed = $fancyText.data("fancy-text-speed") !== undefined ? $fancyText.data("fancy-text-speed") : "",
        $fancy_text_back_speed = $fancyText.data("fancy-text-back-speed") !== undefined ? $fancyText.data("fancy-text-back-speed") : "",
        $fancy_text_delay = $fancyText.data("fancy-text-delay") !== undefined ? $fancyText.data("fancy-text-delay") : "",
        $fancy_text_cursor = $fancyText.data("fancy-text-cursor") === "yes" ? true : false,
        $fancy_text_loop = $fancyText.data("fancy-text-loop") !== undefined ? $fancyText.data("fancy-text-loop") == "yes" ? true : false : false;
        $fancy_text = $fancy_text.split("|");
    var isEditMode = elementorFrontend.isEditMode();

    if ($transition_type == "typing") {
        new Typed("#rael-fancy-text-" + $id, {
            strings: $fancy_text,
            typeSpeed: $fancy_text_speed,
            backSpeed: $fancy_text_back_speed,
            startDelay: 300,
            backDelay: $fancy_text_delay,
            showCursor: $fancy_text_cursor,
            loop: $fancy_text_loop
        });
    }

    if ($transition_type != "typing") {
        $("#rael-fancy-text-" + $id).Morphext({
            animation: $transition_type,separator: ", ",
            speed: $fancy_text_delay,
            complete: function complete() {
                // Called after the entrance animation is executed.
            }
        });
    }

    jQuery(document).ready(function () {
        setTimeout(function () {
            $(".rael-fancy-text-strings", $scope).css("display", "inline-block");
        }, 500);
    });

    if (isEditMode) {
        setTimeout(function () {
            $(".rael-fancy-text-strings", $scope).css("display", "inline-block");
        }, 800);
    }
};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-fancy-text.default", FancyText);
})