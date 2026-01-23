var stickyVideoPosition = '',
    isSticky = 'yes',
    isVideoActive = 'off',
    scrollHeight = 0,
    domHeight = 0,
    stickyVideoHeight = 0,
    stickyVideoWidth = 0;

jQuery(window).on('elementor/frontend/init', function() {
    if (elementorFrontend.isEditMode()) {
        elementor.hooks.addAction('panel/open_editor/widget/rael-sticky-video', function(panel, model, view) {
            var timeout;
            model.attributes.settings.on('change:rael_sv_width', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    let height = Math.ceil(model.getSetting('rael_sv_width') / 1.78);
                    model.attributes.settings.attributes.rael_sv_height = height;
                    panel.el.querySelector('[data-setting="rael_sv_height"]').value = height;
                }, 250);
            });

            model.attributes.settings.on('change:rael_sv_height', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    let width = Math.ceil(model.getSetting('rael_sv_height')) * 1.78;
                    model.attributes.settings.attributes.rael_sv_width = width;
                    panel.el.querySelector('[data-setting="rael_sv_width"]').value = width;
                }, 250);
            })
        });
    }
    
    elementorFrontend.hooks.addAction('frontend/element_ready/rael-sticky-video.default', RAELWidgetStickyVideoHandler);
});

jQuery(window).scroll(function() {
    var scrollTop = jQuery(window).scrollTop(),
        scrollBottom = jQuery(window).height() + scrollTop;

    if ('yes' === isSticky) {
        if (scrollBottom > jQuery(window).height() + 400) {
            if (scrollTop > domHeight) {
                if ('on' === isVideoActive) {
                    jQuery('#videobox').find('.rael-sticky-video__player-close').css('display', 'block');
                    jQuery('#videobox').removeClass('in').addClass('out');
                    positionStickyPlayer(stickyVideoPosition, stickyVideoWidth, stickyVideoHeight);
                }
            } else {
                jQuery('.rael-sticky-video__player-close').hide();
                jQuery('#videobox').removeClass('out').addClass('in');
                jQuery('.rael-sticky-video__player').removeAttr('style');
            }
        }
    } 
});

function getDomElementHeight(el) {
    let contentHeight = jQuery(el).parent().height(),
        requiredScrollHeight = contentHeight * (scrollHeight / 100), // Scroll Height is given in percentage w.r.t. the container.
        height = jQuery(el).parent().offset().top + requiredScrollHeight;

    return height;
}

function positionStickyPlayer(pos, width, height) {
    pos = pos.split('-'); 
    
    jQuery('.rael-sticky-video__player.out').css(pos[0], '40px');
    jQuery('.rael-sticky-video__player.out').css(pos[1], '40px');

    jQuery('.rael-sticky-video__player.out').css('width', `${width}px`);
    jQuery('.rael-sticky-video__player.out').css('height', `${height}px`);
}

function attachPlayListener(plyrInstance, el) {
    plyrInstance.on('play', function(e) {
        domHeight = getDomElementHeight(el);
        jQuery('.rael-sticky-video__player').removeAttr('id');
        el.attr('id', 'videobox');
        
        isVideoActive = 'on';
        stickyVideoPosition = el.data('position');
        stickyVideoHeight = el.data('height');
        stickyVideoWidth = el.data('width');
    });
}

var RAELWidgetStickyVideoHandler = function($scope, $) {
    $('.rael-sticky-video__player-close', $scope).hide();

    let el = $scope.find('.rael-sticky-video__player'),
        autoplay = el.data('autoplay'),
        overlay = el.data('overlay'),
        videoElem = document.getElementById('rael-sticky-video-player-' + $scope.data('id'));
        
    isSticky = el.data('sticky');
    stickyVideoWidth = el.data('width');
    stickyVideoHeight = el.data('height');
    stickyVideoPosition = el.data('position');
    scrollHeight = el.data('scroll-height');

    if ('yes' === isSticky) {
        positionStickyPlayer(stickyVideoPosition, stickyVideoWidth, stickyVideoHeight);
    }

    if ('' !== videoElem) {
        let plyrInstance = new Plyr(videoElem);
    
        if ('yes' !== overlay) {
            if ('yes' === isSticky) {
                domHeight = getDomElementHeight(el);
                el.attr('id', 'videobox');
                attachPlayListener(plyrInstance, el);
            }
        } else {
            var overlayElement = el.prev();
            isVideoActive = 'off';
    
            if ('yes' === autoplay) {
                $('.rael-sticky-video-wrapper > i').hide();
                overlayElement.css('display', 'none');
                plyrInstance.play();
        
                if ('yes' === isSticky) {
                    domHeight = getDomElementHeight(el);
                    el.attr('id', 'videobox');
                    attachPlayListener(plyrInstance, el);
                }
            } else {
                overlayElement.on('click', function() {
                    $('.rael-sticky-video-wrapper > i').hide();
                    $(this).css('display', 'none');
                    plyrInstance.play();
    
                    if ('yes' === isSticky) {
                        domHeight = getDomElementHeight(el);
                        el.attr('id', 'videobox');
                        attachPlayListener(plyrInstance, el);
                    }
                })
            }    
        }
        
        plyrInstance.on('pause', function() {
            isVideoActive = 'off';
        });

        plyrInstance.on('play', function() {
            el.closest('.rael-sticky-video__player').find('.plyr__poster').hide();
            isVideoActive = 'on';
        });

        $('.rael-sticky-video__player-close').on('click', function() {
            el.removeClass('out').addClass('in');
            $('.rael-sticky-video__player').removeAttr('style');
            isVideoActive = 'off';
        });

        el.parent().css('height', `${el.height()}px`);

        $(window).on('resize', function() {
            el.parent().css('height', `${el.height()}px`);
        });
    }
}