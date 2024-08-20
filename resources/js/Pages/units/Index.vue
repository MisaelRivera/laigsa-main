<script setup>
    import { ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import { usePage, Link, useForm } from '@inertiajs/vue3';
    import { Alert, Modal } from 'ant-design-vue';
    import { DeleteOutlined, EditOutlined, EyeOutlined } from '@ant-design/icons-vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        units: Object,
        filters: Object
    });
    const pageUse = usePage();
    const deleteModalOpen = ref(false);
    const deleteUnit = useForm({
        id: null,
        name: null,
    });
    const handleOpenModal = (id, name) => {
        deleteUnit.id = id;
        console.log(id);
        console.log(name);
        deleteUnit.name = name;
        deleteModalOpen.value = true;
    }; 
    const handleDelete = (id) => {
        deleteUnit.delete(route('units.destroy', id), {
            onSuccess: async() => {
                deleteModalOpen.value = false;
                const data = await axios.get('/units/get-units');
                units.value = data.data.data;
            }
        });
    };

    const handleChangePage = async (pageArg) => {
        page.value = pageArg;
        let unitsResults = await axios.get('/units/change-page?page=' + pageArg);
        units.value = unitsResults.data;
    };

    const handleFilterByUnit = async(ev, type) => {
        const value = ev.target.value;
        console.log(value);
        let unitsResults = await axios.get(`/units/filter?value=${value}&type=${type}`);
        units.value = unitsResults.data;
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
            <Alert
                type="success"
                v-if="pageUse.props.flash.message"
                :message="pageUse.props.flash.message"
                closable
                class="mb-3"/>
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
                                <EditOutlined 
                                    class="bg-blue-500 text-white p-1 rounded-full mr-2"/>
                            </Link>
                            <Link 
                                :href="`/units/edit/${unit.id}`">
                                <EyeOutlined 
                                    class="bg-sky-500 text-white p-1 rounded-full mr-2"/>
                            </Link>
                            <DeleteOutlined 
                                class="bg-red-500 text-white p-1 rounded-full mr-2"/>
                        </td>
                    </tr>
                </tbody>
            </table>    
            <Modal 
                v-model:open="deleteModalOpen"
                title="Eliminar unidad"
                @ok="() => handleDelete(deleteUnit.id)">
                    <p>Seguro que deseas borrar la unidad {{ deleteUnit.name }}?</p>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
