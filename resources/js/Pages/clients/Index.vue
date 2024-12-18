<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Link } from '@inertiajs/vue3';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
     const props = defineProps({
        clients: Object,
     });
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
                        <th scope="col" class="px-2 py-3 w-[8%]">
                            Encargo
                        </th>
                        <th scope="col" class="px-2 py-3">
                            Telefono
                        </th>
                        <th scope="col" class="px-2 py-3">
                            Correo
                        </th>
                        <th scope="col" class="px-2 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in clients.data">
                        <td class="px-3 py-2">
                            <Link :href="route('clients.edit', {client: client.id})">
                                <i class="fas fa-eye"></i>
                            </Link>
                        </td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2"></td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </AuthenticatedLayout>
</template>