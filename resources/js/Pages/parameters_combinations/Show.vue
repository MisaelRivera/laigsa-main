<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Notivue, Notification, push } from 'notivue';
    const props = defineProps({
        parameterCombination: Object,
        analists: Array,
        parameterCombinationAnalists: Array
    });

    const isAddAnalystModalVisible = ref(false);
    const isDeleteAnalystModalVisible = ref(false);
    const isAddSupervisorModalVisible = ref(false);
    const isDeleteSupervisorModalVisible = ref(false);

    const deleteAnalyst = ref(null);
    const deleteSupervisor = ref(null);

    const handleOpenAddAnalystModal = () => {
        isAddAnalystModalVisible.value = true;
    };

    const handleOpenAddSupervisorModal = () => {
        isAddSupervisorModalVisible.value = true;
    };

    const handleOpenDeleteAnalystModal = (analyst) => {
        isDeleteAnalystModalVisible.value = true;
        deleteAnalyst.value = analyst;
    };

    const handleOpenDeleteSupervisorModal = (supervisor) => {
        isDeleteSupervisorModalVisible.value = true;
        deleteSupervisor.value = supervisor;
    };

    const handleCloseAddAnalystModal = () => {
        isAddAnalystModalVisible.value = false;
    };

    const handleCloseAddSupervisorModal = () => {
        isAddSupervisorModalVisible.value = false;
    };

    const handleCloseDeleteAnalystModal = () => {
        isDeleteAnalystModalVisible.value = false;
        deleteAnalyst.value = null;
    };

    const handleCloseDeleteSupervisorModal = () => {
        isDeleteSupervisorModalVisible.value = false;
        deleteSupervisor.value = null;
    };

    const handleDeleteAnalyst = (paramCombinationAnalyst) => {
        router.delete(`/parameters-combinations/${paramCombinationAnalyst.id}/${props.parameterCombination.id}/remove-param-combination-analyst`, {
            onSuccess: () => {
                push.success(`Se ha eliminado al analista ${paramCombinationAnalyst.name} correctamenete del parametro ${props.parameterCombination.parametro.parametro}`);
                isDeleteAnalystModalVisible.value = false;
                deleteAnalyst.value = null;
            }
        });
    };

    const handleDeleteSupervisor = (paramCombinationSupervisor) => {
        router.delete(`/parameters-combinations/${paramCombinationSupervisor.id}/${props.parameterCombination.id}/remove-param-combination-supervisor`, {
            onSuccess: () => {
                push.success(`Se ha eliminado al supervisor ${paramCombinationSupervisor.name} correctamenete del parametro ${props.parameterCombination.parametro.parametro}`);
                isDeleteSupervisorModalVisible.value = false;
                deleteSupervisor.value = null;
            }
        });
    };

    const handleAddAnalyst = (form$) => {
        router.post(`/parameters-combinations-add-analyst/${form$.requestData.analista}/${props.parameterCombination.id}`, {}, {
            onSuccess: () => {
                isAddAnalystModalVisible.value = false;
            }
        });
    };

    const handleAddSupervisor = (form$) => {
        router.post(`/parameters-combinations-add-supervisor/${form$.requestData.supervisor}/${props.parameterCombination.id}`, {}, {
            onSuccess: () => {
                isAddSupervisorModalVisible.value = false;
            }
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto mt-7 bg-slate-50 border-2 p-4">
            <div class="grid grid-cols-12">
                <p class="col-span-2 font-bold">Pametro</p>
                <p class="col-span-2 font-bold">Metodo</p>
                <p class="col-span-1 font-bold">Unidad</p>
                <p class="col-span-1 font-bold">LCP</p>
                <p class="col-span-2 font-bold">Alias</p>
                <p class="col-span-1 font-bold">Categoria</p>
                <p class="col-span-1 font-bold">Abreviacion</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2">{{ parameterCombination.parametro.parametro }}</p>
                <p class="col-span-2">{{ parameterCombination.metodo.nombre }}</p>
                <p class="col-span-1">{{ parameterCombination.unidad.nombre }}</p>
                <p class="col-span-1">{{ parameterCombination.lcp.valor }}</p>
                <p class="col-span-2">{{ parameterCombination.alias }}</p>
                <p class="col-span-1">{{ parameterCombination.clasificacion }}</p>
                <p class="col-span-1">{{ parameterCombination.abreviacion }}</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2 font-bold">Orden de acomodo</p>
                <p class="col-span-2 font-bold">Subcontratado</p>
                <p class="col-span-1 font-bold">Compuesto</p>
                <p class="col-span-1 font-bold">Ema</p>
                <p class="col-span-1 font-bold">CNA</p>
                <p class="col-span-1 font-bold">SSA</p>
                <p class="col-span-1 font-bold">Supervisar</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2">{{ parameterCombination.arrange }}</p>
                <p class="col-span-2">{{ parameterCombination.subcontratado ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.compuesto ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.ema ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.cna ? 'Si':'No'}}</p>
                <p class="col-span-1">{{ parameterCombination.ssa ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.supervisar ? 'Si':'No' }}</p>
            </div>
            <div class="grid grid-cols-12 gap-4">
                <table class="border bg-slate-100 col-span-6 mt-10">
                    <thead>
                        <tr>
                            <th class="border py-1 px-2">
                                Analistas
                                <i 
                                    class="fas fa-plus text-white bg-green-500 p-2 rounded-full cursor-pointer"
                                    @click="handleOpenAddAnalystModal"></i>
                             </th>
                            <th class="border py-1 px-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="analista in parameterCombination.analistas">
                            <td class="border py-1 px-2">
                                {{ analista.name }}
                            </td>
                            <td class="border py-1 px-2">
                                <i class="fas fa-trash delete-button" @click="() => handleOpenDeleteAnalystModal(analista)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="border bg-slate-100 col-span-6 mt-10">
                    <thead>
                        <tr>
                            <th class="border py-1 px-2">
                                Supervisores
                                <i 
                                    class="fas fa-plus text-white bg-green-500 p-2 rounded-full cursor-pointer"
                                    @click="handleOpenAddSupervisorModal"></i>
                             </th>
                            <th class="border py-1 px-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="supervisor in parameterCombination.supervisores">
                            <td class="border py-1 px-2">
                                {{ supervisor.name }}
                            </td>
                            <td class="border py-1 px-2">
                                <i class="fas fa-trash delete-button" @click="() => handleOpenDeleteSupervisorModal(supervisor)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <MyModal
            title="Agregar analista"
            :model-value="isAddAnalystModalVisible"
            :ok-button-props="{
                class: ['hidden']
            }"
            :cancel-button-props="{
                class: ['bg-gray-500', 'text-white']
            }"
            @close-from="handleCloseAddAnalystModal">
            <Vueform
                @submit="handleAddAnalyst"
                :endpoint="false">
                <SelectElement 
                    name="analista"
                    :items="analists"
                    :native="false"/>
                <button class="bg-blue-500 text-white rounded px-2 py-1 col-span-2">Agregar</button>
            </Vueform>
        </MyModal>
        <MyModal
            title="Agregar supervisor"
            :model-value="isAddSupervisorModalVisible"
            :ok-button-props="{
                class: ['hidden']
            }"
            :cancel-button-props="{
                class: ['bg-gray-500', 'text-white']
            }"
            @close-from="handleCloseAddSupervisorModal">
            <Vueform
                @submit="handleAddSupervisor"
                :endpoint="false">
                <SelectElement 
                    name="supervisor"
                    :items="analists"
                    :native="false"/>
                <button class="bg-blue-500 text-white rounded px-2 py-1 col-span-2">Agregar</button>
            </Vueform>
        </MyModal>
        <MyModal
            title="Eliminar analista"
            :model-value="isDeleteAnalystModalVisible"
            @ok="() => handleDeleteAnalyst(deleteAnalyst)"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-gray-500', 'text-white']
            }"
            @close-from="handleCloseDeleteAnalystModal">
            <p>Estas seguro de que deseas eliminar al analista {{ deleteAnalyst.name }}?</p>
        </MyModal>
        <MyModal
            title="Eliminar supervisor"
            :model-value="isDeleteSupervisorModalVisible"
            @ok="() => handleDeleteSupervisor(deleteSupervisor)"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-gray-500', 'text-white']
            }"
            @close-from="handleCloseDeleteSupervisorModal">
            <p>Estas seguro de que deseas eliminar al supervisor {{ deleteSupervisor.name }}?</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>