<script setup>
    import { ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import { usePage, Link, useForm } from '@inertiajs/vue3';
    import { Alert, Modal } from 'ant-design-vue';
    import UnitsLayout from '@/Layouts/singleCrud/UnitsLayout.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        unitsProp: Object,
        totalItemsProp: {
            type: Number
        }
    });
    const units = ref(props.unitsProp);
    const pageUse = usePage();
    const totalItems = ref(props.totalItemsProp);
    const page = ref(1);
    const pages = ref(10);
    const items = ref(40);
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
    const handleDelete = () => {
        deleteUnit.delete(route('units.destroy', deleteUnit.id), {
            onSuccess: async() => {
                deleteModalOpen.value = false;
                const data = await axios.get('/units/get-units');
                units.value = data.data;
            }
        });
    };

    const handleChangePage = async (pageArg) => {
        page.value = pageArg;
        let unitsResults = await axios.get('/units/change-page?page=' + pageArg);
        console.log(unitsResults);
        units.value = unitsResults.data;
        console.log(units.value);
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
        <UnitsLayout>
            <Alert
                type="success"
                v-if="pageUse.props.flash.message"
                :message="pageUse.props.flash.message"
                closable
                class="mb-3"/>
            <div>
                <div class="flex justify-between">
                    <IndexTitle 
                        title="Unidades"
                        add-link="/units/create"
                        own-link="/units"/>
                    <Pagination
                        :total-items="totalItems"
                        :items-per-page-prop="items"
                        :pages-per-chunk-prop="pages"
                        :current-page-prop="page"
                        @change-page="handleChangePage" />
                    <div></div>
                </div>
            </div>
            <table class="border">
                <thead>
                    <tr>
                        <th class="border text-left">
                            <label for="unit">Unidad</label>
                            <input 
                                type="text"
                                class="py-1 px-4 rounded border ml-2 focus:outline-none focus:ring focus:ring-aqua-400"
                                id="unit"
                                placeholder="Filtrar"
                                @input="(ev) => handleFilterByUnit(ev, 'nombre')">
                        </th>
                        <th class="border"></th>
                        <th class="border"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="unit in units">
                        <td class="py-2 px-2 border">{{ unit.nombre }}</td>
                        <td class="py-2 px-2 border">
                            <Link 
                                class="w-10 h-10 rounded-full py-2 px-2 bg-blue-500 text-white text-xs"
                                :href="`/units/edit/${unit.id}`">
                                Edit
                            </Link>
                        </td>
                        <td class="py-2 px-2 border">
                            <button 
                                class="rounded-full h-10 w-10 text-white text-center bg-red-400 text-xs"
                                @click="() => handleOpenModal(unit.id, unit.nombre)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>    
        </UnitsLayout>
        <Modal 
            v-model:open="deleteModalOpen"
            title="Eliminar unidad"
            @ok="() => handleDelete(deleteUnit.id)">
                <p>Seguro que deseas borrar la unidad {{ deleteUnit.name }}?</p>
        </Modal>
    </AuthenticatedLayout>
</template>
