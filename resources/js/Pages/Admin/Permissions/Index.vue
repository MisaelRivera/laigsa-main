<script setup>
    import { ref } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
    import { useMessages } from '@/composables/messages';
    import { Notivue, Notification, push } from 'notivue';
    const props = defineProps({
        permissions: Object,
    });

    const { getMessage } = useMessages();

    const isDeleteModalVisible = ref(false);
    const deletePermission = ref(null);
    const handleCloseDeleteModal = () => {
        isDeleteModalVisible.value = false;
        deletePermission.value = null;
    };

    const handleShowDeleteModal = (permission) => {
        deletePermission.value = permission;
        isDeleteModalVisible.value = true;
    };

    const handleDeletePermission = () => {
        router.delete(`/permissions/${deletePermission.value.id}`, {
            onSuccess: () => {
                push.success(`El permiso ${deletePermission.value.name} Se ha eliminado correctamente!`);
                isDeleteModalVisible.value = false;
                deletePermission.value = null;
            }
        });
    };

    if (getMessage()) {
        push.success(getMessage());
    }
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="grid grid-cols-12">
                <div class="col-span-12">
                    <IndexTitle 
                        title="Permisos"
                        :own-link="route('permissions.index')"
                        :add-link="route('permissions.create')"/>
                    <Table>
                        <template #header>
                            <TableRow>
                                <TableHeaderCell>Id</TableHeaderCell>
                                <TableHeaderCell>Nombre</TableHeaderCell>
                                <TableHeaderCell>Acciones</TableHeaderCell>
                            </TableRow>
                        </template>
                        <template #default>
                            <TableRow 
                                v-for="(permission, index) in permissions"
                                :key="index">
                                <TableDataCell>
                                    {{ permission.id }}
                                </TableDataCell>
                                <TableDataCell>
                                    {{ permission.name }}
                                </TableDataCell>
                                <TableDataCell>
                                    <Link 
                                        class="text-green-500 mr-2"
                                        :href="route('permissions.edit', permission.id)">
                                        Editar
                                    </Link>
                                    <button 
                                        class="text-red-500 mr-2"
                                        as="button"
                                        @click="() => handleShowDeleteModal(permission)">
                                        Eliminar
                                    </button>
                                </TableDataCell>
                            </TableRow>
                        </template>
                    </Table>
                </div>
            </div>
            <Notivue v-slot="item">
                <Notification :item="item" />
            </Notivue>
            <MyModal
                title="Eliminar permiso"
                :ok-button-props="{
                    class: ['bg-red-500', 'text-white']
                }"
                :cancel-button-props="{
                    class: ['bg-blue-500', 'text-white']
                }"
                @ok="handleDeletePermission"
                @close-from="handleCloseDeleteModal"
                v-model="isDeleteModalVisible">
                <p>Seguro de que deseas eliminar el permiso {{ deletePermission.name }}?</p>
            </MyModal>
        </NavLayout>
    </AuthenticatedLayout>
</template>