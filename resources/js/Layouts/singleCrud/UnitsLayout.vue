<script setup>
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parametersProp: Object
    }); 
    const { getMessage } = useMessages();
    const parameters = props.parametersProp;
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto">
            <a-alert
                type="success"
                v-if="getMessage()"
                :message="getMessage()">
            </a-alert>
        </div>
    </AuthenticatedLayout>
</template>