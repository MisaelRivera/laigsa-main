<script setup>
     import { ref } from 'vue';
    import { router, useForm, Link } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import { Notivue, Notification, push } from 'notivue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import IndexFilter from '@/Components/Shared/IndexFilter.vue';

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
    
    if (getMessage()) {
        push.success(getMessage());
    }

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
                push.success(`Se ha eliminado la combinacion ${deleteParameterCombination.alias} correctamente`);
                deleteParameterCombination.id = null;
                deleteParameterCombination.alias = null;
            }
        });
    };

    const handleCloseDeleteModal = (parameterCombination) => {
        isDeleteModalVisible.value = false;
        deleteParameterCombination.id = null;
        deleteParameterCombination.alias = null;
    };

    console.log(props.parametersCombinations);
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
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
                   <IndexFilter 
                    :filters="[
                        {
                            value: 'alias',
                            label: 'Alias'
                        },
                    ]"
                    route="parameters-combinations.index"/>
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
                                <i class="fas fa-edit text-white p-2 text-xs rounded-full mr-2 bg-blue-500"></i>
                            </Link>
                            <Link :href="route('parameters-combinations.show', { id: parameterCombination.id, page: parametersCombinations.current_page})">
                                <i class="fas fa-eye text-blue-500 mr-2"></i>
                            </Link>
                            <i 
                                class="fas fa-trash text-white p-2 text-xs rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(parameterCombination)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Notivue v-slot="item">
            <Notification 
                :item="item"/>
        </Notivue>
        <MyModal
            title="Eliminar Combinacion parametro"
            v-model="isDeleteModalVisible"
            @close-from="handleCloseDeleteModal"
            @ok="handleDelete"
            :cancel-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }">
            <p>
                Esta seguro de que desea eliminar la combinacion parametro {{ deleteParameterCombination.alias }}?
            </p>
        </MyModal>
    </AuthenticatedLayout>
</template>