<script setup>
    import { Link } from '@inertiajs/vue3';
    defineProps({
        links: Array
    });
</script>
<template>
    <div class="px-2 py-2 flex items-center">
        <template v-for="link in links">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-1"
                :class="{'text-green-600': link.active}"/>
            <span v-else
                :href="link.url"
                v-html="link.label"
                class="px-1 text-gray-500"></span>
        </template>
    </div>
</template>