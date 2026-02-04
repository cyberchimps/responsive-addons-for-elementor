(function($) {
    "use strict";

    $(document).ready((function() {
        if ($(".rael-video-popup").length > 0 )
        {
            $(".rael-video-popup").magnificPopup({
                type: "iframe",
                mainClass: "mfp-fade",
                removalDelay: 160,
                preloader: !0,
                fixedContentPos: !1});
        }
    }))

})(jQuery);