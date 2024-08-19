<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parameter: {
            type: Object
        }
    });
    const form = useForm({
        parametro: props.parameter.parametro,
    });
    console.log(props.parameter);
    const handleUpdateSubmit = () => {
        form.put(`/parameters/${props.parameter.id}`);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-4/12 p-5 rounded-lg bg-gray-100">
            <h2>Edita el parametro</h2>
            <a-form
                layout="vertical"
                :model="form"
                @finish="handleUpdateSubmit">
                <div class="mb-4">
                    <a-form-item 
                        label="Parametro"
                        name="parametro"
                        :rules="[{ required: true, message: 'Ingrese el parametro' }]">
                        <a-input
                            v-model:value="form.parametro"
                            class="rounded h-8"/>
                    </a-form-item>
                    <p 
                        class="text-red-500"
                        v-if="form.errors.parametro">
                        {{ form.errors.parametro }}
                    </p>
                </div>
                <button class="rounded bg-blue-400 text-white py-2 px-4">
                    Editar
                </button>
            </a-form>
        </div>
    </AuthenticatedLayout>
</template>