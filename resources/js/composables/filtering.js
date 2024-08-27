import { router } from '@inertiajs/vue3';

export function useFilter () {
    const filterIndex = (ev, url, property) => {
        const value = ev.target.value;
        const data = {};
        data[property] = value;
        router.visit(route(url, data), {
            preserveState: true,
            method: 'get'
        });
    };
}