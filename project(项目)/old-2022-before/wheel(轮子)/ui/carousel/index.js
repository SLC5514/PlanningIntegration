/* if (typeof Object.assign != 'function') {
    (function () {
        Object.assign = function (target) {
            'use strict';
            if (target === undefined || target === null) {
                throw new TypeError('Cannot convert undefined or null to object');
            }
            var output = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var source = arguments[index];
                if (source !== undefined && source !== null) {
                    for (var nextKey in source) {
                        if (source.hasOwnProperty(nextKey)) {
                            output[nextKey] = source[nextKey];
                        }
                    }
                }
            }
            return output;
        };
    })();
} */

window.onload = function () {
    var carousel = new wheelCarousel();
    console.log(carousel);
};

function wheelCarousel(params) {
    this.params = Object.assign(
        {
            el: '.wheel-carousel',
            autoplay: true,
            delay: 3000,
            speed: 300,
            timing: 'ease',
        },
        params
    );
    this.$el = document.querySelector(this.params.el);
    this.$wrapper = this.$el.children[0];
    this.slides = this.$wrapper.children;
    this.total = this.slides.length;
    this.activeIndex = 0;
    this.$wrapper.style['transition-duration'] = this.params.speed + 'ms';
    this.$wrapper.style['transition-timing-function'] = this.params.timing;
    this.$wrapper.appendChild(this.slides[0].cloneNode(true));
    this.autoplay();
    this.initEvents();
}
wheelCarousel.prototype.autoplay = function () {
    var self = this;
    this._playTimer = null;
    this._pauseTimer1 = null;
    this._pauseTimer2 = null;
    if (this.params.autoplay === true) {
        clearInterval(this._playTimer);
        clearTimeout(this._pauseTimer1);
        clearTimeout(this._pauseTimer2);
        this._playTimer = setInterval(function () {
            self.activeIndex++;
            if (self.activeIndex > self.total) self.activeIndex = 0;
            self.$wrapper.style.transform = 'translate3d(-' + self.activeIndex + '00%,0,0)';
            if (self.activeIndex >= self.total) {
                self.activeIndex = 0;
                clearTimeout(self._pauseTimer1);
                self._pauseTimer1 = setTimeout(function () {
                    self.$wrapper.style.transition = 'none';
                    self.$wrapper.style.transform = 'translate3d(0,0,0)';
                    clearTimeout(self._pauseTimer2);
                    self._pauseTimer2 = setTimeout(function () {
                        self.$wrapper.style.transition = '';
                        self.$wrapper.style['transition-duration'] = self.params.speed + 'ms';
                        self.$wrapper.style['transition-timing-function'] = self.params.timing;
                        clearTimeout(self._pauseTimer2);
                    }, 0);
                    clearTimeout(self._pauseTimer1);
                }, self.params.speed);
            }
        }, this.params.delay);
    }
}
wheelCarousel.prototype.initEvents = function () {
    var self = this;
    this.$el.addEventListener('mouseenter', function() {
        if (self.params.autoplay === true) {
            clearInterval(self._playTimer);
            clearTimeout(self._pauseTimer1);
            clearTimeout(self._pauseTimer2);
        }
    })
    this.$el.addEventListener('mouseleave', function() {
        if (self.params.autoplay === true) {
            self.autoplay();
        }
    })
};