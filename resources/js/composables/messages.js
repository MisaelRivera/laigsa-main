import { usePage } from "@inertiajs/vue3";
export function useMessages () {
    const getMessage = () => {
        return usePage().props.flash.message;
    };

    const getError = () => {
        return usePage().props.flash.error;
    };

    return { getMessage, getError };
}