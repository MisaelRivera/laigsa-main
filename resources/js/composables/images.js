import { usePage } from "@inertiajs/vue3";
export function useImages () {
    const getLogoUri = () => usePage().props.imgUrl.logo;
    const getBgUri = () => usePage().props.imgUrl.bg;
    return { getBgUri, getLogoUri };
}