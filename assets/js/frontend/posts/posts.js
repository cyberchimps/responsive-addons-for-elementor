class RaelPostsHandler extends elementorModules.frontend.handlers.Base {

    getSkinPrefix() {
        return 'rael_classic_';
    }

    bindEvents() {
        var cid = this.getModelCID();
        var self = this;
        elementorFrontend.addListenerOnce(cid, 'resize', function() {
            self.onWindowResize();
        });
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
        } else {
            // Fallback: run after delay
            setTimeout(function() {
                self.runMasonry();
            }, 300);
        }
    }

    runMasonry() {
        var elements = this.elements;
        this.setColsCountSettings();
        var colsCount = this.getSettings('colsCount'),
            hasMasonry = this.isMasonryEnabled() && colsCount >= 2;
        
        elements.$postsContainer.toggleClass('elementor-posts-masonry', hasMasonry);

        if (!hasMasonry) {
            elements.$postsContainer.height('');
            // Remove all masonry styles
            elements.$posts.css({
                position: '',
                width: '',
                left: '',
                top: '',
                marginTop: ''
            });
            return;
        }

        // Get gap setting
        var verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'row_gap.size');
        if ('' === this.getSkinPrefix() && '' === verticalSpaceBetween) {
            verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'item_gap.size');
        }
        var gap = verticalSpaceBetween || 20;

        // Use simple masonry function
        this.applySimpleMasonry(elements.$postsContainer[0], colsCount, gap);
    }

    // THIS IS THE WORKING SIMPLE MASONRY FUNCTION
    applySimpleMasonry(container, colsCount, gap) {
        if (!container) return;
        
        var items = container.querySelectorAll('.elementor-post');
        if (items.length === 0) return;
        
        var containerWidth = container.offsetWidth;
        if (containerWidth === 0) {
            return;
        }
        
        // Calculate column width
        var colWidth = (containerWidth - (gap * (colsCount - 1))) / colsCount;
    
        
        // Reset container
        container.style.position = 'relative';
        container.style.height = 'auto';
        
        var colHeights = new Array(colsCount).fill(0);
        
        // Position each item
        items.forEach(function(item) {
            // Reset item styles
            item.style.position = 'absolute';
            item.style.width = colWidth + 'px';
            item.style.boxSizing = 'border-box';
            item.style.transition = 'transform 0.3s ease';
            
            // Find shortest column
            var shortestCol = 0;
            for (var i = 1; i < colsCount; i++) {
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

    run() {
        this.fitImages();
        
        // Run masonry after a short delay
        var self = this;
        setTimeout(function() {
            self.initMasonry();
        }, 100);
    }

    onInit(...args) {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.bindEvents();
        
        var self = this;
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    self.run();
                }, 100);
            });
        } else {
            setTimeout(function() {
                self.run();
            }, 300);
        }
    }

    onWindowResize() {
        var self = this;
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(function() {
            self.fitImages();
            self.runMasonry();
        }, 250);
    }

    onElementChange() {
        var self = this;
        setTimeout(function() {
            self.fitImages();
            self.runMasonry();
        }, 100);
    }
}

// Global function to refresh masonry after AJAX
function refreshRaelMasonry($widget) {
    var widgetId = $widget.data('id');
    var handlers = elementorFrontend.elementsHandler.handlers;
        
    for (var key in handlers) {
        if (handlers[key] && handlers[key].$element && 
            handlers[key].$element[0] === $widget[0]) {
            if (handlers[key].runMasonry) {
                setTimeout(function() {
                    handlers[key].runMasonry();
                }, 500);
            }
            break;
        }
    }
}

// GLOBAL SIMPLE MASONRY FUNCTION (as backup)
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
    var gap = 20;
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
        elementorFrontend.elementsHandler.addHandler(RaelPostsHandler, {
            $element: $element,
        });
    };
    
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_classic", addHandler);
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_cards", addHandler);
});

// AJAX AND FILTERING FUNCTIONALITY (UNCHANGED)
var paged_no = 1;
var $ = jQuery.noConflict();

// Initialize on document ready
$(document).ready(function() {
    
    // Run global masonry as fallback
    setTimeout(function() {
        var containers = document.querySelectorAll('.responsive-posts-container');
        containers.forEach(function(container) {
            var $widget = $(container).closest('.elementor-widget-rael-posts');
            if ($widget.length) {
                refreshRaelMasonry($widget);
            } else {
                simpleMasonryGlobal('.elementor-posts-masonry');
            }
        });
    }, 1000);
});

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

    $.ajax({
        type: 'POST',
        url: localize.ajaxurl,
        data: {
            action: 'rael_get_posts_by_terms',
            data: {
                term: term,
                postPerPage: postPerPage,
                paged: paged,
                pid: pid,
                widget_id: widget_id,
                skin: skin
            },
            nonce: localize.nonce
        },
        success: function success(data) {
            var sel = $scope.find('.elementor-posts-masonry');
            if (sel.data('pagination') === 'infinite') {
                $scope.find('.responsive-post-load-more-loader').remove();
                sel.append(data.html);
                sel.next('.rael-post-pagination').first().remove();
                $(data.pagination).insertAfter(sel);
                
                // Refresh masonry after load more
                setTimeout(function() {
                    refreshRaelMasonry($scope);
                    // Run global as backup
                    setTimeout(function() {
                        simpleMasonryGlobal('.elementor-posts-masonry');
                    }, 1000);
                }, 500);
            }
        }
    });
});

function callAjax(term, postPerPage, paged, pid, $scope, skin) {
    let widget_id = $scope.data('id');
    $.ajax({
        type: 'POST',
        url: localize.ajaxurl,
        data: {
            action: 'rael_get_posts_by_terms',
            data: {
                term: term,
                postPerPage: postPerPage,
                paged: paged,
                pid: pid,
                widget_id: widget_id,
                skin: skin
            }
        },
        success: function success(response) {
            if (!response || response.success !== true) {
                console.error('RAEL AJAX failed', response);
                $('.responsive-post-loader').remove();
                return;
            }

            var sel = $scope.find('.elementor-posts-masonry');
            sel.html(response.data.html);

            sel.next('.rael-post-pagination').remove();
            $(response.data.pagination).insertAfter(sel);
            
            // Remove loader
            $('.responsive-post-loader').remove();
            
            // Refresh masonry after AJAX
            setTimeout(function() {
                refreshRaelMasonry($scope);
                
                // Also try global function as backup
                setTimeout(function() {
                    simpleMasonryGlobal('.elementor-posts-masonry');
                }, 800);
            }, 500);
        },
        error: function(xhr, status, error) {
            console.error('RAEL AJAX error:', error);
            $('.responsive-post-loader').remove();
        }
    });
}

// Add required CSS
var masonryCSS = `
    /* Masonry Container - CRITICAL */
    .responsive-posts-container {
        position: relative !important;
        min-height: 100px;
    }
    
    /* Masonry Items - CRITICAL */
    .elementor-post {
        box-sizing: border-box !important;
    }
    
    /* Debug borders */
    .responsive-posts-container .elementor-post {
        /* border: 1px solid rgba(255,0,0,0.2); */
    }
`;

// Inject CSS
if (typeof document !== 'undefined') {
    var style = document.createElement('style');
    style.type = 'text/css';
    style.appendChild(document.createTextNode(masonryCSS));
    document.head.appendChild(style);
}

// Debug function to run in console
window.debugMasonry = function() {
    var container = document.querySelector('.elementor-posts-masonry');
    if (!container) {
        console.error('No container found');
        return;
    }

    
    // Try to run global masonry
    simpleMasonryGlobal('.elementor-posts-masonry');
};

// Run masonry on window load (backup)
window.addEventListener('load', function() {
    setTimeout(function() {
        simpleMasonryGlobal('.elementor-posts-masonry');
    }, 1500);
});

// Run on resize
window.addEventListener('resize', function() {
    setTimeout(function() {
        var containers = document.querySelectorAll('.elementor-posts-masonry');
        containers.forEach(function(container) {
            var $widget = $(container).closest('.elementor-widget-rael-posts');
            if ($widget.length) {
                refreshRaelMasonry($widget);
            }
        });
    }, 200);
});