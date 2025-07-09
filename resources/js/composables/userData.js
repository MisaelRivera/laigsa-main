import { usePage } from "@inertiajs/vue3";

export function useUserData () {
    const getName = () => usePage().props.auth.user.name;
    const getEmail = () => usePage().props.auth.user.email;
    const getId = () => usePage().props.auth.user.id;

    return { getName, getEmail, getId };
}