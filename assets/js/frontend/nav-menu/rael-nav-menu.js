var RAELNavMenu =  elementorModules.frontend.handlers.Base.extend({

    stretchElement: null,
    getDefaultSettings: function getDefaultSettings() {
        return {
            selectors: {
                menu: '.elementor-rael-nav-menu',
                anchorLink: '.elementor-rael-nav-menu--main .elementor-item-anchor',
                dropdownMenu: '.elementor-rael-nav-menu__container.elementor-rael-nav-menu--dropdown',
                menuToggle: '.elementor-rael-menu-toggle'
            }
        };
    },

    getDefaultElements: function getDefaultElements() {
        var selectors = this.getSettings('selectors'),
            elements = {};
        elements.$menu = this.$element.find(selectors.menu);
        elements.$anchorLink = this.$element.find(selectors.anchorLink);
        elements.$dropdownMenu = this.$element.find(selectors.dropdownMenu);
        elements.$dropdownMenuFinalItems = elements.$dropdownMenu.find('.menu-item:not(.menu-item-has-children) > a');
        elements.$menuToggle = this.$element.find(selectors.menuToggle);
        return elements;
    },

    bindEvents: function bindEvents() {
        if (!this.elements.$menu.length) {
            return;
        }

        this.elements.$menuToggle.on('click', this.toggleMenu.bind(this));

        if (this.getElementSettings('rael_full_width')) {
            this.elements.$dropdownMenuFinalItems.on('click', this.toggleMenu.bind(this, false));
        }

        elementorFrontend.addListenerOnce(this.$element.data('model-cid'), 'resize', this.stretchMenu);
    },

    initStretchElement: function initStretchElement() {
        this.stretchElement = new elementorModules.frontend.tools.StretchElement({
            element: this.elements.$dropdownMenu
        });
    },

    toggleMenu: function toggleMenu(show) {
        var isDropdownVisible = this.elements.$menuToggle.hasClass('elementor-active');

        if ('boolean' !== typeof show) {
            show = !isDropdownVisible;
        }

        this.elements.$menuToggle.attr('aria-expanded', show);
        this.elements.$dropdownMenu.attr('aria-hidden', !show);
        this.elements.$menuToggle.toggleClass('elementor-active', show);

        if (show && this.getElementSettings('rael_full_width')) {
            this.stretchElement.stretch();
        }
    },

    followMenuAnchors: function followMenuAnchors() {
        var self = this;
        self.elements.$anchorLink.each(function () {
            if (location.pathname === this.pathname && '' !== this.hash) {
                self.followMenuAnchor(jQuery(this));
            }
        });
    },

    followMenuAnchor: function followMenuAnchor($element) {
        var anchorSelector = $element[0].hash;
        var offset = -300,
            $anchor;

        try {
            // `decodeURIComponent` for UTF8 characters in the hash.
            $anchor = jQuery(decodeURIComponent(anchorSelector));
        } catch (e) {
            return;
        }

        if (!$anchor.length) {
            return;
        }

        if (!$anchor.hasClass('elementor-menu-anchor')) {
            var halfViewport = jQuery(window).height() / 2;
            offset = -$anchor.outerHeight() + halfViewport;
        }

        elementorFrontend.waypoint($anchor, function (direction) {
            if ('down' === direction) {
                $element.addClass('elementor-item-active');
            } else {
                $element.removeClass('elementor-item-active');
            }
        }, {
            offset: '50%',
            triggerOnce: false
        });
        elementorFrontend.waypoint($anchor, function (direction) {
            if ('down' === direction) {
                $element.removeClass('elementor-item-active');
            } else {
                $element.addClass('elementor-item-active');
            }
        }, {
            offset: offset,
            triggerOnce: false
        });
    },

    stretchMenu: function stretchMenu() {
        if (this.getElementSettings('rael_full_width')) {
            this.stretchElement.stretch();
            this.elements.$dropdownMenu.css('top', this.elements.$menuToggle.outerHeight());
        } else {
            this.stretchElement.reset();
        }
    },

    onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

        if (!this.elements.$menu.length) {
            return;
        }

        this.elements.$menu.smartmenus({
            subIndicatorsText: '<i class="fa"></i>',
            subIndicatorsPos: 'append',
            subMenusMaxWidth: '1000px'
        });
        this.initStretchElement();
        this.stretchMenu();

        if (!elementorFrontend.isEditMode()) {
            this.followMenuAnchors();
        }
    },

    onElementChange: function onElementChange(propertyName) {
        if ('rael_full_width' === propertyName) {
            this.stretchMenu();
        }
    }
});



jQuery(window).on("elementor/frontend/init", () => {

    if (jQuery.fn.smartmenus) {
        // Override the default stupid detection
        jQuery.SmartMenus.prototype.isCSSOn = function () {
            return true;
        };

        if (elementorFrontend.config.is_rtl) {
            jQuery.fn.smartmenus.defaults.rightToLeftSubMenus = true;
        }
    }

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RAELNavMenu, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction("frontend/element_ready/rael-nav-menu.default", addHandler);

});