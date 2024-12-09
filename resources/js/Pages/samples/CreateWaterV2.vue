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
        parametersProp: Array,
        errors: Object,
    });
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

    const params = props.order.v_libreta_resultados ? props.parametersProp.map(parameter => parameter.parametro):oldParams;

    const handleSubmit = (form$, FormData) => {
        const vueFormData = form$.requestData;
        router.post(`/water_samples?inicio_muestras=${props.inicioMuestras}&numero_muestras=${props.numeroMuestras}&id_orden=${props.order.id}`, vueFormData);
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
                            :name="`muestra_${i}`"
                            :label="`Muestra ${order.folio} - ${i}`"
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
                            v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`id_identificacion_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                 :items="identificaciones_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra ${i}` }}</div>
                                </template>
                            </SelectElement>
                            <TextElement 
                                :name="`caracteristicas_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                :columns="{container:2, wrapper:12}" v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Muestreador ${i}` }}</div>
                                </template> 
                            </SelectElement>
                            <TextElement 
                                :name="`ph_${i}`"
                                :columns="{container:1, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `pH ${i}` }}</div>
                                </template> 
                            </TextElement>
                            <CheckboxElement
                                :name="`tratada_biologicamente_${i}`"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
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
                                ]" v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Valor del cloro ${i}` }}</div>
                                </template>   
                            </TextElement>
                            <TextElement 
                                :name="`ph_cromo_hexavalente_${i}`"
                                :columns="{ container:2, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `pH Cr VI ${i}` }}</div>
                                </template>   
                            </TextElement>
                            <SelectElement
                                :name="`tipo_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12}"
                                :items="tiposMuestreo"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo ${i}` }}</div>
                                </template>
                            </SelectElement>
                            <TextElement 
                                :name="`conductividad_${i}`"
                                :columns="{ container:2, wrapper:12 }"
                                :conditions="[
                                    [`conductividad_na_${i}`, false],
                                ]"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Conductividad ${i}` }}</div>
                                </template>   
                            </TextElement>
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                                <StaticElement 
                                    content="<div></div>"
                                    :columns="{ container: 1, wrapper:12}"
                                    :conditions="[
                                        [`cloro_${i}`, ['Presente', 'Ausente']],
                                        [`tipo_muestreo_${i}`, 'Simple'],
                                    ]"
                                    :name="`offset2_${i}`"
                                    v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                            <DateElement
                                :name="`fecha_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo ${i}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo_${i}`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
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
                                v-for="i in createRange(inicioMuestras, numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Flujo 6 (l/s) ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros_${i}`"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"/>
                            <TextareaElement 
                                :name="`otros_${i}`"
                                v-for="i in createRange(inicioMuestras, numeroMuestras)"
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
                                ]" v-for="i in createRange(inicioMuestras, numeroMuestras)">
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