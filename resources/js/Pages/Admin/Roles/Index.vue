<script setup>
    import { ref } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import { useMessages } from '@/composables/messages';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { Notivue, Notification, push } from 'notivue';
    const { getMessage } = useMessages();
    const props = defineProps({
        roles: Object,
    });

    if (getMessage()) {
        push.success(getMessage());
    }

    const isDeleteModalVisible = ref(false);
    const deleteRole = ref(null);
    const handleDelete = () => {
        router.delete(`/roles/${deleteRole.value.id}`, {
            onSuccess: () => {
                isDeleteModalVisible.value = false;
                push.success(`El rol ${deleteRole.value.name} ha sido eliminado correctamente`);
                deleteRole.value = null;
            }
        });
    };
    const handleShowDeleteModal = (role) => {
        deleteRole.value = role;
        isDeleteModalVisible.value = true;
    };
    const handleCloseDeleteModal = () => {
        isDeleteModalVisible.value = false;
        deleteRole.value = null;
    };
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="flex justify-between">
                <h1>Roles index page</h1>
                <Link 
                    :href="route('roles.create')"
                    class="py-2 px-3 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
                    New role
                </Link>
            </div>
            <div class="mt-6">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell>Actions</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="role in roles" :key="roles.id" class="border-b">
                            <TableDataCell>{{ role.id }}</TableDataCell>
                            <TableDataCell>{{ role.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link :href="route('roles.edit', role.id)" class="text-green-400 hover:text-green-600">
                                    Editar
                                </Link>
                                <button 
                                    @click="() => handleShowDeleteModal(role)" 
                                    method="delete"
                                    class="text-red-400 hover:text-red-600">
                                    Borrar
                                </button>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
            </div>
            <Notivue v-slot="item">
                <Notification :item="item" />
            </Notivue>
            <MyModal
                title="Eliminar usuario"
                v-model="isDeleteModalVisible"
                @close-from="handleCloseDeleteModal"
                @ok="handleDelete"
                :ok-button-props="{
                    class: ['bg-red-500', 'text-white']  
                }"
                :cancel-button-props="{
                    class: ['bg-blue-500', 'text-white']  
                }">
                <p>Estas seguro de eliminar el rol {{ deleteRole.name }}</p>
            </MyModal>
        </NavLayout>
    </AuthenticatedLayout>
</template>