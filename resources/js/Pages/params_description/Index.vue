<script setup>
    import { ref } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';

    const props = defineProps({
        paramsDescription: Array,
    });

    const deleteParamDescription = ref(null);
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-5/12 mx-auto">
            <table>
                <thead>
                    <tr>
                        <th class="border py-2 px-4">Descripcion</th>
                        <th class="border py-2 px-4">Version</th>
                        <th class="border py-2 px-4"></th>
                        <th class="border py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="paramDescription in paramsDescription.data">
                        <td class="border py-2 px-4">
                            {{ paramDescription.descripcion }}
                        </td>
                        <td class="border py-2 px-4">
                            {{ paramDescription.version }}
                        </td>
                        <td class="border py-2 px-4">
                            <EditLink 
                                :url="route('params_description.edit', { paramDescription: paramDescription.id })"/>
                        </td>
                        <td class="border py-2 px-4">
                            <DeleteButton 
                                />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>