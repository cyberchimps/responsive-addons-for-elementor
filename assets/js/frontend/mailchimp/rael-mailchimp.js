jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-mailchimp-styler.default", function($scope, $) {
        var $mailchimp = $(".rael-mailchimp-wrap", $scope),
            $mailchimp_id = $mailchimp.data("mailchimp-id") !== undefined ? $mailchimp.data("mailchimp-id") : "",
            $api_key = $mailchimp.data("api-key") !== undefined ? $mailchimp.data("api-key") : "",
            $list_id = $mailchimp.data("list-id") !== undefined ? $mailchimp.data("list-id") : "",
            $button_text = $mailchimp.data("button-text") !== undefined ? $mailchimp.data("button-text") : "",
            $success_text = $mailchimp.data("success-text") !== undefined ? $mailchimp.data("success-text") : "",
            $loading_text = $mailchimp.data("loading-text") !== undefined ? $mailchimp.data("loading-text") : "";
        $("#rael-mailchimp-form-" + $mailchimp_id, $scope).on("submit", function(e) {
            e.preventDefault();

            var _this = $(this);

            $(".rael-mailchimp-message", _this).css("display", "none").html("");
            $(".rael-mailchimp-subscribe", _this).addClass("button--loading");
            $(".rael-mailchimp-subscribe span", _this).html($loading_text);
            $.ajax({
                url: RAELFrontendConfig.ajaxurl,
                type: "POST",
                data: {
                    action: "rael_mailchimp_subscribe",
                    fields: _this.serialize(),
                    apiKey: $api_key,
                    listId: $list_id
                },
                success: function success(data) {
                    if (data.status == "subscribed") {
                        $("input[type=text], input[type=email], textarea", _this).val("");
                        $(".rael-mailchimp-message", _this).css("display", "block").html("<p>" + $success_text + "</p>");
                    } else {
                        $(".rael-mailchimp-message", _this).css("display", "block").html("<p>" + data.status + "</p>");
                    }

                    $(".rael-mailchimp-subscribe", _this).removeClass("button--loading");
                    $(".rael-mailchimp-subscribe span", _this).html($button_text);
                },
                fail : function failed(data) {
                    console.log('failed');
                }
            });
        });
    });
});

//# sourceURL=webpack:///./src/js/view/mailchimp.js?