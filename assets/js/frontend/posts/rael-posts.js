class RaelPostsHandler extends elementorModules.frontend.handlers.Base {

    getSkinPrefix() {
        return 'rael_classic_';
    }

    bindEvents() {
        var cid = this.getModelCID();
        var scope = this;
        elementorFrontend.addListenerOnce(cid, 'resize',function () {
            scope.onWindowResize();
        })
    }

    getClosureMethodsNames() {
        return elementorModules.frontend.handlers.Base.prototype.getClosureMethodsNames.apply(this, arguments).concat(['fitImages', 'onWindowResize', 'runMasonry']);
    }

    getDefaultSettings() {
        return {
            classes: {
                fitHeight: 'elementor-fit-height',
                hasItemRatio: 'elementor-has-item-ratio'
            },
            selectors: {
                postsContainer: '.responsive-posts-container',
                post: '.elementor-post',
                postThumbnail: '.elementor-post__thumbnail',
                postThumbnailImage: '.elementor-post__thumbnail img'
            }
        };
    }

    getDefaultElements() {
        var selectors = this.getSettings('selectors');
        return {
            $postsContainer: this.$element.find(selectors.postsContainer),
            $posts: this.$element.find(selectors.post)
        };
    }

    fitImage($post) {
        var settings = this.getSettings(),
            $imageParent = $post.find(settings.selectors.postThumbnail),
            $image = $imageParent.find('img'),
            image = $image[0];

        if (!image) {
            return;
        }

        var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
            imageRatio = image.naturalHeight / image.naturalWidth;
        $imageParent.toggleClass(settings.classes.fitHeight, imageRatio < imageParentRatio);
    }

    fitImages() {
        var $ = jQuery,
            self = this,
            itemRatio = getComputedStyle(this.$element[0], ':after').content,
            settings = this.getSettings();
        this.elements.$postsContainer.toggleClass(settings.classes.hasItemRatio, !!itemRatio.match(/\d/));

        if (self.isMasonryEnabled()) {
            return;
        }

        this.elements.$posts.each(function () {
            var $post = $(this),
                $image = $post.find(settings.selectors.postThumbnailImage);
            self.fitImage($post);
            $image.on('load', function () {
                self.fitImage($post);
            });
        });
    }

    setColsCountSettings() {
        var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
            settings = this.getElementSettings(),
            skinPrefix = this.getSkinPrefix(),
            colsCount;

        switch (currentDeviceMode) {
            case 'mobile':
                colsCount = settings[skinPrefix + 'columns_mobile'];
                break;

            case 'tablet':
                colsCount = settings[skinPrefix + 'columns_tablet'];
                break;

            default:
                colsCount = settings[skinPrefix + 'columns'];
        }

        this.setSettings('colsCount', colsCount);
    }

    isMasonryEnabled() {

        return !!this.getElementSettings(this.getSkinPrefix() + 'masonry');
    }

    initMasonry() {
        var $scope = this;

        imagesLoaded(this.elements.$posts, this.runMasonry($scope));
    }

    runMasonry($scope) {

        var elements = this.elements;
        elements.$posts.css({
            marginTop: '',
            transitionDuration: ''
        });
        this.setColsCountSettings();
        var colsCount = this.getSettings('colsCount'),
            hasMasonry = this.isMasonryEnabled() && colsCount >= 2;
        elements.$postsContainer.toggleClass('elementor-posts-masonry', hasMasonry);

        if (!hasMasonry) {
            elements.$postsContainer.height('');
            return;
        }
        /* The `verticalSpaceBetween` variable is setup in a way that supports older versions of the portfolio widget */


        var verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'row_gap.size');

        if ('' === this.getSkinPrefix() && '' === verticalSpaceBetween) {
            verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'item_gap.size');
        }

        var masonry = new elementorModules.utils.Masonry({
            container: elements.$postsContainer,
            items: elements.$posts.filter(':visible'),
            columnsCount: this.getSettings('colsCount'),
            verticalSpaceBetween: verticalSpaceBetween
        });
        masonry.run();
    }

    run() {
        // For slow browsers
        this.fitImages();
        this.initMasonry();
    }

    onInit(...args) {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.bindEvents();
        this.run();
    }

    onWindowResize() {
        this.fitImages();
        this.runMasonry();
    }

    onElementChange() {
        this.fitImages();
        this.runMasonry();
    }
}

var scope = ''
jQuery(window).on("elementor/frontend/init", () => {

    const addHandler = ($element) => {
        scope = $element;
        elementorFrontend.elementsHandler.addHandler(RaelPostsHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_classic", addHandler);
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_cards", addHandler);

});
var paged_no = 1;
var $ = jQuery.noConflict();
$('.rael_post_filterable_tabs li').click(function(e) {
    e.preventDefault();
    let $scope = $( this ).closest( '.elementor-widget-rael-posts' );
    var term = $(this).data('term');
    var postPerPage = $(this).parent().data('post-per-page');
    var paged = $(this).parent().data('paged');
    paged_no = paged
    var pid = $(this).parent().data('pid');
    var skin = $(this).parent().data('skin');
    var $this = $( this );
    $this.siblings().removeClass('rael_post_active_filterable_tab')
    $this.addClass('rael_post_active_filterable_tab')

    if($scope.find( '.responsive-posts-container' ).data('pagination') !== '' ) {
        if ($('.rael-post-pagination').length) {
            $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'));
        } else {
            $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'))
        }
    } else {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'))
    }

    callAjax(term,postPerPage,paged,pid,$scope,skin)

});


$( 'body' ).on( 'change', '.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown', function( e ) {
    let $scope = $( this ).closest( '.elementor-widget-rael-posts' );
    let term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term')
    var postPerPage = $(this).data('post-per-page');
    var paged = $(this).data('paged');
    paged_no = paged
    var pid = $(this).data('pid');
    var skin = $(this).data('skin');

    if( $scope.find( '.responsive-posts-container' ).data('pagination') !== '' ) {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'))
    } else {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'))
    }

    callAjax(term,postPerPage,paged,pid,$scope,skin)

})

$( 'body' ).on( 'click', '.rael-post-pagination .page-numbers', function( e ) {
    let $scope = $( this ).closest( '.elementor-widget-rael-posts' );
    if( $scope.length > 0 ) {
        e.preventDefault();
    }
    $('.rael-post-pagination span.elementor-screen-only').remove();
    var page_number = 1;
    var curr = parseInt( $scope.find( '.rael-post-pagination .page-numbers.current' ).html() );
    var $this = $( this );

    if ( $this.hasClass( 'next' ) ) {
        page_number = curr + 1;
    } else if ( $this.hasClass( 'prev' ) ) {
        page_number = curr - 1;
    } else {
        page_number = $this.html();
    }

    if( $scope.find('.responsive-posts-container').data('pagination') === 'prev_next' ) {
        page_number = $scope.find('.responsive-posts-container').data('paged')
        if ( $this.hasClass( 'next' ) ) {
            page_number += 1
        } else {
            page_number -= 1
        }
        $scope.find('.responsive-posts-container').data('paged', page_number)
    }

    var pid = $scope.find('.responsive-posts-container').data('pid')
    if ( window.innerWidth <= 767 ) {
        var term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term') === undefined ? '*all' : $('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term')
    } else {
        var term = $scope.find('.rael_post_active_filterable_tab').data('term') === undefined ? '*all' : $scope.find('.rael_post_active_filterable_tab').data('term')
    }
    var skin = $scope.find('.responsive-posts-container').data('skin')
    var postPerPage = $scope.find('.responsive-posts-container').data('post-per-page')
    var paged = page_number
    if( $scope.length > 0 ) {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'))
    }

    $("html, body").animate({
        scrollTop: $scope.find(".responsive-posts-container").offset().top - 50
    }, 1000);

    callAjax(term,postPerPage,paged,pid,$scope,skin)
});

$( 'body' ).on( 'click', '.rael-post-pagination .rael_pagination_load_more', function( e ) {
    let $scope = $( this ).closest( '.elementor-widget-rael-posts' );
    $('<div class="responsive-post-load-more-loader"> <div class="responsive-post-load-more-loader-dot"></div> <div class="responsive-post-load-more-loader-dot"></div> <div class="responsive-post-load-more-loader-dot"></div> </div>').insertAfter($scope.find('.rael-post-pagination'))
    $scope.find('.rael-post-pagination').hide()
    var pid = $scope.find('.responsive-posts-container').data('pid')
    var skin = $scope.find('.responsive-posts-container').data('skin')
    if ( window.innerWidth <= 767 ) {
        var term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term') === undefined ? '*all' : $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term')
    } else {
        var term = $scope.find('.rael_post_active_filterable_tab').data('term') === undefined ? '*all' : $scope.find('.rael_post_active_filterable_tab').data('term')
    }
    var postPerPage = $scope.find('.responsive-posts-container').data('post-per-page')
    paged_no += 1
    var paged = paged_no
    $scope.find('.responsive-posts-container').data('paged', paged)
    let widget_id = $scope.data( 'id' )
    
    $.ajax(
        {
            type: 'POST',
            url: localize.ajaxurl,
            data:
            {
                action: 'rael_get_posts_by_terms',
                data:
                {
                    term,postPerPage,paged,pid,widget_id,skin
                },
                nonce: localize.nonce
            },
            success: function success( data )
            {
                var sel = $scope.find( '.responsive-posts-container' );
                if ( sel.data('pagination') === 'infinite' ) {
                    $scope.find( '.responsive-post-load-more-loader' ).remove()
                    sel.append(data.html)
                    sel.next('.rael-post-pagination').first().remove();
                    $(data.pagination).insertAfter(sel);
                }
            }
        }
    );
    

})

function callAjax(term,postPerPage,paged,pid,$scope,skin) {
    let widget_id = $scope.data( 'id' )
    $.ajax(
        {
            type: 'POST',
            url: localize.ajaxurl,
            data:
            {
                action: 'rael_get_posts_by_terms',
                data:
                {
                    term,postPerPage,paged,pid,widget_id,skin
                }
            },
            success: function success( data )
            {
                var sel = $scope.find( '.responsive-posts-container' );
                sel.empty();
                sel.append(data.html)
                sel.next('.rael-post-pagination').first().remove();
                $(data.pagination).insertAfter(sel);
                $('div.responsive-post-loader').remove();
            }
        } 
    );
}