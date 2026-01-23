class RaelCountdownHandler extends elementorModules.frontend.handlers.Base {
    cacheElements() {
        var $countDown = this.$element.find('.responsive-countdown-wrapper');
        this.cache = {
            $countDown: $countDown,
            timeInterval: null,
            elements: {
                $countdown: $countDown.find('.responsive-countdown-wrapper'),
                $daysSpan: $countDown.find('.responsive-countdown-days'),
                $hoursSpan: $countDown.find('.responsive-countdown-hours'),
                $minutesSpan: $countDown.find('.responsive-countdown-minutes'),
                $secondsSpan: $countDown.find('.responsive-countdown-seconds'),
                $expireMessage: $countDown.parent().find('.responsive-countdown-expire--message')
            },
            data: {
                id: this.$element.data('id'),
                endTime: new Date($countDown.data('date') * 1000),
                actions: $countDown.data('expire-actions'),
                evergreenInterval: $countDown.data('evergreen-interval')
            }
        };
    }

    onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.cacheElements();
        if (0 < this.cache.data.evergreenInterval) {
            this.cache.data.endTime = this.getEvergreenDate();
        }
        this.initializeClock();
    }

    updateClock() {
        var self = this, timeRemaining = this.getTimeRemaining(this.cache.data.endTime);
        jQuery.each(timeRemaining.parts, function (timePart) {
            var $element = self.cache.elements['$' + timePart + 'Span'];
            var partValue = this.toString();
            if (1 === partValue.length) {
                partValue = 0 + partValue;
            }
            if ($element.length) {
                $element.text(partValue);
            }
        });
        if (timeRemaining.total <= 0) {
            clearInterval(this.cache.timeInterval);
            this.runActions();
        }
    }

    initializeClock() {
        var self = this;
        this.updateClock();
        this.cache.timeInterval = setInterval(function () {
            self.updateClock();
        }, 1000);
    }

    runActions() {
        var self = this;
        self.$element.trigger('countdown_expire', self.$element);
        if (!this.cache.data.actions) {
            return;
        }
        this.cache.data.actions.forEach(function (action) {
            switch (action.type) {
                case 'hide':
                    self.cache.$countDown.hide();
                    break;
                case 'redirect':
                    if (action.redirect_url) {
                        window.location.href = action.redirect_url;
                    }
                    break;
                case 'message':
                    self.cache.elements.$expireMessage.show();
                    break;
            }
        });
    }

    getTimeRemaining(endTime) {
        var timeRemaining = endTime - new Date();
        var seconds = Math.floor(timeRemaining / 1000 % 60), minutes = Math.floor(timeRemaining / 1000 / 60 % 60), hours = Math.floor(timeRemaining / (1000 * 60 * 60) % 24), days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        if (days < 0 || hours < 0 || minutes < 0) {
            seconds = minutes = hours = days = 0;
        }
        return {
            total: timeRemaining,
            parts: {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds
            }
        };
    }

    getEvergreenDate() {
        var self = this, id = this.cache.data.id, interval = this.cache.data.evergreenInterval, dueDateKey = id + '-evergreen_due_date', intervalKey = id + '-evergreen_interval', localData = {
            dueDate: localStorage.getItem(dueDateKey),
            interval: localStorage.getItem(intervalKey)
        }, initEvergreen = function initEvergreen() {
            var evergreenDueDate = new Date();
            self.cache.data.endTime = evergreenDueDate.setSeconds(evergreenDueDate.getSeconds() + interval);
            localStorage.setItem(dueDateKey, self.cache.data.endTime);
            localStorage.setItem(intervalKey, interval);
            return self.cache.data.endTime;
        };
        if (null === localData.dueDate && null === localData.interval) {
            return initEvergreen();
        }
        if (null !== localData.dueDate && interval !== parseInt( localData.interval, 10 )) {
            return initEvergreen();
        }

        if (localData.dueDate > 0 && (parseInt( localData.interval, 10 ) === interval)) {
            return localData.dueDate;
        }
    }
}


jQuery(window).on("elementor/frontend/init", () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(RaelCountdownHandler, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/rael-countdown.default", addHandler);

});