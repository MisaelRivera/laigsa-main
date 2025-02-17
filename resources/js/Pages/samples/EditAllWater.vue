<script setup>
    import { ref, onMounted, reactive } from 'vue';
    import { router, usePage, useForm } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper.js';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import { Validator } from '@vueform/vueform';


    const props = defineProps({
        order: Object,
        parametersProp: Array,
        errors: Object,
    });

    const form = reactive({});
    const form$ = ref(null);
    const tabsContainer = ref(null);
    onMounted(() => {
      // Add the class to the FormTabs container
        tabsContainer.value.$el.classList.add('overflow-x-scroll');
    });
    const identificaciones_muestra = props.order.cliente.identificaciones_muestra.map((identificacion_muestra) => {
        return { value: identificacion_muestra.id, label: identificacion_muestra.identificacion_muestra };
    });
    identificaciones_muestra.unshift({ value: null, label: 'Elija una opcion' })
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
        console.log(vueFormData);
        router.put(`/water_samples/update_all/${props.order.id}`, vueFormData);
    };
    console.log(props.order.muestras);
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle
                title="Editar muestras"
                :ownLink="`/muestras/create/${order.folio}/`"
                :backLink="'/orders'"/>
            <p 
                v-for="key in Object.keys(errors)"
                class="text-red-500">
                {{ errors[key] }}
            </p>
            <Vueform
                :endpoint="false"
                @submit="handleSubmit"
                ref="form$"
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
                            :label="`MFQ-${order.folio} - ${i + 1}`"
                            :elements="[
                                `tipo_muestra_${i}`, 
                                `id_identificacion_muestra_${i}`,
                                `caracteristicas_${i}`,
                                `muestreador_${i}`,
                                `ph_${i}`,
                                `tratada_biologicamente_${i}`,
                                `cloro_${i}`,
                                `valor_cloro_${i}`,
                                `ph_cromo_hexavalente_${i}`,
                                `tipo_muestreo_${i}`,
                                `fecha_muestreo_${i}`,
                                `hora_muestreo_${i}`,
                                `fecha_final_muestreo_${i}`,
                                `hora_final_muestreo_${i}`,
                                `fecha_composicion_${i}`,
                                `hora_composicion_${i}`,
                                `flujo_1_${i}`,
                                `flujo_2_${i}`,
                                `flujo_3_${i}`,
                                `flujo_4_${i}`,
                                `flujo_5_${i}`,
                                `flujo_6_${i}`,
                                `parametros_${i}`,
                                `otros_${i}`,
                                `preservacion_correcta_${i}`,
                                `offset_${i}`,
                                `offset2_${i}`,
                                'create_water_samples'
                            ]"
                            :add-class="{
                                wrapper_active: ['border-b-4', 'bg-red-100'],
                            }"
                            v-for="(muestra, i) in order.muestras_aguas"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.tipo_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`id_identificacion_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="(muestra, i) in order.muestras_aguas"
                                 :items="identificaciones_muestra"
                                 :default="muestra.id_identificacion_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra ${i + 1}` }}</div>
                                </template>
                            </SelectElement>
                            <TextElement 
                                :name="`caracteristicas_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="(muestra, i) in order.muestras_aguas"
                                 :default="muestra.caracteristicas">
                                 <template #before>
                                    <div class="text-sm">{{ `Caracteristicas ${i + 1}` }}</div>
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
                                :default="muestra.muestreador"
                                :columns="{container:2, wrapper:12}" v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Muestreador ${i + 1}` }}</div>
                                </template> 
                            </SelectElement>
                            <TextElement 
                                :name="`ph_${i}`"
                                :columns="{container:1, wrapper:12}"
                                 v-for="(muestra, i) in order.muestras_aguas"
                                 :default="muestra.pH">
                                 <template #before>
                                    <div class="text-sm">{{ `pH ${i + 1}` }}</div>
                                </template> 
                            </TextElement>
                            <CheckboxElement
                                :name="`tratada_biologicamente_${i}`"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :columns="{ container:3, wrapper: 12 }"
                                :default="muestra.tratada_biologicame">
                                <p class="text-sm">Tratada biologicamente {{ i  + 1}}</p>
                            </CheckboxElement>
                            <RadiogroupElement
                                :name="`cloro_${i}`"
                                :default="muestra.cloro"
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
                                ]" v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Cloro ${i + 1}` }}</div>
                                </template>    
                            </RadiogroupElement>
                            <TextElement 
                                :name="`valor_cloro_${i}`"
                                :default="muestra.valor_cloro"
                                :columns="{ container:2, wrapper:12 }"
                                :conditions="[
                                    [`cloro_${i}`, ['Presente', 'Ausente']],
                                    [`tipo_muestreo_${i}`, 'Simple']
                                ]"
                                default="N/A"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Valor del cloro ${i + 1}` }}</div>
                                </template>   
                            </TextElement>
                            <TextElement 
                                :name="`ph_cromo_hexavalente_${i}`"
                                :default="muestra.ph_cromo_hexavalente"
                                :rules="[phRange]"
                                :columns="{ container:2, wrapper:12 }"
                                default="N/A"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `pH Cr VI ${i + 1}` }}</div>
                                </template>   
                            </TextElement>
                            <SelectElement
                                :name="`tipo_muestreo_${i}`"
                                :default="muestra.tipo_muestreo"
                                :columns="{ container: 2, wrapper: 12}"
                                :items="tiposMuestreo"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo ${i + 1}` }}</div>
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
                                v-for="(muestra, i) in order.muestras_aguas"/>
                                <StaticElement 
                                    content="<div></div>"
                                    :columns="{ container: 1, wrapper:12}"
                                    :conditions="[
                                        [`cloro_${i}`, ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i}`, 'Simple'],
                                    ]"
                                    :name="`offset2_${i}`"
                                    v-for="(muestra, i) in order.muestras_aguas"/>
                            <DateElement
                                :name="`fecha_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12 }"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.fecha_muestreo"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo_${i}`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.hora_muestreo">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de muestreo ${i + 1}` }}</div>
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
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.fecha_final_muestreo"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de fin de muestreo ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_final_muestreo_${i}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.hora_final_muestreo">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de fin muestreo ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <DateElement
                                :name="`fecha_composicion_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, offset:4, wrapper: 12 }"
                                v-for="(muestra, i) in order.muestras_aguas"
                                display-format="MMMM DD, YYYY"
                                :default="muestra.fecha_composicion">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de composicion ${i + 1}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_composicion_${i}`"
                                input-type="time"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.hora_composicion">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de composicion ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_1_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_1"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 1 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_2_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_2"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 2 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_3_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_3"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 3 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_4_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_4', 'Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_4"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 4 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_5_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_5"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 5 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`flujo_6_${i}`"
                                :conditions="[
                                    [`tipo_muestreo_${i}`, ['Compuesto_6']]
                                ]"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="muestra.folio_6"
                                v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 6 (l/s) ${i + 1}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros_${i}`"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.otros_parametros ? 'Otro':muestra.parametros"/>
                            <TextareaElement 
                                :name="`otros_${i}`"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :conditions="[
                                    [`parametros_${i}`, 'Otro']
                                ]"
                                :default="muestra.otros_parametros ? muestra.parametros:''">
                                <template #before>
                                    <div class="text-sm">{{ `Otros ${i + 1}` }}</div>
                                </template>
                            </TextareaElement>
                            <RadiogroupElement
                                :name="`preservacion_correcta_${i}`"
                                :columns="{ container: 4, wrapper:12 }"
                                :default="muestra.preservacion_correcta"
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
                                ]" v-for="(muestra, i) in order.muestras_aguas">
                                <template #before>
                                    <div class="text-sm">{{ `Preservada correctamente ${i + 1}` }}</div>
                                </template>    
                            </RadiogroupElement>
                        <ButtonElement 
                            submits
                            name="create_water_samples"
                            class="mt-3">
                            Editar
                        </ButtonElement>
                    </FormElements>
                </template>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>