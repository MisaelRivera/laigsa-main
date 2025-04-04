<script setup>
    import { Link } from '@inertiajs/vue3';
    defineProps({
        url: String,
    })
</script>

<template>
    <div class="inline-block">
        <Link 
            class="bg-blue-500 text-white flex justify-center items-center w-8 h-8 rounded-full"
            :href="url">
            <i class="fas fa-edit"></i>
        </Link>
    </div>
</template>
