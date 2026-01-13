class RaelCardsHandler extends elementorModules.frontend.handlers.Base {

    getSkinPrefix() {
        return 'rael_cards_';
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

jQuery(window).on("elementor/frontend/init", () => {

    const addCardHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RaelCardsHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-posts.rael_cards", addCardHandler);

});