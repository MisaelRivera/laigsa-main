<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import { useMessages } from '@/composables/messages';
    import { Notivue, Notification, push } from 'notivue';

    const props = defineProps({
        paramsDescription: Object,
    });

    const { getMessage } = useMessages();

    const deleteParamDescription = ref(null);
    const isDeleteModalVisible = ref(false);

    if (getMessage()) {
        push.success(getMessage());
    }

    const handleOpenDeleteModal = (paramDescription) => {
        isDeleteModalVisible.value = true;
        deleteParamDescription.value = paramDescription;
    };

    const handleCloseDeleteModal = () => {
        isDeleteModalVisible.value = false;
        deleteParamDescription.value = null;
    };

    const handleDeleteParamDescription = () => {
        router.delete(route('params_description.delete', { paramDescription: deleteParamDescription.value.id }), {
            onSuccess: () => {
                push.success(getMessage());
                isDeleteModalVisible.value = false;
                deleteParamDescription.value = null;
            }
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto">
            <IndexTitle 
                title="Descripcion de parametros"
                :own-link="route('params_description.index')"
                :add-link="route('params_description.create')"/>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border py-2 px-4 w-8/12">Descripcion</th>
                        <th class="border py-2 px-2">Tipo</th>
                        <th class="border py-2 px-2">Cesavedac</th>
                        <th class="border py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="paramDescription in paramsDescription.data">
                        <td class="border py-2 px-4">
                            {{ paramDescription.descripcion }}
                        </td>
                        <td class="border py-2 px-2">
                            {{ paramDescription.tipo }}
                        </td>
                        <td class="border py-2 px-2">
                            {{ paramDescription.cesavedac ? 'Si':'No' }}
                        </td>
                        <td class="border py-2 px-4">
                            <EditLink 
                                :url="route('params_description.edit', { paramDescription: paramDescription.id })"/>
                            <DeleteButton 
                                :funct="handleOpenDeleteModal"
                                :args="[paramDescription]"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <MyModal
            v-model="isDeleteModalVisible"
            title="Eliminar descripcion de parametros"
            @ok="() => handleDeleteParamDescription()"
            @close-from="handleCloseDeleteModal"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }">
            <p>Estas seguro de que deseas eliminar la descripcion de parametros {{ deleteParamDescription.descripcion }}?</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification
                :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>