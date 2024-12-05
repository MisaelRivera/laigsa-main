<script setup>
    import { ref, onMounted } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowTitle from '@/Components/Shared/ShowTitle.vue';

    const props = defineProps({
        method: Object,
        backUrl: String,
    });

    const form$ = ref(null);  

    onMounted(() => {
        const nombre = form$.value.el$('nombre');
        nombre.update(props.method.nombre);
    });

    const formState = useForm({
        id_metodo: props.method.id_metodo,
        nombre: props.method.nombre
    });

    const handleFinish = () => {
        formState.nombre = form$.value.el$('nombre').value;
        formState.put(`/methods/${formState.id_metodo}`);
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
                <Vueform
                    :columns="{container:12, wrapper:12}"
                    @submit="handleFinish"
                    :endpoint="false"
                    ref="form$">

                    <TextElement
                        v-model:value="formState.nombre"
                        name="nombre"
                        before="Metodo">
                        <template #description>
                            <p v-if="formState.errors.nombre"
                                class="text-red-500">
                                {{ formState.errors.nombre }}
                            </p>
                        </template>
                    </TextElement>
                    <button class="bg-blue-500 text-white rounded py-2 px-4 mt-2">
                        Editar
                    </button>
                </Vueform>
        </div>
    </AuthenticatedLayout>
</template>