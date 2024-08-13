<script setup>
    const props = defineProps({
        title: String,
    });
    import GuestLayout from '@/Layouts/GuestLayout.vue';
</script>
<template>
    <GuestLayout>
        <h1 class="">{{ title }}</h1>
    </GuestLayout>
</template>