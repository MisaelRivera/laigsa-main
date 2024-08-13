<script setup>
    import { ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    const page = usePage();
    const props = defineProps({
        title: String,
        backgroundUrl: {
            type: String,
            default: '',
        }
    });
    const openModal = ref(false);
    console.log(page.props);

    const submitHandler = (credentials) => {
        console.log(credentials);
    };
</script>
<template>
    <GuestLayout>
    </GuestLayout>
</template>
<style>
    .indexBody {
        background-image: url('../img/background.jpg');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        background-attachment: fixed;
        height: 100%;
    }
</style>