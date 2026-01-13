var ProgressBar = function ProgressBar($scope, $) {
    var $this = $(".rael-progressbar", $scope);
    var $layout = $this.data("layout");
    var $num = $this.data("count");
    var $duration = $this.data("duration");
    if ($num > 100) {
        $num = 100;
    }
    $this.one("inview", function() {
        if ($layout == "line_rainbow") {
            $(".rael-progressbar-line-fill", $this).css({
                width: $num + "%"
            });
        }
        if ($layout == "line") {
            $(".rael-progressbar-line-fill", $this).css({
                width: $num + "%"
            });
        } else if ($layout == "half_circle" || $layout == 'half_circle_fill') {
            $(".rael-progressbar-circle-half", $this).css({
                transform: "rotate(" + $num * 1.8 + "deg)"
            });
        }
        else if ($layout == 'box') {
            $('.rael-progressbar-box-fill', $this).css({
                'height': $num + '%',
            })
        }

        $(".rael-progressbar-circle-inner-content, .rael-progressbar-count-wrap, .rael-progressbar-box-inner-content", $this).prop({
            counter: 0
        }).animate({
            counter: $num
        }, {
            duration: $duration,
            easing: "linear",
            step: function step(counter) {
                if ($layout == "circle" || $layout == "circle_fill") {
                    var rotate = counter * 3.6;
                    $(".rael-progressbar-circle-half-left", $this).css({
                        transform: "rotate(" + rotate + "deg)"
                    });
                    if (rotate > 180) {
                        $(".rael-progressbar-circle-pie", $this).css({
                            "-webkit-clip-path": "inset(0)",
                            "clip-path": "inset(0)"
                        });
                        $(".rael-progressbar-circle-half-right", $this).css({
                            visibility: "visible"
                        });
                    }
                }
                $(".rael-progressbar-count", $this).text(Math.ceil(counter));
            }
        });
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-progress-bar.default",ProgressBar);
});