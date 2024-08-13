<script setup>
    import { Link } from '@inertiajs/vue3';
    import { EditOutlined } from '@ant-design/icons-vue';
    defineProps({
        url: String,
    })
</script>

<template>
    <Link 
        class="bg-blue-500 text-white flex justify-center items-center w-8 h-8 rounded-full"
        :href="url">
        <EditOutlined />
    </Link>
</template>
