<script setup>
    import { ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, } from '@inertiajs/vue3';
    import { Form, FormItem, Input, Row, Col, Alert } from 'ant-design-vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
        unit: Object
    });
    const formState = useForm({
        nombre: props.unit.nombre
    });

    const handleSubmit = () => {
        formState.put('/units/' + props.unit.id);
    };
    
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto mt-4 bg-slate-50 py-3">
            <Row>
                <Col :span="18" :offset="3">
                    <CreateTitle 
                        :title="`Editar unidad ${unit.nombre}`"
                        own-link="/units/create"
                        back-link="/units"/>
                </Col>
            </Row>
            <Form
                layout="vertical"
                :model="formState"
                @finish="handleSubmit">
                <Row>
                    <Col :span="18" :offset="3">
                        <template v-if="errors">
                            <Alert
                                type="error"
                                :message="errors[errorKey]"
                                v-for="errorKey in Object.keys(errors)"/>
                        </template>
                        <FormItem
                            name="nombre"
                            label="Unidad"
                            >
                            <Input 
                                v-model:value="formState.nombre"/>
                        </FormItem>
                    </Col>
                </Row>
                <Row>
                    <Col :span="6" :offset="3">
                        <button 
                            class="rounded py-2 px-4 bg-blue-400 text-white">
                            Editar
                        </button>
                    </Col>
                </Row>
            </Form>
        </div>
    </AuthenticatedLayout>
</template>