<script setup>
    import { Link } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        parametersProp: Object,
    }); 

    const { getMessage } = useMessages();
    const parameters = props.parametersProp.data;
    console.log(props.parametersProp);
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <a-alert
                type="success"
                v-if="getMessage()"
                :message="getMessage()">
            </a-alert>
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Parametros"
                    :add-link="route('parameters.create')"
                    :own-link="route('parameters.index')"/>
                
                <div></div>
                <Pagination 
                    :pagination-info="parametersProp"/>
            </div>
            <table class="borde w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="py-2.5 px-5 border text-left">Parametro</th>
                        <th class="py-2.5 px-5 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="parameter in parameters"
                        class="bg-slate-50">
                        <td class="py-2.5 px-5 border">
                            {{ parameter.parametro }}
                        </td>
                        <td class="py-2.5 px-5 border"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>