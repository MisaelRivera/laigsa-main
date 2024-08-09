<script setup>
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
        <div class="flex h-screen px-4">
            <div class="w-2/12 mt-2 bg-indigo-500 rounded h-4/5 py-2">
                <nav class="mt-10">
                    <ul class="list-none">
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('dashboard')}">
                            <Link :href="route('dashboard')">
                                Dashboard
                            </Link>
                        </li>
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('users.index')}">
                            <Link :href="route('users.index')">
                                Users
                            </Link>
                        </li>
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('roles.index')}">
                            <Link :href="route('roles.index')">
                                Roles
                            </Link>
                        </li>
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('permissions.index')}">
                            <Link :href="route('permissions.index')">
                                Permissions
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="w-9/12 p-5">
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
                                <TableDataCell>Edit/Delete</TableDataCell>
                            </TableRow>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>