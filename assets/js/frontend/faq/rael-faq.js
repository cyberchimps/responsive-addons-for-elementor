jQuery(window).on("elementor/frontend/init", function () {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/rael-faq.default",
    function ($scope, $) {
      var rael_faq_answer = $scope.find(
        ".rael-faq-accordion > .rael-accordion-content"
      );
      var layout = $scope.find(".rael-faq-container");
      var faq_layout = layout.data("layout");

      $scope.find(".rael-accordion-title").on("click keypress", function (e) {
        e.preventDefault();
        var $this = $(this);

        if ("toggle" === faq_layout) {
          // Toggle independent
          if ($this.hasClass("rael-title-active")) {
            $this
              .removeClass("rael-title-active")
              .attr("aria-expanded", "false");
            $this.next(".rael-accordion-content").slideUp("normal", "swing");
          } else {
            $this.addClass("rael-title-active").attr("aria-expanded", "true");
            $this.next(".rael-accordion-content").slideDown("normal", "swing");
          }
        } else if ("accordion" === faq_layout) {
          // Accordion -  open only one
          if (!$this.hasClass("rael-title-active")) {
            // Close all others
            $scope
              .find(".rael-accordion-title.rael-title-active")
              .removeClass("rael-title-active")
              .attr("aria-expanded", "false");
            $scope.find(".rael-accordion-content").slideUp("normal", "swing");

            // Open clicked
            $this.addClass("rael-title-active").attr("aria-expanded", "true");
            $this.next(".rael-accordion-content").slideDown("normal", "swing");
          } else {
            // Close clicked if already active
            $this
              .removeClass("rael-title-active")
              .attr("aria-expanded", "false");
            $this.next(".rael-accordion-content").slideUp("normal", "swing");
          }
        }
      });
    }
  );
});