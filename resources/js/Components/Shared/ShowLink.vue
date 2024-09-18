<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        url: String,
    });
</script>

<template>
     <Link :href="url" replace>
        
    </Link>
</template>