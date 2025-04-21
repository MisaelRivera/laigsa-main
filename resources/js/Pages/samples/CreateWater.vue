<script setup>
    import { ref, onMounted } from 'vue';
    import { router, usePage, useForm } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper.js';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import { Validator } from '@vueform/vueform';
    import { useGenerateRepetitive } from '@/composables/generateRepetitiveContent';

    const props = defineProps({
        order: Object,
        numeroMuestras: Number,
        inicioMuestras: Number,
        parametersProp: Array,
        previousRouteName: String,
        createFields: Array,
        oldParams: Array,
        errors: Object,
    });
    
    const { generateIndexedNames } = useGenerateRepetitive();
    const rangoMuestras = createRange(props.inicioMuestras + 1, props.numeroMuestras);
    const tabsContainer = ref(null);
    onMounted(() => {
      // Add the class to the FormTabs container
      tabsContainer.value.$el.classList.add('overflow-x-scroll');
    });
    const identificaciones_muestra = props.order.cliente.identificaciones_muestra.map((identificacion_muestra) => {
        return { value: identificacion_muestra.id, label: identificacion_muestra.identificacion_muestra };
    });
    identificaciones_muestra.unshift({ value: null, label: 'Elija una opcion' });

    const tiposMuestreo = [
        { value: null, label: 'Elija un tipo' },
        'Simple',
        { value: 'Compuesto_4', label: 'Compuesto 4' },
        { value: 'Compuesto_6', label: 'Compuesto 6' },
        'N/A'
    ];

    const phRange = class extends Validator {
        get msg() {
            return 'El valor debe estar entre 9.3 y 9.7';
        }

        check(value) {
            if (value.match(/[0-9]/)) {
                return parseFloat(value) > 9.299 && parseFloat(value) < 9.71
            }
            return true;
        }
    }

    const handleSubmit = (form$, FormData) => {
        const vueFormData = form$.requestData;
        const url = `/water_samples/${props.order.id}/${props.numeroMuestras}`;
        router.post(url, vueFormData);
    };
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto mt-3">
            <CreateTitle
                title="Crear muestras"
                :ownLink="`/muestras/create/${order.folio}/${numeroMuestras}`"
                :backLink="'/orders'"/>
            <p 
                v-for="key in Object.keys(errors)"
                class="text-red-500">
                {{ errors[key] }}
            </p>
            <Vueform
                :endpoint="false"
                @submit="handleSubmit"
                :columns="{ container: 12, wrapper: 12 }"
                :scroll-to-invalid="false"
                :add-class="{
                    'FormTabs.container': ''
                }"
                :formErrors="errors">
                <template #empty>
                    <FormTabs
                     ref="tabsContainer">
                        <FormTab
                            :name="`muestra_${i}`"
                            :label="`MFQ-${order.folio} - ${i}`"
                            :elements="generateIndexedNames(i, createFields, '_', ['create_water_samples'])"
                            :add-class="{
                                wrapper_active: ['border-b-4', 'bg-red-100'],
                            }"
                            v-for="i in rangoMuestras"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in rangoMuestras">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`id_identificacion_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in rangoMuestras"
                                 :items="identificaciones_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra ${i}` }}</div>
                                </template>
                            </SelectElement>
                            <TextElement 
                                :name="`caracteristicas_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in rangoMuestras">
                                 <template #before>
                                    <div class="text-sm">{{ `Caracteristicas ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`muestreador_${i}`"
                                :items="[
                                    {value: null, label: 'Elija un valor'},
                                    'RCHH',
                                    'JHM',
                                    'FESR',
                                    'JPMS',
                                    'Cliente',
                                    'CESAVEDAC',
                                    'QSM',
                                    'APPC',
                                    'LMQH',
                                ]"
                                :columns="{container:2, wrapper:12}" v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Muestreador ${i}` }}</div>
                                </template> 
                            </SelectElement>
                            <TextElement 
                                :name="`ph_${i}`"
                                :columns="{container:1, wrapper:12}"
                                 v-for="i in rangoMuestras">
                                 <template #before>
                                    <div class="text-sm">{{ `pH ${i}` }}</div>
                                </template> 
                            </TextElement>
                            <CheckboxElement
                                :name="`tratada_biologicamente_${i}`"
                                v-for="i in rangoMuestras"
                                :columns="{ container:3, wrapper: 12 }">
                                <p class="text-sm">Tratada biologicamente {{ i }}</p>
                            </CheckboxElement>
                            <RadiogroupElement
                                :name="`cloro_${i}`"
                                :columns="{ container: 4, wrapper:12 }"
                                :remove-class="{
                                    wrapper: 'flex-col'
                                }"
                                :add-class="{
                                    wrapper: 'gap-1'
                                }"
                                :items="[
                                    'Presente',
                                    'Ausente',
                                    'Laboratorio',
                                    'N/A',
                                ]" v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Cloro ${i}` }}</div>
                                </template>    
                            </RadiogroupElement>
                            <TextElement 
                                :name="`valor_cloro_${i}`"
                                :columns="{ container:2, wrapper:12 }"
                                :conditions="[
                                    [`cloro_${i}`, ['Presente', 'Ausente']],
                                    [`tipo_muestreo_${i}`, 'Simple']
                                ]"
                                default="N/A"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Valor del cloro ${i}` }}</div>
                                </template>   
                            </TextElement>
                            <TextElement 
                                :name="`ph_cromo_hexavalente_${i}`"
                                :rules="[phRange]"
                                :columns="{ container:2, wrapper:12 }"
                                default="N/A"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `pH Cr VI ${i}` }}</div>
                                </template>   
                            </TextElement>
                            <SelectElement
                                :name="`tipo_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12}"
                                :items="tiposMuestreo"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo ${i}` }}</div>
                                </template>
                            </SelectElement>
                            <StaticElement 
                                content="<div></div>"
                                :columns="{ container: 3, wrapper:12}"
                                :conditions="[
                                    [
                                        [`cloro_${i}`, '!=', ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i}`, '!=', 'Simple'],
                                    ]
                                ]"
                                :name="`offset_${i}`"
                                v-for="i in rangoMuestras"/>
                                <StaticElement 
                                    content="<div></div>"
                                    :columns="{ container: 1, wrapper:12}"
                                    :conditions="[
                                        [`cloro_${i}`, ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i}`, 'Simple'],
                                    ]"
                                    :name="`offset2_${i}`"
                                    v-for="i in rangoMuestras"/>
                            <DateElement
                                :name="`fecha_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12 }"
                                v-for="i in rangoMuestras"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo ${i}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo_${i}`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de muestreo ${i}` }}</div>
                                </template>
                            </TextElement>
                            <DateElement
                                :name="`fecha_final_muestreo_${i}`"
                                :add-class="{
                                    ElemenText: {
                                        container: 'my-own-class'
                                    }
                                }"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, offset:4, wrapper: 12 }"
                                v-for="i in rangoMuestras"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de fin de muestreo ${i}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_final_muestreo_${i}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de fin muestreo ${i}` }}</div>
                                </template>
                            </TextElement>
                            <DateElement
                                :name="`fecha_composicion_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, offset:4, wrapper: 12 }"
                                v-for="i in rangoMuestras"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de composicion ${i}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_composicion_${i}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de composicion ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_1_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 1 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_2_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 2 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_3_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 3 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_4_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 4 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_5_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 5 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_6_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 6 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros_${i}`"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in rangoMuestras">
                                <template #before>
                                    <p class="text-sm">{{ `Parametros ${i}` }}</p>
                                </template>
                            </SelectElement>
                            <TextareaElement 
                                :name="`otros_${i}`"
                                v-for="i in rangoMuestras"
                                :conditions="[
                                    [`parametros_${i}`, 'Otro']
                                ]"
                                >
                                <template #before>
                                    <div class="text-sm">{{ `Otros ${i}` }}</div>
                                </template>
                            </TextareaElement>
                            <RadiogroupElement
                                :name="`preservacion_correcta_${i}`"
                                :columns="{ container: 4, wrapper:12 }"
                                :remove-class="{
                                    wrapper: 'flex-col'
                                }"
                                :add-class="{
                                    wrapper: 'gap-1'
                                }"
                                :items="[
                                    'Si',
                                    'No',
                                    'N/A',
                                ]" v-for="i in rangoMuestras">
                                <template #before>
                                    <div class="text-sm">{{ `Preservada correctamente ${i}` }}</div>
                                </template>    
                            </RadiogroupElement>
                        <ButtonElement 
                            submits
                            name="create_water_samples"
                            class="mt-3">
                            Crear
                        </ButtonElement>
                    </FormElements>
                </template>
            </Vueform>
        </div> 
    </AuthenticatedLayout>
</template>