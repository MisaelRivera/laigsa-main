<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        errors: Object,
        samplesNumber: Number
    });
    const activeKey = ref('0');
    const samplesNumberErr = ref('');
    const inEnglish = ref(false);
    const handleSubmit = async (form$, formData) =>{
        if (form$.requestData.length === props.samplesNumber) {
            const formState = useForm({samples: form$.requestData});
            formState.post('/vue-form-tests/test1');
            console.log(props.errors);
            samplesNumberErr.value = '';
        } else {
            samplesNumberErr.value = 'Llena el formulario de todas las muestras';
        }
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
                                    :columns="{container: 6, wrapper:12}">
                                    <template #after="scope">
                                        <StaticElement 
                                            v-if="errors[`samples.${index}.tipo_muestra`]" 
                                            :content="errors[`samples.${index}.tipo_muestra`]"
                                            :attrs="{ class: 'text-red-500'}"/>
                                    </template>
                                </TextElement>
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
                <Vueform 
                    :endpoint="false"
                    @submit="handleSubmit"
                    :columns="{container:12, wrapper: 12}">
                    <ListElement
                        name="samples2">
                        <template #default="{index}">
                            <ObjectElement
                                :name="index"
                                parent="samples">
                                <TextElement 
                                    name="tipo_muestra"
                                    before="Tipo de muestra"
                                    :columns="{container: 6, wrapper:12}"
                                    rules="required">
                                <template #after="scope">
                                    <StaticElement 
                                        v-if="errors[`samples.${index}.tipo_muestra`]" 
                                        :content="errors[`samples.${index}.tipo_muestra`]"
                                        :attrs="{ class: 'text-red-500'}"/>
                                </template>
                            </TextElement>
                            <TextElement 
                                name="identificacion_muestra"
                                before="Identificacion de la muestra"
                                :columns="{container: 6, wrapper:12}"/>
                            <TextareaElement
                                name="caracteristicas"
                                before="Caracteristicas"/>
                            <SelectElement
                                name="aguas_alimentos"
                                before="Aguas o alimentos"
                                :items="[
                                    { value: null, label: 'Elija una opcion' },
                                    { value: 'Aguas', label: 'Aguas' },
                                    { value: 'Alimentos', label: 'Alimentos' },
                                ]"/>
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