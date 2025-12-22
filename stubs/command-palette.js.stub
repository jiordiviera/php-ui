export default () => ({
    selectedIndex: 0,

    init() {
        this.$watch('$wire.showCommandPalette', (value) => {
            if (value) {
                this.selectedIndex = 0;
            }
        });
    },

    navigateUp() {
        if (this.selectedIndex > 0) {
            this.selectedIndex--;
        }
    },

    navigateDown(maxItems) {
        if (this.selectedIndex < maxItems - 1) {
            this.selectedIndex++;
        }
    }
})
