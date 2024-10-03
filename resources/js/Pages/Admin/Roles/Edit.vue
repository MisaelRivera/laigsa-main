<script setup>
    import { onMounted, ref } from 'vue';
    import { Link, Head, useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import Table from '@/Components/Table.vue';
    import TableRow from '@/Components/TableRow.vue';
    import TableHeaderCell from '@/Components/TableHeaderCell.vue';
    import TableDataCell from '@/Components/TableDataCell.vue';
import { push, Notivue, Notification } from 'notivue';
    const props = defineProps({
        role: Object,
        permissions: Array,
    });
    const permissionsTagsRef = ref(null);
    const form = useForm({
        name: props.role.name,
        permissions: props.role.permissions.map((permission) => {
            return permission.id;
        })
    });
    const handleSubmit = () => {
        try{
            form.put(route('roles.update', props.role.id));
        } catch (e) {
            console.log(e)
        }
    };

    const handleRemove = (permission) => {
        form.delete(`/roles/remove-permission/${props.role.id}/${permission.id}`,
            {
                onSuccess: () => {
                    push.success(`El permiso ${permission.name} se ha removido correctamente!`);
                    permissionsTagsRef.deselect(permission.id);
                }
            }
        );
    };
</script>
<template>
    <Head title="Manage roles"/>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="mt-6 grid grid-cols-12">
                <CreateTitle 
                    :title="`Editar rol`"
                    :own-link="`/roles/${role.id}/edit`"
                    back-link="/roles"
                    class="col-start-4 col-span-6"/>
                <Vueform 
                    :endpoint="false"
                    @submit="handleSubmit"
                    :columns="{container: 12, wrapper: 12}"
                    v-model="form"
                    class="col-start-4 col-span-6"
                    sync>
                    <TextElement
                        name="name"
                        before="Nombre"
                        v-model="form.name"/>
                    <TagsElement 
                        name="permissions"
                        before="Permisos"
                        ref="permissionsTagsRef"
                        v-model="form.permissions"
                        :items="permissions"
                        :search="true"/>
                    <button 
                        class="rounded bg-blue-500 text-white py-2.5 px-4 mt-2 col-span-2">
                        Edit
                    </button>
                </Vueform>
            </div>
            <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>
                            <TableHeaderCell>Acciones</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="(permission, index) in role.permissions" :key="index" class="border-b">
                            <TableDataCell>{{ permission.id }}</TableDataCell>
                            <TableDataCell>{{ permission.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <button 
                                    @click="() => handleRemove(permission)" 
                                    method="delete"
                                    class="text-red-400 hover:text-red-600">
                                    Revocar
                                </button>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
                <Notivue v-slot="item">
                    <Notification :item="item"/>
                </Notivue>
        </NavLayout>
    </AuthenticatedLayout>
</template>