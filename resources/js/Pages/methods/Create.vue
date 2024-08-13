<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { Form, FormItem, Input } from 'ant-design-vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const formState = useForm({
        nombre: null
    });

    const handleFinish = () => {
        formState.post('/methods');
    };
</script>
<template>
    <AdminLayout>
        <div class="mx-auto w-8/12 p-4">
            <div class="flex justify-between">
                <CreateTitle 
                    title="Crear metodo"
                    back-link="/methods"
                    own-link="/methods/create"/>
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
                    <button class="bg-green-500 text-white rounded py-2 px-4 mt-2">Crear</button>
                </Form>
        </div>
    </AdminLayout>
</template>