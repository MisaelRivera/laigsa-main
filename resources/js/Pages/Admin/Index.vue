<script setup>
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>
<template>
    <AuthenticatedLayout>
        <template #header>
            <h1>Hi there admin</h1>
        </template>
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

            </div>
        </div>

    </AuthenticatedLayout>
</template>