(function($){
  function RAELWidgetFacebookFeedHandler ($scope, $) {

    if ("undefined" == typeof $scope) {
        console.error("Scope is undefined, exiting Facebook Feed handler.");
        return;
    }


    $scope.find('.rael-fb-load-more-button')
      .off('click.raelFbLoadMore')
      .on('click.raelFbLoadMore', function (e) {
        e.preventDefault();

        var button    = $(this);
        var widgetId  = button.data('widget-id');
        var postId    = button.data('post-id');
        var page      = button.data('page');
        var container = $('#rael-fb-feed-posts-' + widgetId);
        var limit     = button.data('per-page') || button.data('per_page') || button.data('count') || undefined;
        var nextPage  = page + 1;
        var offset    = limit * page;


        button.find('#lds-ring').show();
        button.find('span').hide();

        $.ajax({
            url: rael_facebook_feed_vars.ajaxurl,
            method: 'POST',
            data: {
                action: 'rael_facebook_feed_load_more',
                widget_id: widgetId,
                post_id: postId,
                offset: offset,
                limit: limit,
                nonce: rael_facebook_feed_vars.nonce
            },
            success: function (response) {
                if (response.success) {
                    container.append(response.data.html);
                    button.data('page', nextPage);
                    if (!response.data.has_more) {
                        button.hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Facebook Feed Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
            },
            complete: function() {
                button.find('#lds-ring').hide();
                button.find('span').show();
            }
        });
    });
  }

  $(window).on("elementor/frontend/init", function () {
      elementorFrontend.hooks.addAction("frontend/element_ready/rael-facebook-feed.default", function($scope) {
          RAELWidgetFacebookFeedHandler($scope, $);
      });
  });

  RAELWidgetFacebookFeedHandler(jQuery(document), jQuery);
  
})(jQuery);
