<script setup>
    import { ref, reactive, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { DeleteOutlined, EditOutlined, HistoryOutlined } from '@ant-design/icons-vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';
    import { useMessages } from '@/composables/messages';
    const { getMessage } = useMessages();
    const props = defineProps({
        parameter: Object,
        backUrl: String,
        lcps: Object,
    });
   const formState = useForm({
        id_parametro: null, 
        valor: null,
   });

   const editState = useForm({
        edit_id_parametro: props.parameter.id,
        edit_valor: null,
   });


   const isOpenEditModal = ref(false);

   const handleCreateLcp = (id) => {
        formState.id_parametro = id;
        formState.post(route('lcps.store', props.parameter));
   };


    const handleOpenEditModal = (lcp) => {
        editState.edit_id_parametro = lcp.id;
        editState.edit_valor = lcp.valor;
        isOpenEditModal.value = true;
    };

    const handleEditLcp = () => {
        editState.put(route('lcps.update', editState.edit_id_parametro), {
            onSuccess : () => {
                isOpenEditModal.value = false;
            }
        });
        
    };
</script>
<template>
    <AuthenticatedLayout>
        <a-alert
            type="success"
            :message="getMessage()"
            v-if="getMessage()"/>
        <div 
            class="mx-auto rounded-lg p-4 w-8/12 bg-sky-200 border-slate-700">
            <ShowTitle 
                title="Detalles del parametro"
                :back-url="backUrl"/>
            <h2 class="ml-6">Parametro {{ parameter.parametro }}</h2>
            <a-row>
                <a-col 
                    :offset="6" 
                    :span="12">
                    <a-form 
                        :model="formState"
                        layout="vertical"
                        @finish="() => handleCreateLcp(parameter.id)"
                        class="bg-teal-300 p-3 rounded">
                        <a-form-item
                            label="Valor"
                            name="valor"
                            :rules="[{ required: true, message: 'Ingrese el valor' }]">
                            <a-input
                                v-model:value="formState.valor"
                                class="h-8 rounded-md"/>
                        </a-form-item>
                        <p class="text-red-400" v-if="formState.errors.id_parametro">{{ formState.errors.id_parametro }}</p>
                        <p class="text-red-400" v-if="formState.errors.valor">{{ formState.errors.valor }}</p>
                        <button class="bg-green-400 text-white rounded-md py-2 px-4">
                            Crear
                        </button>
                    </a-form>
                    <table
                        class="border border-slate-700 w-full mt-4">
                        <thead>
                            <tr class="bg-slate-500">
                                <th class="py-2 px-4 border border-slate-700 text-left text-white">Valor</th>
                                <th class="py-2 px-4 border border-slate-700 text-left text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                class="bg-slate-50"
                                v-for="lcp in lcps">
                                <td class="py-2 px-4 border border-slate-700">
                                    {{ lcp.valor }}
                                </td>
                                <td class="py-2 px-4 border border-slate-700">
                                    <EditOutlined 
                                        class="bg-blue-500 text-white p-1 rounded-full"
                                        @click="() => handleOpenEditModal(lcp)"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </a-col>
            </a-row>
        </div>
        <a-modal
            title="Editar lcp"
            v-model:open="isOpenEditModal"
            :ok-button-props="{hidden: true}">
            <a-form
                :model="editState"
                layout="vertical"
                @finish="handleEditLcp">
                <a-form-item
                    label="Valor"
                    name="edit_valor"
                    >
                    <a-input
                        v-model:value="editState.edit_valor"/>
                    <p 
                        class="text-red-500"
                        v-if="editState.errors.edit_valor">
                        {{ editState.errors.edit_valor }}
                    </p>
                </a-form-item>
                <button class="btn btn-primary"> Editar</button>
            </a-form> 
        </a-modal>
    </AuthenticatedLayout>
</template>