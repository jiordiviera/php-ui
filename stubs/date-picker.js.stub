export default (initialValue) => ({
    open: false,
    value: initialValue || '',
    displayValue: '',
    viewMonth: new Date().getMonth(),
    viewYear: new Date().getFullYear(),
    selectedDay: null,
    selectedMonth: null,
    selectedYear: null,
    minDate: null,
    maxDate: null,
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],

    init() {
        if (this.value) this.parseValue(this.value);
        this.$watch('value', (val) => {
            if (val) this.parseValue(val);
            else {
                this.displayValue = '';
                this.selectedDay = null;
                this.selectedMonth = null;
                this.selectedYear = null;
            }
        });
    },

    get emptyDays() {
        let first = new Date(this.viewYear, this.viewMonth, 1).getDay();
        return first === 0 ? 6 : first - 1;
    },

    get daysInMonth() {
        const count = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
        return Array.from({ length: count }, (_, i) => i + 1);
    },

    parseValue(val) {
        const parts = val.split('/');
        if (parts.length === 3) {
            this.selectedDay = parseInt(parts[0]);
            this.selectedMonth = parseInt(parts[1]) - 1;
            this.selectedYear = parseInt(parts[2]);
            this.viewMonth = this.selectedMonth;
            this.viewYear = this.selectedYear;
            this.displayValue = `${this.selectedDay} ${this.monthNames[this.selectedMonth]} ${this.selectedYear}`;
        }
    },

    toggle() { this.open = !this.open; },

    prevMonth() {
        if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; }
        else { this.viewMonth--; }
    },

    nextMonth() {
        if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; }
        else { this.viewMonth++; }
    },

    selectDate(day) {
        if (this.isDisabled(day)) return;
        this.selectedDay = day;
        this.selectedMonth = this.viewMonth;
        this.selectedYear = this.viewYear;
        const d = String(day).padStart(2, '0');
        const m = String(this.viewMonth + 1).padStart(2, '0');
        this.value = `${d}/${m}/${this.viewYear}`;
        this.displayValue = `${day} ${this.monthNames[this.viewMonth]} ${this.viewYear}`;
        this.open = false;
    },

    isSelected(day) {
        return day === this.selectedDay && this.viewMonth === this.selectedMonth && this.viewYear === this.selectedYear;
    },

    isToday(day) {
        const today = new Date();
        return day === today.getDate() && this.viewMonth === today.getMonth() && this.viewYear === today.getFullYear();
    },

    isDisabled(day) {
        const date = new Date(this.viewYear, this.viewMonth, day);
        if (this.minDate) {
            const min = new Date(this.minDate);
            min.setHours(0, 0, 0, 0);
            if (date < min) return true;
        }
        if (this.maxDate) {
            const max = new Date(this.maxDate);
            max.setHours(23, 59, 59, 999);
            if (date > max) return true;
        }
        return false;
    },

    goToday() {
        const today = new Date();
        this.viewMonth = today.getMonth();
        this.viewYear = today.getFullYear();
        if (!this.isDisabled(today.getDate())) {
            this.selectDate(today.getDate());
        }
    },

    clear() {
        this.value = '';
        this.displayValue = '';
        this.selectedDay = null;
        this.selectedMonth = null;
        this.selectedYear = null;
    }
})
