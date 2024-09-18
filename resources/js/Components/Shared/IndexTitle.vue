<script setup>
    import { Link } from '@inertiajs/vue3';
    defineProps({
        title: {
            type: String,
            required: true,
        },

        addLink: {
            type: String,
            default: null
        },

        ownLink: {
            type: String
        },
    });
</script>
<template>
    <div class="flex">
        <Link 
            :href="ownLink">
            <h1 class="text-5xl text-black no-underline font-black">
                {{ title }}
            </h1>
        </Link>
        <Link 
            v-if="addLink"
            :href="addLink">
            <i class="fas fa-plus p-3 rounded-full bg-green-400 text-white"></i>
        </Link>
    </div>
</template>