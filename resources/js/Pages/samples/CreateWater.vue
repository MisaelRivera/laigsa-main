<script setup>
    import { ref, reactive, computed } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
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

    const page = usePage();
    console.log(props.numeroMuestras);
    const oldParams = [
        "NOM-001-SEMARNAT-2021", "NOM-001-SEMARNAT-2021- incluir DBO5, Solidos Sedimentables, Materia Flotante, Coliformes Fecales", "Nom-001-semarnat-1996", "Nom-001-semarnat-1996/color verd, cloruros, e. coli, enterococos fecales. Contratar toxicidad vibrio fisheri,  cot", "Nom-001-semarnat-1996/sin met y cn", "NOM-127-SSA1-2021 Norma completa", "NOM-127-SSA1-2021, Parte de la Norma",  "Nom-127-ssa1-1994. Parte de la norma", "Nom-127-ssa1-1994. Parte de la norma/con olor y sabor", "Nom-127-ssa1-1994. Norma completa/con olor y sabor", "Nom-002-semarnat-1996", "Nom-003-semarnat-1996", "CT, As, Pb, Fluor", "CF, CT (purificada)", "CT (purificada)", "Salmonella. Contratar toxicidad", "Dureza, alcalinidad, ph, conductividad, metales.",  "E. Coli, cf, ct de nom-127-ssa1-1994.",  "Mesofilicos aerobios",  "Ph, cn",  "Sst, ss, dqo, ntk, nitratos, nitritos, fosforo total, nitrogeno total",  "Nom-004-semarnat-2002",  "Nom-004: ph, conductividad, sulfatos, nitratos, cloruros, dt, sdt, cf, ca, na, k",  "Nom-127: cn",  "Nom-127-ssa1-1994/ contratar: btex, trihalometanos, fenoles, yodo residual",  "Ph, cn", "Nueva"
    ];
    const params = props.order.v_libreta_resultados ? parametersProp.map(parameter => parameter.parametro):oldParams;

    const handleSubmit = () =>{};
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle
                title="Crear muestras"
                :ownLink="`/muestras/create/${order.folio}/${numeroMuestras}`"
                :backLink="'/orders'"/>
            <Vueform
                :endpoint="false"
                @submit="handleSubmit"
                :columns="{ container: 12, wrapper: 12 }"
                :scroll-to-invalid="false">
                <template #empty>
                    <FormTabs>
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
                            ]"
                            v-for="i in createRange(0, numeroMuestras)"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i + 1}`"
                                :before="`Tipo de muestra ${i + 1}`"
                                rules="required"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(0, numeroMuestras)"/>
                            <TextElement 
                                :name="`identificacion_muestra_${i + 1}`"
                                :before="`Identificacion de muestra ${i + 1}`"
                                rules="required"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(0, numeroMuestras)"/>
                            <TextElement 
                                :name="`caracteristicas_${i + 1}`"
                                :before="`Caracteristicas ${i + 1}`"
                                rules="required"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(0, numeroMuestras)"/>
                            <SelectElement 
                                :name="`muestreador_${i + 1}`"
                                :before="`Muestreador ${i + 1}`"
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
                                :columns="{container:2, wrapper:12}" v-for="i in createRange(0, numeroMuestras)"/>
                            <TextElement 
                                :name="`ph_${i + 1}`"
                                :before="`pH ${i + 1}`"
                                rules="required"
                                :columns="{container:1, wrapper:12}"
                                 v-for="i in createRange(0, numeroMuestras)"/>
                            <CheckboxElement
                                :name="`tratada_biologicamente_${i + 1}`">
                                Tratada biologicamente
                            </CheckboxElement>
                            <RadiogroupElement
                                :before="`Cloro ${i + 1}`"
                                :name="`cloro_${i + 1}`"
                                :conditions="[
                                    [`tipo_muestra_${i + 1}`, ['Ag']]
                                ]"
                                :items="[
                                    'Presente',
                                    'Ausente',
                                    'Laboratorio',
                                    'N/A',
                                ]" v-for="i in createRange(0, numeroMuestras)"/>
                    </FormElements>
                </template>
            </Vueform>
        </div>
        
    </AuthenticatedLayout>
</template>