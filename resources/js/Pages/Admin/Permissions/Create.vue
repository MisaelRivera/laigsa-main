<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
    });

    const form = useForm({
        name: null,
    });

    const handleSubmit = (form$, FormData) => {
        form.name = form$.requestData.name;
        form.post(route('permissions.store'));
    };
</script>
<template>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="grid grid-cols-12">
                <div class="col-start-4 col-span-6">
                    <CreateTitle 
                        title="Crear permiso"
                        :own-link="route('permissions.create')"
                        :back-link="route('permissions.index')"/>
                    <Vueform
                        :endpoint="false"
                        @submit="handleSubmit"
                        :columns="{contaienr:12, wrapper: 12}">
                        <TextElement 
                            name="name"
                            before="Nombre"
                            :description="errors.name ? `<p class='text-red-500'>${errors.name}</p>`:null"/>
                        <ButtonElement
                            submits
                            name="create_permission">
                            Crear
                        </ButtonElement>
                    </Vueform>
                </div>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>