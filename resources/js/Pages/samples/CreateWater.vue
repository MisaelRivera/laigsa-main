<script setup>
    import { ref, onMounted } from 'vue';
    import { router, usePage, useForm } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper.js';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const props = defineProps({
        order: Object,
        numeroMuestras: Number,
        inicioMuestras: Number,
        identificacionesMuestra: Array,
        parametersProp: Array,
        errors: Object,
    });
    const tabsContainer = ref(null);
    onMounted(() => {
      // Add the class to the FormTabs container
      tabsContainer.value.$el.classList.add('overflow-x-scroll');
    });
    console.log(props.errors);
    const page = usePage();
    const oldParams = [
        { value: null, label: 'Elija un parametro' },
        "NOM-001-SEMARNAT-2021", "NOM-001-SEMARNAT-2021- incluir DBO5, Solidos Sedimentables, Materia Flotante, Coliformes Fecales", "Nom-001-semarnat-1996", "Nom-001-semarnat-1996/color verd, cloruros, e. coli, enterococos fecales. Contratar toxicidad vibrio fisheri,  cot", "Nom-001-semarnat-1996/sin met y cn", "NOM-127-SSA1-2021 Norma completa", "NOM-127-SSA1-2021, Parte de la Norma",  "Nom-127-ssa1-1994. Parte de la norma", "Nom-127-ssa1-1994. Parte de la norma/con olor y sabor", "Nom-127-ssa1-1994. Norma completa/con olor y sabor", "Nom-002-semarnat-1996", "Nom-003-semarnat-1996", "CT, As, Pb, Fluor", "CF, CT (purificada)", "CT (purificada)", "Salmonella. Contratar toxicidad", "Dureza, alcalinidad, ph, conductividad, metales.",  "E. Coli, cf, ct de nom-127-ssa1-1994.",  "Mesofilicos aerobios",  "Ph, cn",  "Sst, ss, dqo, ntk, nitratos, nitritos, fosforo total, nitrogeno total",  "Nom-004-semarnat-2002",  "Nom-004: ph, conductividad, sulfatos, nitratos, cloruros, dt, sdt, cf, ca, na, k",  "Nom-127: cn",  "Nom-127-ssa1-1994/ contratar: btex, trihalometanos, fenoles, yodo residual",  "Ph, cn", "Otro"
    ];

    const tiposMuestreo = [
        { value: null, label: 'Elija un tipo' },
        'Simple',
        { value: 'Compuesto_4', label: 'Compuesto 4' },
        { value: 'Compuesto_6', label: 'Compuesto 6' },
        'N/A'
    ];

    const params = props.order.v_libreta_resultados ? parametersProp.map(parameter => parameter.parametro):oldParams;

    const handleSubmit = (form$, FormData) => {
        const vueFormData = form$.requestData;
        router.post(`/water_samples?inicio_muestras=${props.inicioMuestras}&numero_muestras=${props.numeroMuestras}`, vueFormData);
    };
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
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
                            :name="`muestra_${i + 1}`"
                            :label="`Muestra ${order.folio} - ${i + 1}`"
                            :elements="[
                                `tipo_muestra_${i + 1}`, 
                                `identificacion_muestra_${i + 1}`,
                                `caracteristicas_${i + 1}`,
                                `muestreador_${i + 1}`,
                                `ph_${i + 1}`,
                                `tratada_biologicamente_${i + 1}`,
                                `cloro_${i + 1}`,
                                `valor_cloro_${i + 1}`,
                                `ph_cromo_hexavalente_${i + 1}`,
                                `tipo_muestreo_${i + 1}`,
                                `fecha_muestreo_${i + 1}`,
                                `hora_muestreo_${i + 1}`,
                                `fecha_final_muestreo_${i + 1}`,
                                `hora_final_muestreo_${i + 1}`,
                                `fecha_composicion_${i + 1}`,
                                `hora_composicion_${i + 1}`,
                                `flujo_1_${i + 1}`,
                                `flujo_2_${i + 1}`,
                                `flujo_3_${i + 1}`,
                                `flujo_4_${i + 1}`,
                                `flujo_5_${i + 1}`,
                                `flujo_6_${i + 1}`,
                                `parametros_${i + 1}`,
                                `otros_${i + 1}`,
                                `preservacion_correcta_${i + 1}`,
                                `offset_${i + 1}`,
                                `offset2_${i + 1}`,
                                'create_water_samples'
                            ]"
                            v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i + 1}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`identificacion_muestra_${i + 1}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`caracteristicas_${i + 1}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Caracteristicas ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`muestreador_${i + 1}`"
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
                                :columns="{container:2, wrapper:12}" v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Caracteristicas ${i + 1}` }}</div>
                                </template> 
                            </SelectElement>
                            <TextElement 
                                :name="`ph_${i + 1}`"
                                :columns="{container:1, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `pH ${i + 1}` }}</div>
                                </template> 
                            </TextElement>
                            <CheckboxElement
                                :name="`tratada_biologicamente_${i + 1}`"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                :columns="{ container:3, wrapper: 12 }">
                                <p class="text-sm">Tratada biologicamente {{ i + 1 }}</p>
                            </CheckboxElement>
                            <RadiogroupElement
                                :name="`cloro_${i + 1}`"
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
                                ]" v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Cloro ${i + 1}` }}</div>
                                </template>    
                            </RadiogroupElement>
                            <TextElement 
                                :name="`valor_cloro_${i + 1}`"
                                :columns="{ container:2, wrapper:12 }"
                                :conditions="[
                                    [`cloro_${i + 1}`, ['Presente', 'Ausente']],
                                    [`tipo_muestreo_${i + 1}`, 'Simple']
                                ]"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Valor del cloro ${i + 1}` }}</div>
                                </template>   
                            </TextElement>
                            <TextElement 
                                :name="`ph_cromo_hexavalente_${i + 1}`"
                                :columns="{ container:2, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `pH Cr VI ${i + 1}` }}</div>
                                </template>   
                            </TextElement>
                            <SelectElement
                                :name="`tipo_muestreo_${i + 1}`"
                                :columns="{ container: 2, wrapper: 12}"
                                :items="tiposMuestreo"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo ${i + 1}` }}</div>
                                </template>
                            </SelectElement>
                            <StaticElement 
                                content="<div></div>"
                                :columns="{ container: 3, wrapper:12}"
                                :conditions="[
                                    [
                                        [`cloro_${i + 1}`, '!=', ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i + 1}`, '!=', 'Simple'],
                                    ]
                                ]"
                                :name="`offset_${i + 1}`"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                                <StaticElement 
                                    content="<div></div>"
                                    :columns="{ container: 1, wrapper:12}"
                                    :conditions="[
                                        [`cloro_${i + 1}`, ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i + 1}`, 'Simple'],
                                    ]"
                                    :name="`offset2_${i + 1}`"
                                    v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                            <DateElement
                                :name="`fecha_muestreo_${i + 1}`"
                                :columns="{ container: 2, wrapper: 12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo_${i + 1}`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de muestreo ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <DateElement
                                :name="`fecha_final_muestreo_${i + 1}`"
                                :add-class="{
                                    ElemenText: {
                                        container: 'my-own-class'
                                    }
                                }"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, offset:4, wrapper: 12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de fin de muestreo ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_final_muestreo_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de fin muestreo ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <DateElement
                                :name="`fecha_composicion_${i + 1}`"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, offset:4, wrapper: 12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de composicion ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_composicion_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de composicion ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_1_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 1 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_2_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 2 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_3_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 3 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_4_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 4 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_5_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 5 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_6_${i + 1}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i + 1}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 6 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros_${i + 1}`"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                            <TextareaElement 
                                :name="`otros_${i + 1}`"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                :conditions="[
                                    [`parametros_${i + 1}`, 'Otro']
                                ]"
                                >
                                <template #before>
                                    <div class="text-sm">{{ `Otros ${i + 1}` }}</div>
                                </template>
                            </TextareaElement>
                            <RadiogroupElement
                                :name="`preservacion_correcta_${i + 1}`"
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
                                ]" v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Preservada correctamente ${i + 1}` }}</div>
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