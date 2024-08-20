<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';
    const props = defineProps({
        parameter: Object,
        backUrl: String
    });
   console.log(props.backUrl);
</script>
<template>
    <AuthenticatedLayout>
        <div 
            class="mx-auto rounded-lg p-4 w-8/12 bg-slate-200 border-slate-700">
            <ShowTitle 
                title="Detalles del parametro"
                :back-link="backUrl"/>
        </div>
    </AuthenticatedLayout>
</template>