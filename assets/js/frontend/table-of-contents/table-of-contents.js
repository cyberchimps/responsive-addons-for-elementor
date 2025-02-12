class RaelTocHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {

        var elementSettings = this.getElementSettings(),
            listTagWrapper = 'numbers' === elementSettings.rael_marker_view ? 'ol' : 'ul';
        return {
            selectors: {
                body: '.rael-toc__body',
                expandButton: '.rael-toc__toggle-button--expand',
                headerTitle: '.rael-toc__header-title',
                collapseButton: '.rael-toc__toggle-button--collapse',
                postContentContainer: '.elementor:not([data-elementor-type="header"]):not([data-elementor-type="footer"]):not([data-elementor-type="popup"])',
                widgetContainer: '.elementor-widget-rael-table-of-contents'
            },
            classes: {
                activeItem: 'elementor-item-active',
                firstLevelListItem: 'rael-toc__top-level',
                listTextWrapper: 'rael-toc__list-item-text-wrapper',
                listWrapper: 'rael-toc__list-wrapper',
                listItem: 'rael-toc__list-item',
                listItemText: 'rael-toc__list-item-text',
                collapsed: 'rael-toc--collapsed',
                headingAnchor: 'rael-toc__heading-anchor',
                anchor: 'elementor-menu-anchor'
            },
            listTagWrapper: listTagWrapper
        };
    }

    getDefaultElements() {
        var settings = this.getSettings();
        return {
            $tocBody: this.$element.find(settings.selectors.body),
            $listItems: this.$element.find('.' + settings.classes.listItem),
            $widgetContainer: this.$element.find(settings.selectors.widgetContainer),
            $expandButton: this.$element.find(settings.selectors.expandButton),
            $collapseButton: this.$element.find(settings.selectors.collapseButton),
            $pageContainer: this.getContainer()
        };
    }

    bindEvents() {
        var self = this;

        var elementSettings = this.getElementSettings();

        if (elementSettings.rael_minimize_box) {
            this.elements.$expandButton.on('click', function () {
                return self.expandBox();
            });
            this.elements.$collapseButton.on('click', function () {
                return self.collapseBox();
            });
        }

        if (elementSettings.rael_collapse_subitems) {
            this.elements.$listItems.on('hover', function (event) {
                return jQuery(event.target).slideToggle();
            });
        }
    }

    expandBox() {

        var boxHeight = this.getCurrentDeviceSetting('rael_min_height');
        this.$element.removeClass(this.getSettings('classes.collapsed'));
        this.elements.$tocBody.slideDown();

        this.elements.$widgetContainer.css('min-height', boxHeight.size + boxHeight.unit);
    }

    collapseBox() {
        this.$element.addClass(this.getSettings('classes.collapsed'));
        this.elements.$tocBody.slideUp();

        this.elements.$widgetContainer.css('min-height', '0px');
    }

    addAnchorTagsBeforeHeadings() {
        var self = this;

        var classes = this.getSettings('classes');

        this.elements.$headings.before(function (index) {
            if (jQuery(self.elements.$headings[index]).data('hasOwnID')) {
                return;
            }

            return "<span id=\"".concat(classes.headingAnchor, "-").concat(index, "\" class=\"").concat(classes.anchor, " \"></span>");
        });
    }

    getHeadingLink(index, classes) {
        var headingID = this.elements.$headings[index].id,
            wrapperID = this.elements.$headings[index].closest('.elementor-widget').id;
        var anchorLink = '';

        if (headingID) {
            anchorLink = headingID;
        } else if (wrapperID) {
            anchorLink = wrapperID;
        }


        if (headingID || wrapperID) {
            jQuery(this.elements.$headings[index]).data('hasOwnID', true);
        } else {
            anchorLink = "".concat(classes.headingAnchor, "-").concat(index);
        }

        return anchorLink;
    }

    setHeadingsData() {
        var self = this;

        this.headingsData = [];
        var classes = this.getSettings('classes');

        this.elements.$headings.each(function (index, element) {
            var anchorLink = self.getHeadingLink(index, classes);

            self.headingsData.push({
                tag: +element.nodeName.slice(1),
                text: element.textContent,
                anchorLink: anchorLink
            });
        });
    }

    getNestedLevel(level) {
        var settings = this.getSettings(),
            elementSettings = this.getElementSettings(),
            icon = this.getElementSettings('rael_icon');

        var html = "<".concat(settings.listTagWrapper, " class=\"").concat(settings.classes.listWrapper, "\">");

        while (this.listItemPointer < this.headingsData.length) {
            var currentItem = this.headingsData[this.listItemPointer];
            var listItemTextClasses = settings.classes.listItemText;

            if (0 === currentItem.level) {
                listItemTextClasses += ' ' + settings.classes.firstLevelListItem;
            }

            if (level > currentItem.level) {
                break;
            }

            if (level === currentItem.level) {
                html += "<li class=\"".concat(settings.classes.listItem, "\">");
                html += "<div class=\"".concat(settings.classes.listTextWrapper, "\">");
                var liContent = "<a href=\"#".concat(currentItem.anchorLink, "\" class=\"").concat(listItemTextClasses, "\">").concat(currentItem.text, "</a>");

                if ('bullets' === elementSettings.rael_marker_view && icon) {
                    liContent = "<i class=\"".concat(icon.value, "\"></i>").concat(liContent);
                }

                html += liContent;
                html += '</div>';
                this.listItemPointer++;
                var nextItem = this.headingsData[this.listItemPointer];

                if (nextItem && level < nextItem.level) {
                    html += this.getNestedLevel(nextItem.level);
                }

                html += '</li>';
            }
        }

        html += "</".concat(settings.listTagWrapper, ">");
        return html;
    }

    noHeadingsFound() {
        var noHeadingsText = 'No headings were found on this page.';

        if (elementorFrontend.isEditMode()) {
            noHeadingsText = 'No headings were found on this page.';
        }

        return this.elements.$tocBody.html(noHeadingsText);
    }

    collapseOnInit() {
        var minimizedOn = this.getElementSettings('rael_minimized_on'),
            currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

        if ('tablet' === minimizedOn && 'desktop' !== currentDeviceMode || 'mobile' === minimizedOn && 'mobile' === currentDeviceMode) {
            this.collapseBox();
        }
    }

    getHeadings() {
        var elementSettings = this.getElementSettings(),
            tags = elementSettings.rael_headings_by_tags.join(','),
            selectors = this.getSettings('selectors'),
            excludedHeadingSelectors = elementSettings.rael_exclude_headings_by_selector;
        return this.elements.$pageContainer.find(tags).not(selectors.headerTitle).filter(function (index, heading) {
            return !jQuery(heading).closest(excludedHeadingSelectors).length;
        });
    }

    createNestedList() {
        var self = this;

        this.headingsData.forEach(function (heading, index) {
            heading.level = 0;

            for (var i = index - 1; i >= 0; i--) {
                var currentOrderedItem = self.headingsData[i];

                if (currentOrderedItem.tag <= heading.tag) {
                    heading.level = currentOrderedItem.level;

                    if (currentOrderedItem.tag < heading.tag) {
                        heading.level++;
                    }

                    break;
                }
            }
        });
        this.elements.$tocBody.html(this.getNestedLevel(0));
    }

    deactivateActiveItem($activeToBe) {
        if (!this.$activeItem || this.$activeItem.is($activeToBe)) {
            return;
        }

        var settings = this.getSettings(),
            classes = settings.classes;

        this.$activeItem.removeClass(classes.activeItem);

        if (this.$activeList && (!$activeToBe || !this.$activeList[0].contains($activeToBe[0]))) {
            this.$activeList.slideUp();
        }
    }

    activateItem($listItem) {

        var classes = this.getSettings('classes');
        this.deactivateActiveItem($listItem);
        $listItem.addClass(classes.activeItem);
        this.$activeItem = $listItem;

        if (!this.getElementSettings('rael_collapse_subitems')) {
            return;
        }

        var $activeList;

        if ($listItem.hasClass(classes.firstLevelListItem)) {
            $activeList = $listItem.parent().next();
        } else {
            $activeList = $listItem.parents('.' + classes.listWrapper).eq(-2);
        }

        if (!$activeList.length) {
            delete this.$activeList;
            return;
        }

        this.$activeList = $activeList;
        this.$activeList.stop().slideDown();
    }

    onListItemClick(event) {
        var self = this;

        this.itemClicked = true;
        setTimeout(function () {
            return self.itemClicked = false;
        }, 2000);
        var $clickedItem = jQuery(event.target),
            $list = $clickedItem.parent().next(),
            collapseNestedList = this.getElementSettings('rael_collapse_subitems');
        var listIsActive;

        if (collapseNestedList && $clickedItem.hasClass(this.getSettings('classes.firstLevelListItem'))) {
            if ($list.is(':visible')) {
                listIsActive = true;
            }
        }

        this.activateItem($clickedItem);

        if (collapseNestedList && listIsActive) {
            $list.slideUp();
        }
    }

    followAnchor($element, index) {
        var self = this;

        var anchorSelector = $element[0].hash;
        var $anchor;

        try {
            // `decodeURIComponent` for UTF8 characters in the hash.
            $anchor = jQuery(decodeURIComponent(anchorSelector));
        } catch (e) {
            return;
        }

        elementorFrontend.waypoint($anchor, function (direction) {
            if (self.itemClicked) {
                return;
            }

            var id = $anchor.attr('id');

            if ('down' === direction) {
                self.viewportItems[id] = true;

                self.activateItem($element);
            } else {
                delete self.viewportItems[id];

                self.activateItem(self.$listItemTexts.eq(index - 1));
            }
        }, {
            offset: 'bottom-in-view',
            triggerOnce: false
        });

        elementorFrontend.waypoint($anchor, function (direction) {
            if (self.itemClicked) {
                return;
            }

            var id = $anchor.attr('id');

            if ('down' === direction) {
                delete self.viewportItems[id];

                if ( Object.keys( self.viewportItems ).length ) {
                    self.activateItem(self.$listItemTexts.eq(index + 1));
                }
            } else {
                self.viewportItems[id] = true;

                self.activateItem($element);
            }
        }, {
            offset: 0,
            triggerOnce: false
        });
    }

    followAnchors() {
        var self = this;

        this.$listItemTexts.each(function (index, element) {
            return self.followAnchor(jQuery(element), index);
        });
    }

    createFlatList() {
        this.elements.$tocBody.html(this.getNestedLevel());
    }

    populateTOC() {
        this.listItemPointer = 0;
        var elementSettings = this.getElementSettings();

        if (elementSettings.rael_hierarchical_view) {
            this.createNestedList();
        } else {
            this.createFlatList();
        }

        this.$listItemTexts = this.$element.find('.rael-toc__list-item-text');
        this.$listItemTexts.on('click', this.onListItemClick.bind(this));

        if (!elementorFrontend.isEditMode()) {
            this.followAnchors();
        }
    }

    run() {
        this.elements.$headings = this.getHeadings();

        if (!this.elements.$headings.length) {
            return this.noHeadingsFound();
        }

        this.setHeadingsData();

        if (!elementorFrontend.isEditMode()) {
            this.addAnchorTagsBeforeHeadings();
        }

        this.populateTOC();

        if (this.getElementSettings('rael_minimize_box')) {
            this.collapseOnInit();
        }
    }

    onInit() {
        var self = this;

        for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
            args[_key] = arguments[_key];
        }

        super.onInit(args);

        this.viewportItems = [];
        jQuery(function () {
            return self.run();
        });
    }

    getContainer() {
        var settings = this.getSettings(),
            elementSettings = this.getElementSettings();

        if (elementSettings.container) {
            return jQuery(elementSettings.container);
        }


        var $documentWrapper = this.$element.parents('.elementor');

        if ('popup' === $documentWrapper.attr('data-elementor-type')) {
            return $documentWrapper;
        }


        return jQuery(settings.selectors.postContentContainer);
    }
}

jQuery(window).on("elementor/frontend/init", () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RaelTocHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-table-of-contents.default", addHandler);

});