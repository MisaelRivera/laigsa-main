<script setup>
    import { ref } from 'vue';
    import { useForm, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const props = defineProps({
        sample: Object,
        identificacionesMuestras: Object,
        errors: Object,
    });

    const formState = useForm({
        tipo_muestra: '', id_identificacion_muestra: null,
    });

    const muestreadores = [
        {value: null, label: 'Elija un muestreador'},
        'RCHH',
        'JHM',
        'FESR',
        'JPMS',
        'Cliente',
        'CESAVEDAC',
        'QSM',
        'APPC',
        'LMQH',
    ];

    const cloro = [
        'Presente',
        'Ausente',
        'Laboratorio',
        'N/A'
    ];

    const tipoMuestreos = [
        'Simple',
        {value: 'Compuesto_4', label: 'Compuesto 4'},
        {value: 'Compuesto_6', label: 'Compuesto 6'},
        'N/A'
    ];

    const params = [
        { value: null, label: 'Elija un parametro' },
        "NOM-001-SEMARNAT-2021", "NOM-001-SEMARNAT-2021- incluir DBO5, Solidos Sedimentables, Materia Flotante, Coliformes Fecales", "Nom-001-semarnat-1996", "Nom-001-semarnat-1996/color verd, cloruros, e. coli, enterococos fecales. Contratar toxicidad vibrio fisheri,  cot", "Nom-001-semarnat-1996/sin met y cn", "NOM-127-SSA1-2021 Norma completa", "NOM-127-SSA1-2021, Parte de la Norma",  "Nom-127-ssa1-1994. Parte de la norma", "Nom-127-ssa1-1994. Parte de la norma/con olor y sabor", "Nom-127-ssa1-1994. Norma completa/con olor y sabor", "Nom-002-semarnat-1996", "Nom-003-semarnat-1996", "CT, As, Pb, Fluor", "CF, CT (purificada)", "CT (purificada)", "Salmonella. Contratar toxicidad", "Dureza, alcalinidad, ph, conductividad, metales.",  "E. Coli, cf, ct de nom-127-ssa1-1994.",  "Mesofilicos aerobios",  "Ph, cn",  "Sst, ss, dqo, ntk, nitratos, nitritos, fosforo total, nitrogeno total",  "Nom-004-semarnat-2002",  "Nom-004: ph, conductividad, sulfatos, nitratos, cloruros, dt, sdt, cf, ca, na, k",  "Nom-127: cn",  "Nom-127-ssa1-1994/ contratar: btex, trihalometanos, fenoles, yodo residual",  "Ph, cn", "Otro"
    ];

    const handleEdit = (form$) => {
       router.put(`/water_samples/${props.sample.id}`, form$.requestData);
    };

    console.log(props.sample)
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto mt-3">
            <CreateTitle
                :title="`Editar muestra ${sample.orden.folio } - ${sample.numero_muestra}`"
                :own-link="route('water_samples.edit', sample.id)"
                />
            <Vueform
                :endpoint="false"
                @submit="handleEdit"
                :scroll-to-invalid="false"
                :columns="{container:12, wrapper:12}">
                <TextElement
                    name="tipo_muestra"
                    :columns="{container: 6, wrapper:12}"
                    :default="sample.tipo_muestra">
                    <template #before>
                        <p class="text-sm">Tipo de muestra</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.tipo_muestra">
                            {{ errors.tipo_muestra }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="id_identificacion_muestra"
                    :columns="{container:6, wrapper:12}"
                    :items="identificacionesMuestras"
                    :default="sample.id_identificacion_muestra">
                    <template #before>
                        <p class="text-sm">Identificacion de muestra</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.id_identificacion_muestra">
                            {{ errors.id_identificacion_muestra }}
                        </p>
                    </template>
                </SelectElement>
                <TextElement
                    name="caracteristicas"
                    :columns="{container:6, wrapper:12}"
                    :default="sample.caracteristicas">
                    <template #before>
                        <p class="text-sm">
                            Caracteristicas
                        </p>
                    </template>
                    <template>
                        <p 
                            class="text-red-400"
                            v-if="errors.caracteristicas">
                            {{ errors.caracteristicas }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="muestreador"
                    :columns="{container:2, wrapper:12}"
                    :items="muestreadores"
                    :default="sample.muestreador">
                    <template #before>
                        <p class="text-sm">Muestreador</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.muestreador">
                            {{ errors.muestreador }}
                        </p>
                    </template>
                </SelectElement>
                <TextElement
                    name="pH"
                    :columns="{container: 1, wrapper:12}"
                    :default="sample.pH">
                    <template #before>
                        <p class="text-sm">pH</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.pH">
                            {{ errors.pH }}
                        </p>
                    </template>
                </TextElement>
                <CheckboxElement
                    name="tratada_biologicamente"
                    :columns="{container:3, wrapper:12}"
                    :add-class="{
                        wrapper: 'mt-6'
                    }"
                    :default="sample.tratada_biologicamente">
                    Tratada biologicamente
                </CheckboxElement>
                <RadiogroupElement
                    name="cloro"
                    :columns="{container:4, wrapper:12}"
                    :remove-class="{
                        wrapper: 'flex-col'
                    }"
                    :add-class="{
                        wrapper: 'gap-1'
                    }"
                    :items="cloro"
                    :default="sample.cloro">
                    <template #before>
                        <p class="text-sm">Cloro</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.cloro">
                            {{ errors.cloro }}
                        </p>
                    </template>
                </RadiogroupElement>
                <TextElement
                    name="valor_cloro"
                    input-type="number"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.valor_cloro"
                    :conditions="[
                        ['tipo_muestreo', '==', 'Simple'],
                        ['cloro', '==', ['Presente', 'Ausente']]
                    ]">
                    <template #before>
                        <p class="text-sm">Valor de cloro</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.valor_cloro">
                            {{ errors.valor_cloro }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="ph_cromo_hexavalente"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.ph_cromo_hexavalente">
                    <template #before>
                        <p class="text-sm">pH Cr VI</p>
                    </template>
                    <template #description>
                        <p 
                            class="bg-red-400"
                            v-if="errors.ph_cromo_hexavalente">
                            {{ errors.ph_cromo_hexavalente }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="tipo_muestreo"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.tipo_muestreo_show"
                    :items="tipoMuestreos">
                    <template #before>
                        <p class="text-sm">Tipo de muestreo</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.tipo_muestreo">
                            {{ errors.tipo_muestreo }}
                        </p>
                    </template>
                </SelectElement>
                <StaticElement
                    content="<div class='col-span-2'></div>"
                    name="offset_1"
                    :conditions="[
                        ['tipo_muestreo', 'Simple'],
                        ['cloro', ['Presente', 'Ausente']],
                    ]"/>
                <DateElement
                    name="fecha_muestreo"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.fecha_muestreo">
                    <template #before>
                        <p class="text-sm">Fecha de muestreo</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.fecha_muestreo">
                            {{ errors.fecha_muestreo }}
                        </p>
                    </template>
                </DateElement>
                <TextElement
                    name="hora_muestreo"
                    input-type="time"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.hora_muestreo">
                    <template #before>
                        <p class="text-sm">Hora de muestreo</p>
                    </template>
                    <template #default>
                        <p 
                            class="text-red-400"
                            v-if="errors.hora_muestreo">
                            {{ errors.hora_muestreo }}
                        </p>
                    </template>
                </TextElement>
                <DateElement
                    name="fecha_final_muestreo"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.fecha_final_muestreo">
                    <template #before>
                        <p class="text-sm">Fecha de muestreo final</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.fecha_final_muestreo">
                            {{ errors.fecha_final_muestreo }}
                        </p>
                    </template>
                </DateElement>
                <TextElement
                    name="hora_final_muestreo"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    input-type="time"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.hora_final_muestreo">
                    <template #before>
                        <p class="text-sm">Hora final de muestreo</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.hora_final_muestreo">
                            {{ errors.hora_final_muestreo }}
                        </p>
                    </template>
                </TextElement>
                <DateElement
                    name="fecha_composicion"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.fecha_composicion">
                    <template #before>
                        <p class="text-sm">Fecha de composicion</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.fecha_composicion">
                            {{ errors.fecha_composicion }}
                        </p>
                    </template>
                </DateElement>
                <TextElement
                    name="hora_composicion"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    input-type="time"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.hora_composicion">
                    <template #before>
                        <p class="text-sm">Hora de composicion</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.hora_composicion">
                            {{ errors.hora_composicion }}
                        </p>
                    </template>
                </TextElement>
                <StaticElement
                    name="offset_2"
                    content="<div class='col-span-4'></div>"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"/>
                <TextElement
                    name="flujo_1"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_1">
                    <template #before>
                        <p class="text-sm">Flujo 1</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_1">
                            {{ errors.flujo_1 }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="flujo_2"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_2">
                    <template #before>
                        <p class="text-sm">Flujo 2</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_2">
                            {{ errors.flujo_2 }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="flujo_3"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_3">
                    <template #before>
                        <p class="text-sm">Flujo 3</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_3">
                            {{ errors.flujo_3 }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="flujo_4"
                    :conditions="[
                        ['tipo_muestreo', '!=', ['Simple', 'N/A']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_4">
                    <template #before>
                        <p class="text-sm">Flujo 4</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_4">
                            {{ errors.flujo_4 }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="flujo_5"
                    :conditions="[
                        ['tipo_muestreo', ['Compuesto_6']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_5">
                    <template #before>
                        <p class="text-sm">Flujo 5</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_5">
                            {{ errors.flujo_5 }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="flujo_6"
                    :conditions="[
                        ['tipo_muestreo', ['Compuesto_6']]
                    ]"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.flujo_6">
                    <template #before>
                        <p class="text-sm">Flujo 6</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.flujo_6">
                            {{ errors.flujo_6 }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="parametros"
                    :default="sample.otros_parametros  ? 'Otro':sample.parametros"
                    :items="params">
                    <template #before>
                        <p class="text-sm">Parametros</p>
                    </template>
                    <template #description>
                        <p 
                            v-if="errors.parametros"
                            class="text-red-400">
                            {{ errors.parametros }}
                        </p>
                    </template>
                </SelectElement>
                <TextareaElement
                    name="otros"
                    :conditions="[
                        ['parametros', 'Otro']
                    ]"
                    :default="sample.parametros">
                    <template #before>
                        <p class="text-sm">
                            Otros
                        </p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.otros">
                            {{ errors.otros }}
                        </p>
                    </template>
                </TextareaElement>
                <RadiogroupElement
                    name="preservacion_correcta"
                    :columns="{container:2, wrapper:12}"
                    :items="['Si', 'No', 'N/A']"
                    :remove-class="{
                        wrapper: 'flex-col'
                    }"
                    :add-class="{
                        wrapper: 'gap-1'
                    }"
                    :default="sample.preservacion_correcta">
                    <template #before>
                        <p class="text-sm">Preservada correctamente</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="errors.preservacion_correcta">
                            {{ errors.preservacion_correcta }}
                        </p>
                    </template>
                </RadiogroupElement>
                <StaticElement
                    name="offset_3"
                    content="<div class='col-span-10'></div>"/>
                <button class="py-1 px-2 rounded text-white bg-blue-500 ">
                    Editar
                </button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>