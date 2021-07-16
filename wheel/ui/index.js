window.onload = function() {
    var carousel = new wheelCarousel({
        el: '.carousel',
        delay: 5000,
        speed: 600,
        timing: 'ease-in-out',
    });
    console.log(carousel)
}

function wheelCarousel(params) {
    this.params = Object.assign({
        el: '.wheel-carousel',
        autoplay: true,
        delay: 3000,
        speed: 300,
        timing: 'ease',
    }, params);
    this.$el = document.querySelector(this.params.el);
    this.$wrapper = this.$el.children[0];
    this.slides = this.$wrapper.children;
    this.total = this.slides.length;
    this.activeIndex = 0;
    this.$wrapper.style['transition-duration'] = this.params.speed + 'ms';
    this.$wrapper.style['transition-timing-function'] = this.params.timing;
    this.$wrapper.append(this.slides[0].cloneNode(true));
    this.init();
}
wheelCarousel.prototype.init = function() {
    var self = this;
    this._playTimer = null;
    this._pauseTimer1 = null;
    this._pauseTimer2 = null;
    if (this.params.autoplay === true) {
        this._playTimer = setInterval(function() {
            self.activeIndex++;
            if (self.activeIndex > self.total) self.activeIndex = 0;
            self.$wrapper.style.transform = 'translate3d(-' + self.activeIndex + '00%,0,0)';
            if (self.activeIndex === self.total) {
                self.activeIndex = 0;
                clearTimeout(self._pauseTimer1);
                self._pauseTimer1 = setTimeout(function() {
                    self.$wrapper.style.transition = 'none';
                    self.$wrapper.style.transform = 'translate3d(0,0,0)';
                    clearTimeout(self._pauseTimer2);
                    self._pauseTimer2 = setTimeout(() => {
                        self.$wrapper.style.transition = '';
                        clearTimeout(self._pauseTimer2);
                    }, 0);
                    clearTimeout(self._pauseTimer1);
                }, self.params.speed);
            }
        }, this.params.delay);
    }
}