<script setup>
    import { ref } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    const { getMessage } = useMessages();
    const props = defineProps({
        users: Object,
    });
    if (getMessage()) {
        push.success(getMessage());
    }
    const isDeleteModalVisible = ref(false);
    const deleteUser = ref(null);

    const handleShowDeleteModal = (user) => {
        deleteUser.value = user;
        isDeleteModalVisible.value = true;
    };

    const handleCloseDeleteModal = () => {
        deleteUser.value = null;
        isDeleteModalVisible.value = false;
    };

    const handleDelete = () => {
       
        router.delete(`/users/${deleteUser.value.id}`, {
            onSuccess: () => {
                isDeleteModalVisible.value = false;
                push.success(`El usuario ${deleteUser.value.name} Se ha eliminado correctamente`);
                deleteUser.value = null;
            }
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="flex justify-between">
                <h1>Users index page</h1>
                <Link 
                    :href="route('users.create')"
                    class="py-2 px-3 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
                    New user
                </Link>
            </div>
            <div class="mt-6">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>
                            <TableHeaderCell>Email</TableHeaderCell>
                            <TableHeaderCell>Detalles</TableHeaderCell>
                            <TableHeaderCell>Acciones</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="user in users" :key="users.id" class="border-b">
                            <TableDataCell>{{ user.id }}</TableDataCell>
                            <TableDataCell>{{ user.name }}</TableDataCell>
                            <TableDataCell>{{ user.email }}</TableDataCell>
                            <TableDataCell>
                                <Link :href="route('users.show', user.id)">
                                    <i class="fas fa-eye text-blue-500"></i>
                                </Link>
                            </TableDataCell>
                            <TableDataCell>
                                <Link :href="route('users.edit', user.id)">
                                    <span class="text-green-500">Editar</span>
                                </Link>
                                <span 
                                    class="text-red-500 ml-2"
                                    @click="() => handleShowDeleteModal(user)">
                                    Borrar
                                </span>
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
                <p>Estas seguro de eliminar el usuario {{ deleteUser.name }}</p>
            </MyModal>
        </NavLayout>
    </AuthenticatedLayout>
</template>