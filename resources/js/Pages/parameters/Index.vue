<script setup>
    import { ref, reactive } from 'vue';
    import { Link, useForm, router } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { Notivue, Notification, push } from 'notivue';

    const props = defineProps({
        parametersProp: Object,
        filters: Object
    }); 
    const isDeleteModalVisible = ref(false);
    const deleteParameter = useForm({
        id: null,
        parametro: null
    });
    const { getMessage } = useMessages();

    if (getMessage()) push.success(getMessage());

    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.visit(route('parameters.index', { parameter: encodeURIComponent(value) }), {
            preserveState: true,
            method: 'get'
        });
    };

    const handleOpenDeleteModal = (parameter) => {
        isDeleteModalVisible.value = true;
        deleteParameter.id = parameter.id;
        deleteParameter.parametro = parameter.parametro;
    };

    const handleDelete = () => {
        deleteParameter.delete(`/parameters/${deleteParameter.id}`, {
            onSuccess: async() => {
                deleteParameter.value = null;
                isDeleteModalVisible.value = false;
                push.success(getMessage());
            }
        });
    };

    const handleCloseDeleteModal = () => {
        deleteParameter.value = null;
        isDeleteModalVisible.value = false;
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <div class="flex justify-between items-center">
                <Link :href="route('parameters.test')">Test</Link>
                <IndexTitle 
                    title="Parametros"
                    :add-link="route('parameters.create')"
                    :own-link="route('parameters.index')"/>
                
                <Pagination 
                    :links="parametersProp.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded border px-3"
                            v-model="filters.parameter"
                            @input="handleFilter">
                    </div>
                </div>
            </div>
            <table class="borde w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="py-2.5 px-5 border text-left w-10/12">Parametro</th>
                        <th class="py-2.5 px-5 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="parameter in parametersProp.data"
                        class="bg-slate-50">
                        <td class="py-2.5 px-5 border">
                            {{ parameter.parametro }}
                        </td>
                        <td class="py-2.5 px-5 border text-center">
                            <Link :href="route('parameters.edit', parameter.id)">
                                <i class="fas fa-edit text-white p-2 text-xs rounded-full mr-2 bg-blue-500"/>
                            </Link>
                            <Link :href="route('parameters.show', parameter.id)">
                                <i class="fas fa-eye text-white p-2 text-xs rounded-full mr-2 bg-sky-500"/>
                            </Link>
                            <i 
                                class="fas fa-trash text-white p-2 text-xs rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(parameter)"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <MyModal
            title="Eliminar parametro"
            v-model="isDeleteModalVisible"
            @close-from="handleCloseDeleteModal"
            @ok="handleDelete"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-blue-500', 'text-white']
            }">
            <p>Seguro que deseas eliminar el parametro {{ deleteParameter.parametro }}</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification :item="item" />
        </Notivue>
    </AuthenticatedLayout>
</template>