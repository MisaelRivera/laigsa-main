<script setup>
    import { ref } from 'vue';
    import { router, useForm, Link } from '@inertiajs/vue3';
    import { DeleteOutlined, EditOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
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
                                <EditOutlined class="text-white p-1 rounded-full mr-2 bg-blue-500"/>
                            </Link>
                            <Link :href="route('rules.show', { id: rule.id, page: rules.current_page})">
                                <EyeOutlined class="text-white p-1 rounded-full mr-2 bg-sky-500"/>
                            </Link>
                            <DeleteOutlined 
                                class="text-white p-1 rounded-full mr-2 bg-red-500"
                                @click="() => handleOpenDeleteModal(rule)"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a-modal
            v-model:open="isDeleteModalVisible"
            title="Eliminar norma"
            @ok="handleDelete">
            <p>Seguro que deseas borrar la norma {{ deleteRule.norma }}?</p>
        </a-modal>
    </AuthenticatedLayout>
</template>