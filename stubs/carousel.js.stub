export default (options = {}) => ({
    current: 0,
    total: options.total || 0,
    autoplay: options.autoplay || false,
    interval: options.interval || 5000,
    timer: null,

    init() {
        if (this.autoplay && this.total > 1) {
            this.startAutoplay();
        }
    },

    next() {
        this.current = (this.current + 1) % this.total;
        this.resetAutoplay();
    },

    prev() {
        this.current = (this.current - 1 + this.total) % this.total;
        this.resetAutoplay();
    },

    goTo(index) {
        this.current = index;
        this.resetAutoplay();
    },

    startAutoplay() {
        this.timer = setInterval(() => this.next(), this.interval);
    },

    resetAutoplay() {
        if (this.autoplay && this.timer) {
            clearInterval(this.timer);
            this.startAutoplay();
        }
    }
})
