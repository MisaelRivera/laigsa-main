<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
        permission: Object,
    });

    const form = useForm({
        name: props.permission.name,
    });

    const handleSubmit = () => {
        form.put(route('permissions.update', props.permission.id));
    };
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="grid grid-cols-12">
                <div class="col-start-4 col-span-6">
                    <CreateTitle 
                        title="Editar permiso"
                        :own-link="route('permissions.create')"
                        :back-link="route('permissions.index')"/>
                    <Vueform
                        :endpoint="false"
                        @submit="handleSubmit"
                        :columns="{contaienr:12, wrapper: 12}"
                        v-model="form"
                        sync>
                        <TextElement 
                            name="name"
                            before="Nombre"
                            v-model="form.name"
                            :description="errors.name ? `<p class='text-red-500'>${errors.name}</p>`:null"/>
                        <button
                           class="py-1 px-2 rounded-md text-white bg-blue-500 col-span-2">
                            Editar
                        </button>
                    </Vueform>
                </div>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>