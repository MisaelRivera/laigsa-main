<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
    });
    const formState = useForm({
        nombre: 'Juan'
    });
    const handleSubmit = (form$) => {
        formState.nombre = form$.requestData.nombre;
        formState.post('/units');
    };
    
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto mt-4 bg-slate-50 py-3 px-5">
            <CreateTitle 
                title="Crear unidad"
                own-link="/units/create"
                back-link="/units"/>
                <Vueform
                    :columns="{ container:12 , wrapper: 12}"
                    :float-placeholders="false"
                    :endpoint="false"
                    @submit="handleSubmit">
                    <TextElement
                        name="nombre"
                        before="Nombre">
                        <template #description>
                            <p v-if="formState.errors.nombre"
                                class="text-red-400">
                                {{ formState.errors.nombre }}
                            </p>
                        </template>
                    </TextElement>
                    <button 
                        class="rounded-md py-1 px-2 bg-green-500 text-white col-span-3">
                        Crear
                    </button>
                </Vueform>
        </div>
    </AuthenticatedLayout>
</template>