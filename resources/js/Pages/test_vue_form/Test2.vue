<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    
    const props = defineProps({
        parameters: Object
    });
    console.log(props.parameters);
    const isDeleteOpen = ref(false);
    const deleteParam = useForm({
        id: null,
        parametro: null
    });

    const handleCloseDelete = () => {
        isDeleteOpen.value = false;
        deleteParam.id = null;
        deleteParam.parametro = null;
    };

    const handleDelete = () => {
        deleteParam.delete(`/test2/${deleteParam.id}`);
    };

    const formState = useForm({
        name: ''
    });

    const schema = ref({
        name: {
            type: 'text',
            before: {
                content: '<div class="text-sm">Name</div>'
            },
            description: {
                content: formState.errors.name ? `<p class="text-red-400">${formState.errors.name}</p>`:''
            },

            placeholder: 'Your name',
            columns: {container: 6, wrapper: 12},
        },
        
        btn: {
            type: 'button',
            'button-label': 'Crear',
            submits: true,
        }
    });

    const handleSubmit = (form$) => {
        formState.name = form$.requestData;
        console.log(formState.name);
        //formState.post('/vue-form-tests/test2');
    };

</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto">
            <Vueform
                :schema="schema"
                @submit="handleSubmit"
                :endpoint="false"/>
        </div>
        <MyModal 
            v-model="isDeleteOpen"
            title="Eliminar parametro"
            :ok-button-props="{
                class: ['bg-green-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            @close-from="handleCloseDelete"
            @ok="handleDelete">
            <p>Estas seguro que deseas eliminar el parametro {{ deleteParam.parametro }}</p>
        </MyModal>
    </AuthenticatedLayout>
</template>