<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({

        rule: {
            type: Object,
        },

        page: {
            type: String
        }
    });
    const form = useForm({
        norma: props.rule.norma,
        tipo: props.rule.tipo,
        descripcion: props.rule.descripcion,
        cesavedac: props.rule.cesavedac,
        aguas: props.rule.aguas,
    });
    const handleCreateSubmit = () => {
        form.post('/rules');
    };
    console.log(props.page);
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
            <CreateTitle 
                title="Crear una nueva norma"
                :back-link="route('rules.index', { page: page })"
                :own-link="route('rules.edit', { rule: props.rule, page: page })"/>
            <a-form
                layout="vertical"
                :model="form"
                @finish="handleCreateSubmit">
                <a-row :gutter="8">
                    <a-col :span="12">
                        <div class="mb-4">
                            <a-form-item 
                                label="Norma"
                                name="norma"
                                :rules="[{ required: false, message: 'Ingrese la norma' }]">
                                <a-input
                                    v-model:value="form.norma"
                                    class="rounded h-8"/>
                                <p 
                                    class="text-red-500"
                                    v-if="form.errors.norma">
                                    {{ form.errors.norma }}
                                </p>
                            </a-form-item>
                        </div>
                    </a-col>
                    <a-col :span="12">
                        <div class="mb-4">
                            <a-form-item
                                label="Tipo"
                                name="tipo"
                                :rule="[{ required: true, message: 'Ingrese el tipo' }]">
                                <a-input
                                    v-model:value="form.tipo"
                                    class="rounded h-8"/>
                                <p 
                                    class="text-red-500"
                                    v-if="form.errors.tipo">
                                    {{ form.errors.tipo }}
                                </p>
                            </a-form-item>
                        </div>
                    </a-col>
                </a-row>
                <a-row  :gutter="8">
                    <a-col :span="12">
                        <div class="mb-4">
                            <a-form-item
                                label="Descripcion"
                                name="descripcion"
                                :rule="[{ required: true, message: 'Ingrese la descripcion' }]">
                                <a-input
                                    v-model:value="form.descripcion"
                                    class="rounded h-8"/>
                                <p 
                                    class="text-red-500"
                                    v-if="form.errors.descripcion">
                                    {{ form.errors.descripcion }}
                                </p>
                            </a-form-item>
                        </div>
                    </a-col>
                    <a-col :span="6">
                        <a-form-item
                            name="cesavedac"
                            class="mb-0 mt-6">
                            <a-checkbox
                                v-model:checked="form.cesavedac">Cesavedac</a-checkbox>
                        </a-form-item>
                    </a-col>
                    <a-col :span="6">
                        <a-form-item
                            name="aguas"
                            class="mb-0 mt-6">
                            <a-checkbox
                                v-model:checked="form.aguas">Aguas</a-checkbox>
                        </a-form-item>
                    </a-col>
                </a-row>
                <button class="rounded bg-blue-400 text-white py-2 px-4">
                    Edit
                </button>
            </a-form>
        </div>
    </AuthenticatedLayout>
</template>