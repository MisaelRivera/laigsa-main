import { usePage } from "@inertiajs/vue3"
export function usePermission (){
    const  hasRole = (name) => {
        return usePage().props.auth.user.roles.includes(name);
    };

    const getRoles = () => {
        return usePage().props.auth.user.roles;
    };

    const hasPermission = (name) => {
        return usePage().props.auth.user.permissions.includes(name);
    };

    const getPermissions = () => {
        return usePage().props.auth.user.permissions;
    };

    return { hasRole, hasPermission, getRoles, getPermissions };
}