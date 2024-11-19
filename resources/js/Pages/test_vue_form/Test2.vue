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

    const handleOpenDelete = (param) => {
        isDeleteOpen.value = true;
        deleteParam.id = param.id;
        deleteParam.parametro = param.parametro;
    };

    const handleDelete = () => {
        deleteParam.delete(`/test2/${deleteParam.id}`);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto">
            <h1>Crear parametro</h1>
            <table class="border">
                <thead>
                    <tr>
                        <th class="border py-1 px-2">Parametro</th>
                        <th class="border py-1 px-2">Metodo</th>
                        <th class="border py-1 px-2">Abreviacion</th>
                        <th class="border py-1 px-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(param, index) in parameters.data" :key="index">
                        <td class="border py-1 px-2">{{ param.parametro }}</td>
                        <td class="border py-1 px-2">{{ param.metodo }}</td>
                        <td class="border py-1 px-2">{{ param.abreviacion }}</td>
                        <td class="border py-1 px-2">
                            <i 
                                class="fas fa-trash bg-red-500 text-white py-2 px-2 rounded-full"
                                @click="handleOpenDelete"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
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