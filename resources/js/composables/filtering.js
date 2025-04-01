import { router } from '@inertiajs/vue3';

export function useFilter (ev, filterName, routeName) {
    const textFilter = () => {
        const filtersCopy = {};
        const value = ev.target.value;
        filters[filterName] = value;
        Array.from(Object.keys(filters)).forEach(item => {
            if (filters[item] !== null && filters[item] !== '') {
                filtersCopy[item] = filters[item];
            }
        });

        router.visit(route(routeName, filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    }

    const checkboxFilter = () => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('cesavedac')) {
            activeFilters.value.push('cesavedac');
        }
        filters['cesavedac'] = value;
        Array.from(Object.keys(filters)).forEach(item => {
            if (filters[item] !== null && filters[item] !== '') {
                filtersCopy[item] = filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };
}