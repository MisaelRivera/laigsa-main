<script setup>
    import { Link } from '@inertiajs/vue3';
    import { EyeOutlined } from '@ant-design/icons-vue';

    defineProps({
        url: String,
    });
</script>

<template>
     <Link :href="url" replace>
        <EyeOutlined class="text-blue-400 h-4 w-4"/>
    </Link>
</template>