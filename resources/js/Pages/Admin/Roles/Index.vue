<script setup>
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import { useMessages } from '@/composables/messages';
    const { getMessage } = useMessages();
    const props = defineProps({
        roles: Object,
    });
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <a-alert 
                v-if="getMessage()"
                :message="getMessage()"
                type="success"
                closable/>
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
                                    Edit
                                </Link>
                                <Link 
                                    :href="route('roles.destroy', role.id)" 
                                    as="button" 
                                    method="delete"
                                    class="text-red-400 hover:text-red-600">
                                    Delete
                                </Link>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>