<script setup>
    import { ref } from 'vue';
    import { useForm, Link, router } from '@inertiajs/vue3';
    import { Notification, Notivue, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
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

    if (getMessage()) {
        push.success(getMessage());
    }

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
                push.success(`Se ha eliminado correctamente un metodo`);
            }
        });
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
                        :key="method.id"
                        class="bg-slate-50">
                        <td class="border py-2 px-4">{{ method.nombre }}</td>
                        <td class="border py-2 px-4 text-center">
                            <Link 
                                :href="route('methods.edit', method)">
                                <i 
                                    class="bg-blue-500 p-1 text-white rounded-full mr-2 fas fa-edit text-xs"></i>
                            </Link>
                            <Link 
                                :href="route('methods.show', method)">
                                <i 
                                    class="bg-sky-500 p-1 text-white rounded-full fas fa-eye text-xs"></i>
                            </Link>
                            <i 
                                class="bg-red-500 p-1 text-white rounded-full ml-2 fas fa-trash text-xs"
                                @click="() => handleShowDeleteModal(method.id_metodo, method.nombre)" ></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
        <MyModal
            v-model="isDeleteModalVisible"
            @ok="handleDeleteMethod"
            :title="`Eliminar metodo`">
            <p>{{ `Seguro que deseas eliminar el metodo ${deleteMethod.nombre}?` }}</p>
        </MyModal>
    </AuthenticatedLayout>
</template>