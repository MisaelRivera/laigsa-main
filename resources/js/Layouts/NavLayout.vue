<script setup>
    import { Link } from '@inertiajs/vue3';
</script>

<template>
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
                            :class="{'bg-sky-400': route().current('users.*')}">
                            <Link :href="route('users.index')">
                                Users
                            </Link>
                        </li>
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('roles.*')}">
                            <Link :href="route('roles.index')">
                                Roles
                            </Link>
                        </li>
                        <li 
                            class="py-2 text-center border-y border-slate-400 text-white"
                            :class="{'bg-sky-400': route().current('permissions.*')}">
                            <Link :href="route('permissions.index')">
                                Permissions
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="w-9/12 p-2">
                <slot></slot>
            </div>
        </div>
</template>