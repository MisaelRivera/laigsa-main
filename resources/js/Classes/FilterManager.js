import { router } from '@inertiajs/vue3';

export default class FilterManager {
    constructor(filters) {
        this.filters = filters;
    }

    applyFilter(key, value) {
        this.filters[key] = value;
        this.visitWithFilters();
    }

    removeFilter(key) {
        this.filters[key] = null;
        this.visitWithFilters();
    }

    visitWithFilters() {
        const activeFilters = {};

        Object.keys(this.filters).forEach(key => {
            if (this.filters[key] !== null && this.filters[key] !== '') {
                activeFilters[key] = this.filters[key];
            }
        });

        router.visit(route('orders.index', activeFilters), {
            preserveState: true,
            method: 'get',
        });
    }
}