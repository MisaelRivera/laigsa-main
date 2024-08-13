<script setup>
    import { ref } from 'vue';
    import { useForm, usePage, Link } from '@inertiajs/vue3';
    import { Alert, Col, Row, Form, FormItem, Input, Modal } from 'ant-design-vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        methodsProp: {
            type: Object
        },

        totalItemsProp: {
            type: Number,
        },
        Errors: Object
    });

    const page = ref(1),
          pages = ref(10),
          totalItems = ref(props.totalItemsProp),
          items = ref(40),
          pageUse = usePage(),
          methods = ref(props.methodsProp),
          deleteMethod = useForm({
            id: null,
            nombre: null
          }),
          isVisibleDeleteModal = ref(false);

    const handleShowDeleteModal = (id, name) => {
        deleteMethod.id = id;
        deleteMethod.name = name;
        isVisibleDeleteModal.value = true;
    };

    const handleDeleteMethod = () => {
        
    };

    const handleChangePage = async (pageArg) => {
        page.value = pageArg;
        let methodsResults = await axios.get('/methods/change-page?page=' + pageArg);
        methods.value = methodsResults.data;
        console.log(methods.value);
    };
</script>
<template>
    <AdminLayout>
        <div class="mx-auto w-8/12">
            <Alert 
                type="success"
                :message="pageUse.props.flash.message"
                v-if="pageUse.props.flash.message"/>
            <div class="flex justify-between">
                <IndexTitle 
                    title="Metodos"
                    own-link="/methods"
                    add-link="/methods/create"/>
                <Pagination 
                    :total-items="totalItems"
                    :items-per-page-prop="items"
                    :pages-per-chunk-prop="pages"
                    :current-page-prop="page"
                    @change-page="handleChangePage"
                    v-if="totalItems > 0"/>
                <div></div>
            </div>
            <table class="border mt-4">
                <thead>
                    <tr>
                        <th class="border py-2 px-4">Metodo</th>
                        <th class="border py-2 px-4"></th>
                        <th class="border py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="method in methods">
                        <td class="border py-2 px-4">{{ method.nombre }}</td>
                        <td class="border py-2 px-4">
                            <Link 
                                :href="`/methods/edit/${method.id_metodo}`"
                                class="bg-blue-500 py-2 px-4 text-xs text-white text-center">
                                Editar
                            </Link>
                        </td>
                        <td class="border py-2 px-4">
                            <button 
                                :href="`/methods/edit/${method.id_metodo}`"
                                class="bg-red-500 py-2 px-4 text-xs text-white text-center"
                                @click="() => handleShowDeleteModal(method.id_metodo, method.nombre)">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Modal
            v-model:open="isVisibleDeleteModal"
            @ok=""
            :title="`Eliminar metodo`">
            <p>{{ `Seguro que deseas eliminar el metodo ${deleteMethod.nombre}?` }}</p>
        </Modal>
    </AdminLayout>
</template>