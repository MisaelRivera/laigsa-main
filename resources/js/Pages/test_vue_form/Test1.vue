<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const inEnglish = ref(false);
    const handleSubmit = async (form$, formData) =>{
        const formState = useForm(form$.requestData);
        formState.post('/vue-form-tests/test1');
    };
</script>
<template>
    <AuthenticatedLayout>
        <h1>Welcome to vueform Test 1</h1>
            <div class="w-6/12">
                <Vueform 
                    :endpoint="false"
                    @submit="handleSubmit">
                    <ListElement 
                        name="samples"
                        :add-class="'w-full'">
                        <template #default="{ index }">
                            <ObjectElement
                                :name="index">
                                <TextElement 
                                    name="tipo_muestra"
                                    before="Tipo de muestra"
                                    :columns="{container: 6, wrapper:12}"/>
                                <TextElement 
                                    name="identificacion_muestra"
                                    before="Identificacion de la muestra"
                                    :columns="{container: 6, wrapper:12}"/>
                                <TextareaElement
                                    name="caracteristicas"
                                    before="Caracteristicas"/>
                            </ObjectElement>
                        </template>
                    </ListElement>
                    <ButtonElement
                        name="submit" submits>
                        Crear
                    </ButtonElement>
                </Vueform>
                    <button 
                        @click="inEnglish = !inEnglish"
                        class="py-2 px-4 rounded-md bg-yellow-500 text-slate-700 mt-2">
                        {{ inEnglish ? 'Change to spanish':'Cambiar a ingles' }}
                    </button>
            </div>
    </AuthenticatedLayout>
</template>