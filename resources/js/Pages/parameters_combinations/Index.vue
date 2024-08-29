<script setup>
     import { ref } from 'vue';
    import { router, useForm, Link } from '@inertiajs/vue3';
    import { DeleteOutlined, EditOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';

    const props = defineProps({
        parametersCombinations: {
            type: Object,
        },
        filters: Object
    });
    const isDeleteModalVisible = ref(false);
    const deleteParameterCombination = useForm({
        id: null,
        alias: null
    });


    const { getMessage } = useMessages();
    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.visit(route('parameters-combinations.index', { byAlias: value }), {
            preserveState: true,
            method: 'get'
        });
    };

    const handleOpenDeleteModal = (parameterCombination) => {
        isDeleteModalVisible.value = true;
        deleteParameterCombination.id = parameterCombination.id;
        deleteParameterCombination.alias = parameterCombination.alias;
    };

    const handleDelete = () => {
        deleteParameterCombination.delete(`/parameters-combinations/${deleteParameterCombination.id}?page=${props.parametersCombinations.current_page}`, {
            onSuccess: async() => {
                isDeleteModalVisible.value = false;
            }
        });
    };
    console.log(props.parametersCombinations);
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
                    title="Combinaciones de parametros"
                    :add-link="route('parameters-combinations.create', { page: parametersCombinations ?  parametersCombinations.current_page:'' })"
                    :own-link="route('parameters-combinations.index')"/>
                
                <Pagination 
                    v-if="parametersCombinations"
                    :links="parametersCombinations.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded"
                            v-model="filters.byAlias"
                            @input="handleFilter">
                    </div>
                </div>
            </div>
            <table class="borde w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="py-2.5 px-5 border text-left">Parametro</th>
                        <th class="py-2.5 px-5 border text-left">LCP</th>
                        <th class="py-2.5 px-5 border text-left w-64">Alias</th>
                        <th class="py-2.5 px-5 border w-36">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="parameterCombination in parametersCombinations.data"
                        class="bg-slate-50">
                        <td class="py-2.5 px-5 border">
                            {{ parameterCombination.parametro.parametro }}
                        </td>
                        <td class="py-2.5 px-5 border">
                            {{ parameterCombination.lcp.valor }}
                        </td>
                        <td class="py-2.5 px-5 border">
                            {{ parameterCombination.alias }}
                        </td>
                        <td class="py-2.5 px-5 border text-center">
                            <Link :href="`/parameters-combinations/${parameterCombination.id}/edit?page=${parametersCombinations.current_page}`">
                                <EditOutlined class="text-white p-1 rounded-full mr-2 bg-blue-500"/>
                            </Link>
                            <Link :href="route('parameters-combinations.show', { id: parameterCombination.id, page: parametersCombinations.current_page})">
                                <EyeOutlined class="text-white p-1 rounded-full mr-2 bg-sky-500"/>
                            </Link>
                            <DeleteOutlined 
                                class="text-white p-1 rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(parameterCombination)"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a-modal
            v-model:open="isDeleteModalVisible"
            title="Eliminar combinacion de parametro"
            @ok="handleDelete">
            <p>Seguro que deseas borrar la combinacion de parametro {{ deleteParameterCombination.alias }}?</p>
        </a-modal>
    </AuthenticatedLayout>
</template>