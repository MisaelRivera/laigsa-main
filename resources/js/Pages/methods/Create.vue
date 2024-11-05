<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const formState = useForm({
        nombre: null
    });

    const handleSubmit = (form$) => {
        formState.nombre = form$.requestData.nombre;
        formState.post('/methods');
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-8/12 p-4">
            <div class="flex justify-between">
                <CreateTitle 
                    title="Crear metodo"
                    back-link="/methods"
                    own-link="/methods/create"/>
                </div>
                <Vueform
                    :endpoint="false"
                    @submit="handleSubmit"
                    :columns="{container:12, wrapper:12}">
                    <TextElement
                        name="nombre"
                        before="Metodo">
                        <template #description>
                            <p 
                                v-if="formState.errors.nombre"
                                class="text-red-400">
                                {{ formState.errors.nombre }}
                            </p>
                        </template>
                    </TextElement>
                    <ButtonElement
                        name="create_method_btn"
                        submits
                        :columns="{container:3, wrapper:12}">
                        Crear
                    </ButtonElement>
                </Vueform>
        </div>
    </AuthenticatedLayout>
</template>