<script setup>
    import { ref, onMounted } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const props = defineProps({
        sample: Object,
        identificacionesMuestras: Object
    });

    const formEl = ref(null);

    const formState = useForm({
        tipo_muestra: null,
        id_identificacion_muestra: null,
        caracteristicas: null,
        muestreador: null,
        pH: null,
        tratada_biologicamente: false,
        cloro: null,
        ph_cromo_hexavalente: 'N/A',
        tipo_muestreo: null,
        fecha_muestreo: null,
        hora_muestreo: null,
        parametros: null,
        preservacion_correcta: null
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

    const handleEdit = () => {
        

    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle
                :title="`Editar muestra ${sample.orden.folio } - ${sample.numero_muestra}`"
                :own-link="route('water_samples.edit', sample.id)"
                />
            <Vueform
                ref="formEl"
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
                            v-if="formState.errors.tipo_muestra">
                            {{ formState.errors.tipo_muestra }}
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
                            v-if="formState.errors.id_identificacion_muestra">
                            {{ formState.errors.id_identificacion_muestra }}
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
                            v-if="formState.errors.caracteristicas">
                            {{ formState.errors.caracteristicas }}
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
                            v-if="formState.errors.muestreador">
                            {{ formState.errors.muestreador }}
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
                            v-if="formState.errors.pH">
                            {{ formState.errors.pH }}
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
                            v-if="formState.errors.cloro">
                            {{ formState.errors.cloro }}
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
                            v-if="formState.errors.valor_cloro">
                            {{ formState.errors.valor_cloro }}
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
                            v-if="formState.errors.ph_cromo_hexavalente">
                            {{ formState.errors.ph_cromo_hexavalente }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="tipo_muestreo"
                    :columns="{container:2, wrapper:12}"
                    :default="sample.tipo_muestreo"
                    :items="tipoMuestreos">
                    <template #before>
                        <p class="text-sm">Tipo de muestreo</p>
                    </template>
                    <template #description>
                        <p 
                            class="text-red-400"
                            v-if="formState.errors.tipo_muestreo">
                            {{ formState.errors.tipo_muestreo }}
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
                            v-if="formState.errors.fecha_muestreo">
                            {{ formState.errors.fecha_muestreo }}
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
                            v-if="formState.errors.hora_muestreo">
                            {{ formState.errors.hora_muestreo }}
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
                            v-if="formState.errors.fecha_final_muestreo">
                            {{ formState.errors.fecha_final_muestreo }}
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
                            v-if="formState.errors.hora_final_muestreo">
                            {{ formState.errors.hora_final_muestreo }}
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
                            v-if="formState.errors.fecha_composicion">
                            {{ formState.errors.fecha_composicion }}
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
                            v-if="formState.errors.hora_composicion">
                            {{ formState.errors.hora_composicion }}
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
                            v-if="formState.errors.flujo_1">
                            {{ formState.errors.flujo_1 }}
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
                            v-if="formState.errors.flujo_2">
                            {{ formState.errors.flujo_2 }}
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
                            v-if="formState.errors.flujo_3">
                            {{ formState.errors.flujo_3 }}
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
                            v-if="formState.errors.flujo_4">
                            {{ formState.errors.flujo_4 }}
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
                            v-if="formState.errors.flujo_5">
                            {{ formState.errors.flujo_5 }}
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
                            v-if="formState.errors.flujo_6">
                            {{ formState.errors.flujo_6 }}
                        </p>
                    </template>
                </TextElement>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>