export default (options = {}) => ({
    multiple: options.multiple || false,
    openItems: options.defaultOpen !== null ? [options.defaultOpen] : [],

    toggle(index) {
        if (this.multiple) {
            const idx = this.openItems.indexOf(index);
            if (idx > -1) {
                this.openItems.splice(idx, 1);
            } else {
                this.openItems.push(index);
            }
        } else {
            this.openItems = this.isOpen(index) ? [] : [index];
        }
    },

    isOpen(index) {
        return this.openItems.includes(index);
    }
})
