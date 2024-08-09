<script setup>
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
    const props = defineProps({
        users: Object,
    });
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
            <div class="w-9/12">
                <h1>Users index page</h1>
                <div class="mt-6">
                    <Table>
                        <template #header>
                            <TableRow>
                                <TableHeaderCell>ID</TableHeaderCell>
                                <TableHeaderCell>Name</TableHeaderCell>
                                <TableHeaderCell>Email</TableHeaderCell>
                                <TableHeaderCell>Actions</TableHeaderCell>
                            </TableRow>
                        </template>
                        <template #default>
                            <TableRow v-for="user in users" :key="users.id" class="border-b">
                                <TableDataCell>{{ user.id }}</TableDataCell>
                                <TableDataCell>{{ user.name }}</TableDataCell>
                                <TableDataCell>{{ user.email }}</TableDataCell>
                                <TableDataCell>Edit/Delete</TableDataCell>
                            </TableRow>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>