<script setup>
    import { ref } from 'vue';
    import { useForm, usePage, Link, router } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import { DeleteOutlined, EditOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import { Alert, Col, Row, Form, FormItem, Input, Modal } from 'ant-design-vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const { getMessage } = useMessages();
    const props = defineProps({
        methods: {
            type: Object
        },

        filters: {
            type: Object,
        },
        Errors: Object
    });

    const deleteMethod = useForm({
        id: null,
        nombre: null
    }),
    isDeleteModalVisible = ref(false);

    const handleShowDeleteModal = (id, name) => {
        deleteMethod.id = id;
        deleteMethod.nombre = name;
        isDeleteModalVisible.value = true;
    };

    const handleDeleteMethod = () => {
        deleteMethod.delete(`/methods/${deleteMethod.id}`, {
            onSuccess: () => {
                isDeleteModalVisible.value = false;
            }
        });
    };

    const handleChangePage = async (pageArg) => {
        page.value = pageArg;
        let methodsResults = await axios.get('/methods/change-page?page=' + pageArg);
        methods.value = methodsResults.data;
        console.log(methods.value);
    };

    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.visit(route('methods.index', { byMethod: encodeURIComponent(value) }), {
            preserveState: true,
            method: 'get'
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12">
            <Alert
                type="success"
                :message="getMessage()"
                v-if="getMessage()"/>
            <div class="flex justify-between">
                <IndexTitle 
                    title="Metodos"
                    own-link="/methods"
                    add-link="/methods/create"/>
                <Pagination 
                    :links="methods.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded"
                            v-model="filters.byMethod"
                            @input="handleFilter">
                    </div>
                </div>
            </div>
            <table class="border mt-4 w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border py-2 px-4">Metodo</th>
                        <th class="border py-2 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="method in methods.data"
                        class="bg-slate-50">
                        <td class="border py-2 px-4">{{ method.nombre }}</td>
                        <td class="border py-2 px-4 text-center">
                            <Link 
                                :href="route('methods.edit', method)">
                                <EditOutlined 
                                    class="bg-blue-500 p-1 text-white rounded-full mr-2"/>
                            </Link>
                            <Link 
                                :href="route('methods.show', method)">
                                <EyeOutlined 
                                    class="bg-sky-500 p-1 text-white rounded-full"/>
                            </Link>
                            <DeleteOutlined 
                                class="bg-red-500 p-1 text-white rounded-full ml-2"
                                @click="() => handleShowDeleteModal(method.id_metodo, method.nombre)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Modal
            v-model:open="isDeleteModalVisible"
            @ok="handleDeleteMethod"
            :title="`Eliminar metodo`">
            <p>{{ `Seguro que deseas eliminar el metodo ${deleteMethod.nombre}?` }}</p>
        </Modal>
    </AuthenticatedLayout>
</template>