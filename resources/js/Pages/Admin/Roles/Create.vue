<script setup>
    import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        permissions: Array,
        errors: Object,
    });
    const form = useForm({
        name: '',
        permissions: []
    });
    const handleSubmit = (form$, FormData) => {
        try{
            form.name = form$.requestData.name;
            form.permissions = form$.requestData.permissions;
           form.post(route('roles.store'));
        } catch (e) {
            console.log(e)
        }
    };
</script>
<template>
    <Head title="Manage roles"/>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="mt-6 grid grid-cols-12">
                <div class="flex justify-between col-start-4 col-span-6">
                    <CreateTitle 
                        title="Crear rol"
                        own-link="/roles/creates"
                        back-link="/roles"/>
                </div>
                <Vueform
                    :endpoint="false"
                    @submit="handleSubmit"
                    class="col-start-4 col-span-6 mt-2"
                    :columns="{container:12, wrapper:12}">
                        <TextElement 
                            name="name"
                            before="Nombre"
                            :description="errors.name ? `<p class='text-red-500'>${errors.name}</p>`:null"/>
                        <TagsElement 
                            name="permissions"
                            before="Permisos"
                            :items="permissions"
                            :search="true"/>
                    <button 
                        class="rounded bg-green-500 text-white py-2.5 px-4 mt-2 col-span-2">
                        Create
                    </button>
                </Vueform>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>