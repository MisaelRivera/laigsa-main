<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { Form, FormItem, Input } from 'ant-design-vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';

    const props = defineProps({
        method: Object,
        backUrl: String,
    });

    const formState = useForm({
        id: props.method.id_metodo,
        nombre: props.method.nombre
    });

    const handleFinish = () => {
        formState.put(`/methods/${formState.id}`);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-8/12 p-4">
            <div class="flex justify-between">
                <ShowTitle 
                    title="Editar metodo"
                    :back-url="backUrl"/>
                </div>
                <Form 
                    layout="vertical"
                    :model="formState"
                    @finish="handleFinish">
                    <FormItem
                        label="Metodo"
                        name="nombre"
                        :rules="[{ required: true, message: 'Introduzca el nombre del metodo' }]">
                        <Input
                            v-model:value="formState.nombre"/>
                    </FormItem>
                    <button class="bg-blue-500 text-white rounded py-2 px-4 mt-2">
                        Editar
                    </button>
                </Form>
        </div>
    </AuthenticatedLayout>
</template>