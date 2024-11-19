<script setup>
    import { ref } from 'vue';
    import { Link, useForm } from '@inertiajs/vue3';
    import { Notification, Notivue, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        units: Object,
        filters: Object
    });
    const { getMessage } = useMessages();
    if (getMessage()) {
        push.success(getMessage());
    }

    const deleteModalOpen = ref(false);
    const deleteUnit = useForm({
        id: null,
        nombre: null,
    });

    const handleOpenDeleteModal = (unit) => {
        deleteUnit.id = unit.id;
        deleteUnit.nombre = unit.nombre;
        deleteModalOpen.value = true;
    };

    const handleCloseDeleteModal = () => {
        deleteUnit.id = null;
        deleteUnit.nombre = null;
        deleteModalOpen.value = false;
    };

    const handleDelete = (unit) => {
        deleteUnit.delete(`/units/${unit.id}`, {
            onSuccess: async() => {
                deleteModalOpen.value = false;
                push.success(`Se ha eleminado la unidad ${unit.nombre} correctamente`);
            }
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Unidades"
                    :add-link="route('units.create')"
                    :own-link="route('units.index')"/>
                
                <Pagination 
                    :links="units.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded"
                            v-model="filters.byUnit"
                            @input="handleFilter">
                    </div>
                </div>
            </div>
            <table class="border w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="py-2.5 px-5 border text-left w-10/12">
                            Unidad
                        </th>
                        <th class="py-2.5 px-5 border">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="unit in units.data"
                        class="bg-slate-50">
                        <td class="py-2 px-2 border">{{ unit.nombre }}</td>
                        <td class="py-2 px-2 border text-center">
                            <Link 
                                :href="`/units/edit/${unit.id}`">
                                <i class="bg-blue-500 text-white p-1 rounded-full text-xs mr-2 fas fa-edit"></i>
                            </Link>
                            <Link 
                                :href="`/units/edit/${unit.id}`">
                                <i class="bg-sky-500 text-white p-1 rounded-full text-xs mr-2 fas fa-eye"></i>
                            </Link>
                            <i 
                                class="bg-red-500 text-white p-1 rounded-full text-xs mr-2 fas fa-trash"
                                @click="() => handleOpenDeleteModal(unit)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Notivue v-slot="item">
                <Notification :item="item"/>
            </Notivue>
            <MyModal 
                v-model="deleteModalOpen"
                title="Eliminar unidad"
                @ok="() => handleDelete(deleteUnit)"
                @close-from="handleCloseDeleteModal"
                :ok-button-props="{
                    class: ['bg-red-500', 'text-white']
                }"
                :cancel-button-props="{
                    class: ['bg-blue-500', 'text-white']
                }">
                    <p>Seguro que deseas borrar la unidad {{ deleteUnit.nombre }}?</p>
            </MyModal>
        </div>
    </AuthenticatedLayout>
</template>
