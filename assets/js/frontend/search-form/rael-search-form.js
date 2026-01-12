class RAELSearchBerHandler extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
        return {
            selectors: {
                wrapper: '.rael-elementor-search-form',
                container: '.rael-elementor-search-form__container',
                icon: '.rael-elementor-search-form__icon',
                input: '.rael-elementor-search-form__input',
                toggle: '.rael-elementor-search-form__toggle',
                submit: '.rael-elementor-search-form__submit',
                closeButton: '.dialog-close-button'
            },
            classes: {
                isFocus: 'rael-elementor-search-form--focus',
                isFullScreen: 'rael-elementor-search-form--full-screen',
                lightbox: 'elementor-lightbox'
            }
        };
    }

    getDefaultElements() {
        var selectors = this.getSettings('selectors'),
            elements = {};
        elements.$wrapper = this.$element.find(selectors.wrapper);
        elements.$container = this.$element.find(selectors.container);
        elements.$input = this.$element.find(selectors.input);
        elements.$icon = this.$element.find(selectors.icon);
        elements.$toggle = this.$element.find(selectors.toggle);
        elements.$submit = this.$element.find(selectors.submit);
        elements.$closeButton = this.$element.find(selectors.closeButton);
        return elements;
    }

    bindEvents() {
        var self = this,
            $container = self.elements.$container,
            $closeButton = self.elements.$closeButton,
            $input = self.elements.$input,
            $wrapper = self.elements.$wrapper,
            $icon = self.elements.$icon,
            skin = this.getElementSettings('rael_skin'),
            classes = this.getSettings('classes');

        if ('full_screen' === skin) {
            // Activate full-screen mode on click
            self.elements.$toggle.on('click', function () {
                $container.toggleClass(classes.isFullScreen).toggleClass(classes.lightbox);
                $input.focus();
            }); // Deactivate full-screen mode on click or on esc.

            $container.on('click', function (event) {
                if ($container.hasClass(classes.isFullScreen) && $container[0] === event.target) {
                    $container.removeClass(classes.isFullScreen).removeClass(classes.lightbox);
                }
            });
            $closeButton.on('click', function () {
                $container.removeClass(classes.isFullScreen).removeClass(classes.lightbox);
            });
            elementorFrontend.elements.$document.keyup(function (event) {
                var ESC_KEY = 27;

                if (ESC_KEY === event.keyCode) {
                    if ($container.hasClass(classes.isFullScreen)) {
                        $container.click();
                    }
                }
            });
        } else {
            // Apply focus style on wrapper element when input is focused
            $input.on({
                focus: function focus() {
                    $wrapper.addClass(classes.isFocus);
                },
                blur: function blur() {
                    $wrapper.removeClass(classes.isFocus);
                }
            });
        }

        if ('minimal' === skin) {
            // Apply focus style on wrapper element when icon is clicked in minimal skin
            $icon.on('click', function () {
                $wrapper.addClass(classes.isFocus);
                $input.focus();
            });
        }
    }
}

jQuery(window).on("elementor/frontend/init", () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RAELSearchBerHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-search-form.default", addHandler);

});