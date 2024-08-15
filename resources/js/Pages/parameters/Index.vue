<script setup>
    import { ref } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import { EyeOutlined, DeleteOutlined, EditOutlined } from '@ant-design/icons-vue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';

    const props = defineProps({
        parametersProp: Object,
    }); 
    console.log(props.parametersProp);
    const paginationInfo = ref(props.parametersProp);
    const { getMessage } = useMessages();
    const parameters = ref(props.parametersProp.data);
    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.get('/parameters', {filter: value}, {
            preserveState: true
        });
    };
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
                
                <Pagination 
                    :pagination-info="paginationInfo"/>
                <div class="w-40 mb-4">
                    <label for="filtro">Filtro</label>
                    <input 
                        type="text"
                        id="filtro"
                        name="parametro"
                        class="h-8 w-40 rounded"
                        @input="handleFilter">
                </div>
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
                        <td class="py-2.5 px-5 border">
                            <Link :href="route('parameters.edit', parameter.id)">
                                <EditOutlined />
                            </Link>
                            <Link :href="route('parameters.show', parameter.id)">
                                <EyeOutlined />
                            </Link>
                            <Link :href="route('parameters.destroy', parameter.id)">
                                <DeleteOutlined />
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>