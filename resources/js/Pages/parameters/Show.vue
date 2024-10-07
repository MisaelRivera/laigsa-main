<script setup>
    import { ref, reactive, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';
    import { useMessages } from '@/composables/messages';
    const { getMessage } = useMessages();
    const props = defineProps({
        parameter: Object,
        backUrl: String,
        lcps: Object,
    });
   const formState = useForm({
        id_parametro: null, 
        valor: null,
   });

   const editState = useForm({
        edit_id_parametro: props.parameter.id,
        edit_valor: null,
   });


   const isOpenEditModal = ref(false);

   const handleCreateLcp = (id) => {
        formState.id_parametro = id;
        formState.post(route('lcps.store', props.parameter));
   };


    const handleOpenEditModal = (lcp) => {
        editState.edit_id_parametro = lcp.id;
        editState.edit_valor = lcp.valor;
        isOpenEditModal.value = true;
    };

    const handleEditLcp = () => {
        editState.put(route('lcps.update', editState.edit_id_parametro), {
            onSuccess : () => {
                isOpenEditModal.value = false;
            }
        });
        
    };
</script>
<template>
    <AuthenticatedLayout>
        <div 
            class="mx-auto rounded-lg p-4 w-8/12 bg-sky-100 border-slate-700">
            <ShowTitle 
                title="Detalles del parametro"
                :back-url="backUrl"/>
        </div>
    </AuthenticatedLayout>
</template>