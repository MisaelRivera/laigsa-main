<script setup>
    import { Link } from '@inertiajs/vue3';
    defineProps({
        title: {
            type: String,
            required: true,
        },
        backLink: {
            type: String,
            default: null
        },

        ownLink: {
            type: String
        },
    });
</script>
<template>
    <div class="flex items-center">
        <Link 
            v-if="backLink"
            :href="backLink"
            class="text-2xl mb-1 mr-2">
            <i class="fas fa-arrow-left"></i>
        </Link>
        <Link 
            :href="ownLink">
            <h1 class="text-3xl text-black no-underline font-black">
                {{ title }}
            </h1>
        </Link>
    </div>
</template>