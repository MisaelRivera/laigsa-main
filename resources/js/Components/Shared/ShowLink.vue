<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        url: String,
    });
</script>

<template>
     <Link :href="url" replace>
        <i class="fas fa-eye text-blue-400"></i>
    </Link>
</template>