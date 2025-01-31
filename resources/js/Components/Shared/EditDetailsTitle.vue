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

        editLink: {
            type: String
        },

        align: {
            type:String,
            default:''
        },
    });
</script>
<template>
    <div 
        class="flex items-center"
        :class="align">
        <Link 
            v-if="backLink"
            :href="backLink"
            class="text-2xl mb-1 mr-2">
            <i class="fas fa-arrow-left"></i>
        </Link>
        <h1 class="text-3xl text-black no-underline font-black">
            {{ title }}
            <Link 
                v-if="editLink"
                :href="editLink"
                class="text-2xl mb-1 mr-2">
                <i class="fas fa-edit"></i>
            </Link>
        </h1>
    </div>
</template>