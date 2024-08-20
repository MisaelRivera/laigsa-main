<script setup>
    import { ref } from 'vue';
    import { useForm, usePage, Link } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import { DeleteOutlined, EditOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import { Alert, Col, Row, Form, FormItem, Input, Modal } from 'ant-design-vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const { getMessage } = useMessages();
    const props = defineProps({
        lcps: {
            type: Object
        },

        filters: {
            type: Object,
        },
        Errors: Object
    });

    const deleteLcp = useForm({
        id: null,
        valor: null
    }),
    isDeleteModalVisible = ref(false);

    const handleShowDeleteModal = (id, name) => {
        deleteLcp.id = id;
        deleteLcp.valor = name;
        isDeleteModalVisible.value = true;
    };

    const handleDeleteLcp = () => {
        deleteLcp.delete(`/lcps/${deleteLcp.id}`, {
            onSuccess: () => {
                isDeleteModalVisible.value = false;
            }
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
                    own-link="/lcps"
                    add-link="/lcps/create"/>
                <Pagination 
                    :links="lcps.links"/>
                <div></div>
            </div>
            <table class="border mt-4 w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border py-2 px-4">LCP</th>
                        <th class="border py-2 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="lcp in lcps.data"
                        class="bg-slate-50">
                        <td class="border py-2 px-4">{{ lcp.nombre }}</td>
                        <td class="border py-2 px-4 text-center">
                            <Link 
                                :href="route('lcps.edit', lcp)">
                                <EditOutlined 
                                    class="bg-blue-500 p-1 text-white rounded-full mr-2"/>
                            </Link>
                            <Link 
                                :href="route('lcps.show', lcp)">
                                <EyeOutlined 
                                    class="bg-sky-500 p-1 text-white rounded-full"/>
                            </Link>
                            <DeleteOutlined 
                                class="bg-red-500 p-1 text-white rounded-full ml-2"
                                @click="() => handleShowDeleteModal(lcp.id_metodo, lcp.nombre)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Modal
            v-model:open="isDeleteModalVisible"
            @ok="handleDeleteMethod"
            :title="`Eliminar metodo`">
            <p>{{ `Seguro que deseas eliminar el metodo ${deleteMethod.valor}?` }}</p>
        </Modal>
    </AuthenticatedLayout>
</template>