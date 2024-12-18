<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        client: Object
    });


</script>
<template>
    <div class="w-8/12 mx-auto">
        <Vueform
            :endpoint="false"
            @submit=""
            :columns="{container:12, wrapper:12}">

        </Vueform> 
    </div>
</template>