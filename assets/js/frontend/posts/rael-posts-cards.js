class RaelCardsHandler extends elementorModules.frontend.handlers.Base {

    getSkinPrefix() {
        return 'rael_cards_';
    }

    bindEvents() {
        var cid = this.getModelCID();
        var self = this;
        elementorFrontend.addListenerOnce(cid, 'resize', function() {
            self.onWindowResize();
        });
    }

    getClosureMethodsNames() {
        return elementorModules.frontend.handlers.Base.prototype.getClosureMethodsNames.apply(this, arguments).concat(['fitImages', 'onWindowResize', 'runMasonry', 'applySimpleMasonry', '_actuallyApplyMasonry']);
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

        this.elements.$posts.each(function() {
            var $post = $(this),
                $image = $post.find(settings.selectors.postThumbnailImage);
            self.fitImage($post);
            $image.on('load', function() {
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
        var self = this;

        // Use imagesLoaded if available
        if (typeof imagesLoaded !== 'undefined') {
            imagesLoaded(this.elements.$posts, function() {
                self.runMasonry();
            });
        } 
    }

    runMasonry() {
    var elements = this.elements;

    if (!elements.$postsContainer.length) {
        return;
    }

    this.setColsCountSettings();

    var colsCount = parseInt(this.getSettings('colsCount'), 10);
    var masonryEnabled = this.isMasonryEnabled();

    // destroy first on editor toggle
    this.destroyMasonry();

    if (!masonryEnabled || !colsCount || colsCount < 2) {
        return;
    }

    // EDITOR width safety
    var container = elements.$postsContainer[0];
    if (container.offsetWidth === 0) {
        var self = this;
        setTimeout(function () {
            self.runMasonry();
        }, 200);
        return;
    }

    elements.$postsContainer.addClass('elementor-posts-masonry');

    var gap = parseInt(
        this.getElementSettings(this.getSkinPrefix() + 'row_gap.size') ||
        this.getElementSettings(this.getSkinPrefix() + 'item_gap.size') ||
        0,
        10
    );

    this.applySimpleMasonry(container, colsCount, gap);
}

     applySimpleMasonry(container, colsCount, gap) {
        if (!container) return;

        var items = container.querySelectorAll('.elementor-post');
        if (!items.length) return;

        var widgetContainer = this.$element.find('.elementor-widget-container')[0];
        if (!widgetContainer) return;

        var containerWidth = widgetContainer.getBoundingClientRect().width;

        if (
            window.elementorFrontend &&
            elementorFrontend.isEditMode() &&
            containerWidth < 300
        ) {
            var self = this;
            setTimeout(function () {
                self.applySimpleMasonry(container, colsCount, gap);
            }, 200);
            return;
        }

        var colWidth = (containerWidth - (gap * (colsCount - 1))) / colsCount;

        container.style.position = 'relative';
        container.style.height = 'auto';

        var colHeights = new Array(colsCount).fill(0);

        items.forEach(function(item) {
            item.style.position = 'absolute';
            item.style.width = colWidth + 'px';
            item.style.boxSizing = 'border-box';

            var shortestCol = 0;
            for (var i = 1; i < colsCount; i++) {
                if (colHeights[i] < colHeights[shortestCol]) {
                    shortestCol = i;
                }
            }

            var left = shortestCol * (colWidth + gap);
            var top  = colHeights[shortestCol];

            item.style.left = left + 'px';
            item.style.top  = top + 'px';

            colHeights[shortestCol] += item.offsetHeight + gap;
        });

        container.style.height = Math.max(...colHeights) + 'px';

    }

    run() {
        this.fitImages();
        
        var self = this;
        setTimeout(function() {
            self.initMasonry();
        }, 500);
    }

    onInit(...args) {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.bindEvents();

        this.fitImages();
        this.initMasonry();
    }

    onWindowResize() {
        var self = this;
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(function() {
            self.fitImages();
            self.runMasonry();
        }, 250);
    }

    onElementChange(propertyName) {
        if (!propertyName) {
            return;
        }
        if (
            propertyName.includes('masonry') ||
            propertyName.includes('columns') ||
            propertyName.includes('row_gap') ||
            propertyName.includes('item_gap')
        ) {
            const self = this;

            requestAnimationFrame(() => {
                self.fitImages();
                self.runMasonry();
            });
        }
    }
    destroyMasonry() {
        var container = this.elements.$postsContainer[0];
        if (!container) return;

        var items = container.querySelectorAll('.elementor-post');

        container.style.height = '';
        container.classList.remove('elementor-posts-masonry');

        items.forEach(function(item) {
            item.style.position = '';
            item.style.top = '';
            item.style.left = '';
            item.style.width = '';
            item.style.marginTop = '';
            item.style.float = '';
            item.style.transition = '';
        });
    }

}
function forceRaelMasonryReflow($scope) {
    if (!window.elementorFrontend) return;

    const handlers = elementorFrontend.elementsHandler.handlers;

    for (let key in handlers) {
        const handler = handlers[key];
        if (
            handler &&
            handler.$element &&
            handler.$element[0] === $scope[0] &&
            typeof handler.onWindowResize === 'function'
        ) {
            handler.onWindowResize(); 
            break;
        }
    }
}


// Global function to refresh masonry after AJAX
function refreshRaelMasonry($widget) {
    if (!window.elementorFrontend) return;

    // Let DOM settle
    setTimeout(function () {
        // Re-run Elementor handler lifecycle for this widget
        elementorFrontend.elementsHandler.runReadyTrigger($widget);
    }, 50);
}

// GLOBAL SIMPLE MASONRY FUNCTION
function simpleMasonryGlobal(containerSelector) {
    var container = document.querySelector(containerSelector);
    if (!container) {
        return;
    }
    
    var items = container.querySelectorAll('.elementor-post');
    if (items.length === 0) {
        return;
    }
    
    // Default settings
    var cols = 3;
      var gap = parseInt(
        this.getElementSettings(this.getSkinPrefix() + 'row_gap.size') ||
        this.getElementSettings(this.getSkinPrefix() + 'item_gap.size') ||
        0,
        10
    );
    var containerWidth = container.offsetWidth;
    
    if (containerWidth === 0) {
        return;
    }
    
    var colWidth = (containerWidth - (gap * (cols - 1))) / cols;
    
    // Reset
    container.style.position = 'relative';
    container.style.height = 'auto';
    
    var colHeights = new Array(cols).fill(0);
    
    // Position each item
    items.forEach(function(item, index) {
        item.style.position = 'absolute';
        item.style.width = colWidth + 'px';
        item.style.boxSizing = 'border-box';
        
        // Find shortest column
        var shortestCol = 0;
        for (var i = 1; i < cols; i++) {
            if (colHeights[i] < colHeights[shortestCol]) {
                shortestCol = i;
            }
        }
        
        // Set position
        var left = shortestCol * (colWidth + gap);
        var top = colHeights[shortestCol];
        
        item.style.left = left + 'px';
        item.style.top = top + 'px';
        
        // Update column height
        colHeights[shortestCol] += item.offsetHeight + gap;
    });
    
    // Set container height
    var maxHeight = Math.max(...colHeights);
    container.style.height = maxHeight + 'px';
}

// Initialize Elementor widget
jQuery(window).on("elementor/frontend/init", function() {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RaelCardsHandler, {
            $element: $element,
        });
    };
    
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_cards", addHandler);
});

var paged_no = 1;
var $ = jQuery.noConflict();

$('.rael_post_filterable_tabs li').click(function(e) {
    e.preventDefault();
    let $scope = $(this).closest('.elementor-widget-rael-posts');
    var term = $(this).data('term');
    var postPerPage = $(this).parent().data('post-per-page');
    var paged = $(this).parent().data('paged');
    paged_no = paged;
    var pid = $(this).parent().data('pid');
    var skin = $(this).parent().data('skin');
    var $this = $(this);
    $this.siblings().removeClass('rael_post_active_filterable_tab');
    $this.addClass('rael_post_active_filterable_tab');

    if ($scope.find('.responsive-posts-container').data('pagination') !== '') {
        if ($('.rael-post-pagination').length) {
            $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'));
        } else {
            $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'));
        }
    } else {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'));
    }

    callAjax(term, postPerPage, paged, pid, $scope, skin);
});

$('body').on('change', '.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown', function(e) {
    let $scope = $(this).closest('.elementor-widget-rael-posts');
    let term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term');
    var postPerPage = $(this).data('post-per-page');
    var paged = $(this).data('paged');
    paged_no = paged;
    var pid = $(this).data('pid');
    var skin = $(this).data('skin');

    if ($scope.find('.responsive-posts-container').data('pagination') !== '') {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'));
    } else {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.responsive-posts-container'));
    }

    callAjax(term, postPerPage, paged, pid, $scope, skin);
});

$('body').on('click', '.rael-post-pagination .page-numbers', function(e) {
    let $scope = $(this).closest('.elementor-widget-rael-posts');
    if ($scope.length > 0) {
        e.preventDefault();
    }
    $('.rael-post-pagination span.elementor-screen-only').remove();
    var page_number = 1;
    var curr = parseInt($scope.find('.rael-post-pagination .page-numbers.current').html());
    var $this = $(this);

    if ($this.hasClass('next')) {
        page_number = curr + 1;
    } else if ($this.hasClass('prev')) {
        page_number = curr - 1;
    } else {
        page_number = $this.html();
    }

    if ($scope.find('.responsive-posts-container').data('pagination') === 'prev_next') {
        page_number = $scope.find('.responsive-posts-container').data('paged');
        if ($this.hasClass('next')) {
            page_number += 1;
        } else {
            page_number -= 1;
        }
        $scope.find('.responsive-posts-container').data('paged', page_number);
    }

    var pid = $scope.find('.responsive-posts-container').data('pid');
    if (window.innerWidth <= 767) {
        var term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term') === undefined ? '*all' : $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term');
    } else {
        var term = $scope.find('.rael_post_active_filterable_tab').data('term') === undefined ? '*all' : $scope.find('.rael_post_active_filterable_tab').data('term');
    }
    var skin = $scope.find('.responsive-posts-container').data('skin');
    var postPerPage = $scope.find('.responsive-posts-container').data('post-per-page');
    var paged = page_number;
    if ($scope.length > 0) {
        $('<div class="responsive-post-loader"></div>').insertAfter($('.rael-post-pagination'));
    }

    $("html, body").animate({
        scrollTop: $scope.find(".responsive-posts-container").offset().top - 50
    }, 1000);

    callAjax(term, postPerPage, paged, pid, $scope, skin);
});

$('body').on('click', '.rael-post-pagination .rael_pagination_load_more', function(e) {
    let $scope = $(this).closest('.elementor-widget-rael-posts');
    $('<div class="responsive-post-load-more-loader"> <div class="responsive-post-load-more-loader-dot"></div> <div class="responsive-post-load-more-loader-dot"></div> <div class="responsive-post-load-more-loader-dot"></div> </div>').insertAfter($scope.find('.rael-post-pagination'));
    $scope.find('.rael-post-pagination').hide();
    var pid = $scope.find('.responsive-posts-container').data('pid');
    var skin = $scope.find('.responsive-posts-container').data('skin');
    if (window.innerWidth <= 767) {
        var term = $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term') === undefined ? '*all' : $scope.find('.rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown option:selected').data('term');
    } else {
        var term = $scope.find('.rael_post_active_filterable_tab').data('term') === undefined ? '*all' : $scope.find('.rael_post_active_filterable_tab').data('term');
    }
    var postPerPage = $scope.find('.responsive-posts-container').data('post-per-page');
    paged_no += 1;
    var paged = paged_no;
    $scope.find('.responsive-posts-container').data('paged', paged);
    let widget_id = $scope.data('id');

    $.ajax(
        {
            type: 'POST',
            url: raelpostsvar.ajaxurl,
            data:
            {
                action: 'rael_get_posts_by_terms',
                data:
                {
                    term,postPerPage,paged,pid,widget_id,skin
                },
                nonce: raelpostsvar.nonce
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
});

function callAjax(term,postPerPage,paged,pid,$scope,skin) {
    let widget_id = $scope.data( 'id' );
    $.ajax(
        {
            type: 'POST',
            url: raelpostsvar.ajaxurl,
            data:
            {
                action: 'rael_get_posts_by_terms',
                nonce: raelpostsvar.nonce,
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

                  if (typeof imagesLoaded !== 'undefined') {
                        imagesLoaded(sel[0], function () {
                            setTimeout(forceWindowResize, 50);
                        });
                    } else {
                        setTimeout(forceWindowResize, 150);
                    }
            }
        } 
    );
}
function forceWindowResize() {
    window.dispatchEvent(new Event('resize'));
}
// Run masonry on window load
window.addEventListener('load', function() {
    setTimeout(function() {
        jQuery('.elementor-widget-rael-posts').each(function() {
            var $widget = jQuery(this);
            setTimeout(function() {
                refreshRaelMasonry($widget);
            }, 800);
        });
    }, 1500);
});

// Run on resize
window.addEventListener('resize', function() {
    setTimeout(function() {
        jQuery('.elementor-widget-rael-posts').each(function() {
            var $widget = jQuery(this);
            setTimeout(function() {
                refreshRaelMasonry($widget);
            }, 200);
        });
    }, 200);
});
