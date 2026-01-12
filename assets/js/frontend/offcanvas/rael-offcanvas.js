var RAELOffcanvasHandler = function RAELOffcanvasHandler($scope, $) {
        new window.RAELOffcanvasContent($scope);
};

(function ($) {
        window.RAELOffcanvasContent = function ($scope) {
                this.node = $scope;
                if ($scope.find(".rael-offcanvas-toggle").length < 1) return;

                this.wrap = $scope.find(".rael-content-wrap-offcanvas");
                this.content = $scope.find(".rael-offcanvas-content");
                this.button = $scope.find(".rael-offcanvas-toggle");
                this.settings = this.wrap.data("settings");
                this.id = this.settings.content_id;
                this.transition = this.settings.transition;
                this.esc_close = this.settings.esc_close;
                this.body_click_close = this.settings.body_click_close;
                this.open_offcanvas = this.settings.open_offcanvas;
                this.direction = this.settings.direction;
                this.duration = 500;

                this.init();
        };

        RAELOffcanvasContent.prototype = {
                id: "",
                node: "",
                wrap: "",
                content: "",
                button: "",
                settings: {},
                transition: "",
                duration: 400,
                initialized: false,
                animations: ["slide", "slide-along", "reveal", "push"],

                init: function () {
                        if (!this.wrap.length) {
                                return;
                        }

                        $("html").addClass("rael-offcanvas-content-widget");

                        if ($(".rael-offcanvas-container").length === 0) {
                                $("body").wrapInner('<div class="rael-offcanvas-container rael-offcanvas-container-' + this.id + '" />');
                                this.content.insertBefore(".rael-offcanvas-container");
                        }

                        if (this.wrap.find(".rael-offcanvas-content").length > 0) {
                                if ($(".rael-offcanvas-container > .rael-offcanvas-content-" + this.id).length > 0) {
                                        $(".rael-offcanvas-container > .rael-offcanvas-content-" + this.id).remove();
                                }
                                if ($("body > .rael-offcanvas-content-" + this.id).length > 0) {
                                        $("body > .rael-offcanvas-content-" + this.id).remove();
                                }
                                $("body").prepend(this.wrap.find(".rael-offcanvas-content"));
                        }
                        this.bindEvents();
                },

                show: function () {
                        $(".rael-offcanvas-content-" + this.id).addClass("rael-offcanvas-content-visible");

                        $("html").addClass("rael-offcanvas-content-" + this.transition);
                        $("html").addClass("rael-offcanvas-content-" + this.direction);
                        $("html").addClass("rael-offcanvas-content-open");
                        $("html").addClass("rael-offcanvas-content-" + this.id + "-open");
                        $("html").addClass("rael-offcanvas-content-reset");
                },

                destroy: function () {
                        this.close();

                        this.animations.forEach(function (animation) {
                                if ($("html").hasClass("rael-offcanvas-content-" + animation)) {
                                        $("html").removeClass("rael-offcanvas-content-" + animation);
                                }
                        });

                        if ($("body > .rael-offcanvas-content-" + this.id).length > 0) {
                                $('body > .rael-offcanvas-content-' + this.id ).remove();
                        }
                },

                bindEvents: function () {
                        if (this.open_offcanvas === "yes") {
                                this.show();
                        }
                        this.button.on("click", $.proxy(this.toggleContent, this));

                        $("body").delegate(".rael-offcanvas-content .rael-close-offcanvas", "click", $.proxy(this.close, this));

                        if (this.esc_close === "yes") {
                                this.closeESC();
                        }
                        if (this.body_click_close === "yes") {
                                this.closeClick();
                        }
                },

                toggleContent: function () {
                        if (!$("html").hasClass("rael-offcanvas-content-open")) {
                                this.show();
                        } else {
                                this.close();
                        }
                },

                closeESC: function () {
                        var self = this;

                        if ("" === self.settings.esc_close) {
                                return;
                        }
                        $(document).on("keydown", function (e) {
                                if (e.keyCode === 27) {
                                        self.close();
                                }
                        });
                },

                close: function () {
                        $("html").removeClass("rael-offcanvas-content-open");
                        $("html").removeClass("rael-offcanvas-content-" + this.id + "-open");
                        setTimeout(
                            $.proxy(function () {
                                    $("html").removeClass("rael-offcanvas-content-reset");
                                    $("html").removeClass("rael-offcanvas-content-" + this.transition);
                                    $("html").removeClass("rael-offcanvas-content-" + this.direction);
                                    $(".rael-offcanvas-content-" + this.id).removeClass("rael-offcanvas-content-visible");
                            }, this),
                            500
                        );
                },

                closeClick: function () {
                        var self = this;

                        $(document).on("click", function (e) {
                                if (
                                    $(e.target).is(".rael-offcanvas-content") ||
                                    $(e.target).parents(".rael-offcanvas-content").length > 0 ||
                                    $(e.target).is(".rael-offcanvas-toggle") ||
                                    $(e.target).parents(".rael-offcanvas-toggle").length > 0
                                ) {
                                        return;
                                } else {
                                        self.close();
                                }
                        });
                },
        };
})(jQuery);


jQuery(window).on("elementor/frontend/init", function() {
        elementorFrontend.hooks.addAction("frontend/element_ready/rael-offcanvas.default", RAELOffcanvasHandler);
});