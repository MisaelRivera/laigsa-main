<script setup>
    import { ref } from 'vue';
    import { router, useForm, Link } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { Notivue, Notification, push } from 'notivue';
    import IndexFilter from '@/Components/Shared/IndexFilter.vue';
    const props = defineProps({
        rules: {
            type: Object,
        },
        filters: Object
    });
    const isDeleteModalVisible = ref(false);
    const deleteRule = useForm({
        id: null,
        norma: null
    });
    const { getMessage } = useMessages();
    const handleFilter = (ev) => {
        const value = ev.target.value;
        console.log(value);
        router.visit(route('rules.index', { byRule: value }), {
            preserveState: true,
            method: 'get'
        });
    };

    const handleOpenDeleteModal = (rule) => {
        isDeleteModalVisible.value = true;
        deleteRule.id = rule.id;
        deleteRule.norma = rule.norma;
    };

    const handleDelete = () => {
        deleteRule.delete(`/rules/${deleteRule.id}?page=${props.rules.current_page}`, {
            onSuccess: async() => {
                push.success(`Se ha eliminado la norma ${deleteRule.norma} correctamente`);
                isDeleteModalVisible.value = false;
            }
        });
    };

    const handleCloseDeleteModal = () => {
        deleteRule.id = null;
        deleteRule.norma = null;
        isDeleteModalVisible.value = false;
    };
    if (getMessage()) push.success(getMessage());
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Normas"
                    :add-link="route('rules.create', { page: rules.current_page })"
                    :own-link="route('rules.index')"/>
                
                <Pagination 
                    :links="rules.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded"
                            v-model="filters.byRule"
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
                        v-for="rule in rules.data"
                        class="bg-slate-50">
                        <td class="py-2.5 px-5 border">
                            {{ rule.norma }}
                        </td>
                        <td class="py-2.5 px-5 border text-center">
                            <Link :href="`/rules/${rule.id}/edit?page=${rules.current_page}`">
                                <i class="fas fa-edit text-white p-1 text-xs rounded-full mr-2 bg-blue-500"></i>
                            </Link>
                            <Link :href="`/rules/${rule.id}?page=${rules.current_page}`">
                                <i class="fas fa-eye text-blue-500 mr-2"></i>
                            </Link>
                            <i 
                                class="fas fa-trash text-white p-1 text-xs rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(rule)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <MyModal
            title="Eliminar norma"
            v-model="isDeleteModalVisible"
            @close-from="handleCloseDeleteModal"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            @ok="handleDelete">
            <p>Seguro que desea eliminar la norma {{ deleteRule.norma }}?</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>