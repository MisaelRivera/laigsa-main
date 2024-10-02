<script setup>
    import { Link, Head, useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        role: Object,
    });

    console.log(props.role.name);
    const form = useForm({
        name: props.role.name,
    });
    const handleSubmit = () => {
        try{
            form.put(route('roles.update', props.role.id));
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
                    <button 
                        class="rounded bg-blue-500 text-white py-2.5 px-4 mt-2 col-span-2">
                        Edit
                    </button>
                </Vueform>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>