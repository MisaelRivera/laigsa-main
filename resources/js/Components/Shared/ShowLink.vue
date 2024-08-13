<script setup>
    import { Link } from '@inertiajs/vue3';
    import { EyeIcon } from '@heroicons/vue/24/solid';

    defineProps({
        url: String,
    });
</script>

<template>
     <Link :href="url">
        <EyeIcon class="text-blue-400 h-4 w-4"/>
    </Link>
</template>