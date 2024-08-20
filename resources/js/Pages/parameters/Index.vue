<script setup>
    import { ref, reactive } from 'vue';
    import { Link, useForm, router } from '@inertiajs/vue3';
    import { EyeOutlined, DeleteOutlined, EditOutlined } from '@ant-design/icons-vue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';

    const props = defineProps({
        parametersProp: Object,
        filters: Object
    }); 
    const isDeleteModalVisible = ref(false);
    const deleteParameter = useForm({
        id: null,
        parametro: null
    });
    console.log(props.parametersProp.links[0]);
    const { getMessage } = useMessages();
    const handleFilter = (ev) => {
        console.log(props.parametersProp.links);
        const value = ev.target.value;
        router.visit(route('parameters.index', { byParameter: value }), {
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
                isDeleteModalVisible.value = false;
            }
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <a-alert
                type="success"
                v-if="getMessage()"
                :message="getMessage()">
            </a-alert>
            <div class="flex justify-between items-center">
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
                            class="h-8 w-40 rounded"
                            v-model="filters.byParameter"
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
                                <EditOutlined class="text-white p-1 rounded-full mr-2 bg-blue-500"/>
                            </Link>
                            <Link :href="route('parameters.show', parameter.id)">
                                <EyeOutlined class="text-white p-1 rounded-full mr-2 bg-sky-500"/>
                            </Link>
                            <DeleteOutlined 
                                class="text-white p-1 rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(parameter)"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a-modal
            v-model:open="isDeleteModalVisible"
            title="Eliminar parametro"
            @ok="handleDelete">
            <p>Seguro que deseas borrar el parametro {{ deleteParameter.parametro }}?</p>
        </a-modal>
    </AuthenticatedLayout>
</template>