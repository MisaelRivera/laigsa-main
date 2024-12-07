<script setup>
    import { ref, nextTick } from 'vue';
    import { useForm, Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';
    import { useMessages } from '@/composables/messages';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { Notivue, Notification, push } from 'notivue';
    const { getMessage } = useMessages();
    const props = defineProps({
        parameter: Object,
        backUrl: String,
        lcps: Object,
        errors: Object
    });
    const form$ = ref(null)
   const formState = useForm({
        id_parametro: props.parameter.id, 
        valor_crear: null,
   });

   const editState = useForm({
        id_parametro: props.parameter.id,
        valor: null,
   });

   const isOpenEditModal = ref(false);

   const isOpenDeleteModal = ref(false);

   const deleteLCP = ref(null);

   const handleCreateLcp = (form$, FormData) => {
        formState.valor_crear = form$.requestData.lcp;
        formState.post(route('lcps.store', props.parameter), {
            onSuccess: () => {
                push.success("Se ha agregado el lcp correctamente");
            }
        });
   };

    const handleOpenEditModal = (lcp) => {
        isOpenEditModal.value = true;
        nextTick(() => {
            form$.value.update({ valor: lcp.valor, id: lcp.id })
        });
    };

    const handleEditLcp = () => {
        editState.valor = form$.value.data.valor;
        editState.put(route('lcps.update', form$.value.data.id), {
            onSuccess : () => {
                isOpenEditModal.value = false;
                push.success("Se ha editado el lcp correctamente");
            }
        });
    };

    const handleCloseEditModal = () => {
        isOpenEditModal.value = false;
    };

    const handleOpenDeleteModal = (lcp) => {
        deleteLCP.value = lcp;
        isOpenDeleteModal.value = true;
    };

    const handleCloseDeleteModal = () => {
        isOpenDeleteModal.value = false;
        deleteLCP.value = null;
    };

    const handleDeleteLcp = () => {
        router.delete(`/lcps/${props.parameter.id}/${deleteLCP.value.id}`, {
            onSuccess: () => {
                isOpenDeleteModal.value = false;
                push.success(`Se ha removido el ${deleteLCP.value.valor} correctamente`);
                deleteLCP.value = null;
            }
        });
    };

    if (getMessage()) push.success(getMessage());
</script>
<template>
    <AuthenticatedLayout>
        <div 
            class="mx-auto rounded-lg p-4 w-8/12 bg-sky-100 border-slate-700">
            <ShowTitle 
                :title="`Detalles del parametro ${parameter.parametro}`"
                :back-url="backUrl"/>
           <Vueform
                :endpoint="false"
                @submit="handleCreateLcp"
                :scroll-to-invalid="false"
                :display-errors="false"
                :columns="{ container:12, wrapper:12 }">
                <TextElement 
                    name="lcp"
                    before="LCP"
                    :description="errors.valor_crear ? `<p class='text-red-500'>${errors.valor_crear}</p>`:null"/>
                <button class="py-1 px-2 rounded-md text-white bg-green-500">Agregar</button>
           </Vueform>
           <div class="w-full px-4 py-3 rounded-lg bg-teal-500 mt-2 grid grid-cols-3">
                <p><b>Parametro:</b> {{ parameter.parametro }}</p>
                <p><b>Parametro:</b> {{ parameter.parametro }}</p>
                <p><b>Parametro:</b> {{ parameter.parametro }}</p>
           </div>
           <table class="border w-full">
                <thead>
                    <tr class="bg-sky-600 text-white">
                        <th class="py-1 px-2 border">Lcp</th>
                        <th class="py-1 px-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="(lcp, index) in lcps"
                        :key="index">
                        <td class="py-1 px-2 border">{{ lcp.valor }}</td>
                        <td class="py-1 px-2 border text-center">
                            <i 
                                class="fas fa-edit cursor-pointer p-2 text-xs text-white bg-blue-500 rounded-full"
                                @click="() => handleOpenEditModal(lcp)"></i>
                            <i 
                                class="fas fa-trash p-2 rounded-full cursor-pointer text-white bg-red-500 ml-2"
                                @click="() => handleOpenDeleteModal(lcp)"></i>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div>
        <MyModal
            title="Editar lcp"
            v-model="isOpenEditModal"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-slate-500', 'text-white']
            }"
            @close-from="handleCloseEditModal"
            @ok="handleEditLcp">
            <Vueform
                :endpoint="false"
                @submit="handleEditLcp"
                :columns="{ container:12, wrapper:12 }"
                ref="form$">
                <TextElement
                    name="valor"
                    before="LCP"
                    :columns="{ container: 12, wrapper:12 }"
                    :description="errors.valor ? `<p class='text-red-500'>${errors.valor}</p>`:null"/>
                <HiddenElement 
                    name="id"/>
            </Vueform>
        </MyModal>
        <MyModal
            v-model="isOpenDeleteModal"
            title="Remover LCP"
            @close-from="handleCloseDeleteModal"
            @ok="handleDeleteLcp"
            :cancel-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :ok-button-props="{
                class: ['bg-red-500', 'text-white']
            }">
            <p>Seguro que desea remover el lcp {{ deleteLCP.valor }} del parametro {{ parameter.parametro }}</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>