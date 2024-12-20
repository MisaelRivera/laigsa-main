<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Link } from '@inertiajs/vue3';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
     const props = defineProps({
        clients: Object,
     });
     const handleChange = (ev) => {
        const value = ev.target.checked;
        const label = ev.target.previousElementSibling;
        if (value) {
            label.classList.add('bg-emerald-400');
        } else {
            label.classList.remove('bg-emerald-400');
        }
     };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-full mx-auto mt-3">
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Clientes"
                    :add-link="route('clients.create')"
                    :own-link="route('clients.index')"/>
                <Pagination 
                    :links="clients.links"/>
                <div class="flex items-center">
                    <div>
                    </div>
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 bg-gray-50">
                    <tr>
                        <th 
                            scope="col" 
                            class="px-2 py-3">
                        </th>
                        <th scope="col" class="px-2 py-3">Cvd</th>
                        <th scope="col" class="px-2 py-3">
                            <label for="cliente">
                                Cliente
                            </label>
                            <input 
                                type="text"
                                name="cliente"
                                id="cliente"
                                placeholder="Buscar..."
                                class="border w-full">
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <label for="sitio-muestreo">
                                Sitio de muestreo
                            </label>
                            <input 
                                type="text"
                                name="sitio_muestreo"
                                id="sitio-muestreo"
                                placeholder="Buscar..."
                                class="border w-full">
                        </th>
                        <th scope="col" class="px-2 py-3 w-[35%]">
                            Observaciones
                        </th>
                        <th scope="col" class="px-2 py-3">
                            Encargado
                        </th>
                        <th scope="col" class="px-2 py-3 w-[7%]">
                            Telefono
                        </th>
                        <th scope="col" class="px-2 py-3">
                            Correo
                        </th>
                        <th scope="col" class="px-2 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="[&>*:nth-child(odd)]:bg-gray-100">
                    <tr 
                        v-for="client in clients.data"
                        class="border-b">
                        <td class="px-3 py-2">
                            <Link :href="route('clients.edit', {client: client.id})">
                                <i class="fas fa-eye text-blue-500"></i>
                            </Link>
                        </td>
                        <td class="px-3 py-2">
                            <label
                                :for="`cesavedac-checkbox-${client.id}`"
                                class="border h-4 w-4 rounded-sm flex justify-center items-center bg-white"
                                :class="{'bg-emerald-400': client.cesavedac}">
                                <i class="fas fa-check text-[8px] text-white"></i>
                            </label>
                            <input 
                                type="checkbox"
                                class="hidden"
                                :id="`cesavedac-checkbox-${client.id}`"
                                @change="handleChange"
                                :checked="client.cesavedac">
                        </td>
                        <td class="px-3 py-2">
                            {{ client.cliente }}
                        </td>
                        <td class="px-3 py-2">
                            {{ client.direccion_muestreo }}
                        </td>
                        <td class="px-3 py-2">
                            {{ client.observaciones }}
                        </td>
                        <td class="px-3 py-2">
                            {{ client.encargado }}
                        </td>
                        <td class="px-3 py-2">
                            {{ client.telefono }}
                        </td>
                        <td class="px-3 py-2">
                            {{ client.correo_electronico }}
                        </td>
                        <td class="px-3 py-2">
                            <Link
                                :href="route('clients.edit', {client:client.id})">
                                <i class="fas fa-edit bg-blue-500 text-white px-2 py-2 rounded-full"></i>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </AuthenticatedLayout>
</template>
<style>
    .custom-checkbox {
        width: 1.25rem; /* Size */
        height: 1.25rem;
        border-color: #94a3b8; /* Customize border color */
        background-color: transparent;
    }

    .custom-checkbox:checked {
        background-color: #3b82f6; /* Customize checked color */
        border-color: #3b82f6;
    }
</style>