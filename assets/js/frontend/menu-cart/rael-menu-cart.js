class MiniCartHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                container: '.rael-menu-cart__container',
                main: '.rael-menu-cart__main',
                toggle: '.rael-menu-cart__toggle .elementor-button',
                closeButton: '.rael-menu-cart__close-button',
                cartLink: '#rael-menu-cart__toggle_button'
            },
            classes: {
                isShown: 'rael-menu-cart--shown',
                lightbox: 'elementor-lightbox'
            }
        };
    }

    getDefaultElements() {
        var selectors = this.getSettings('selectors'), elements = {};
        elements.$container = this.$element.find(selectors.container);
        elements.$main = this.$element.find(selectors.main);
        elements.$toggle = this.$element.find(selectors.toggle);
        elements.$closeButton = this.$element.find(selectors.closeButton);
        elements.$cartLink = this.$element.find(selectors.cartLink);
        return elements;
    }

    toggleAriaExpanded($element) {
        $element.attr('aria-expanded', function (index, isExpanded) {
            if (typeof isExpanded !== 'undefined') {
                return 'true' !== isExpanded;
            }
            return true;
        });
    }

    removeAttributesOnHide() {
        var elements = this.elements,
            $container = elements.$container,
            $main = elements.$main,
            classes = this.getSettings('classes');
        $container.removeClass(classes.isShown).attr('aria-expanded', false);
        $main.attr('aria-expanded', false);
    }

    bindEvents() {
        var elements = this.elements,
            $container = elements.$container,
            $main = elements.$main,
            $toggle = elements.$toggle,
            $closeButton = elements.$closeButton,
            $cartLink = elements.$cartLink,
            classes = this.getSettings('classes');

        $toggle.bind('click', {self:this}, function (event) {

            var self = event.data.self;

            var noQueryParams = -1 === RAELFrontendConfig.menu_cart.cart_page_url.indexOf('?'),
                currentUrl = noQueryParams ? window.location.origin + window.location.pathname : window.location.href,
                isCart = RAELFrontendConfig.menu_cart.cart_page_url === currentUrl,
                isCheckout = RAELFrontendConfig.menu_cart.checkout_page_url === currentUrl;

            if (!isCart && !isCheckout) {
                event.preventDefault();
                $container.toggleClass(classes.isShown);
                self.toggleAriaExpanded($container);
                self.toggleAriaExpanded($main);
            } else {
                var cartUrl = RAELFrontendConfig.menu_cart.cart_page_url;
                $cartLink.attr('href', cartUrl);
                self.removeAttributesOnHide();
            }
        });

        $container.bind('click', {self:this}, function (event) {
            var self = event.data.self;
            if ($container.hasClass(classes.isShown) && $container[0] === event.target) {
                self.removeAttributesOnHide();
            }
        });

        $closeButton.bind('click', {self:this}, function (event) {
            var self = event.data.self;
            self.removeAttributesOnHide();
        });

        elementorFrontend.elements.$document.bind('keyup', function (event) {
            var ESC_KEY = 27;
            if (ESC_KEY === event.keyCode) {
                if ($container.hasClass(classes.isShown)) {
                    $container.click();
                }
            }
        });
    }
}
jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(MiniCartHandler, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/rael-wc-menu-cart.default",
        addHandler
    );

    if (elementorFrontend.isEditMode()) {
        return;
    }

    jQuery(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function () {
        jQuery('div.elementor-widget-rael-wc-menu-cart').each(function () {
            elementorFrontend.elementsHandler.runReadyTrigger(jQuery(this));
        });
    });
});