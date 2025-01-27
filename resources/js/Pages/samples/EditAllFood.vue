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

    const form$ = ref(null);
    const tabsContainer = ref(null);
    onMounted(() => {
      // Add the class to the FormTabs container
        tabsContainer.value.$el.classList.add('overflow-x-scroll');
    });
    const identificaciones_muestra = props.order.cliente.identificaciones_muestra.map((identificacion_muestra) => {
        return { value: identificacion_muestra.id, label: identificacion_muestra.identificacion_muestra };
    });
    identificaciones_muestra.unshift({ value: null, label: 'Elija una opcion' });
    const oldParams = [
        { value: null, label: 'Elija un parametro' },
        "Mesofílicos Aerobios", "Mohos", "Levaduras", "Salmonella s.p.p.", 
        "Staphylococcus Aureus", "Coliformes Totales", "Coliformes Fecales",
        "Escherichia Coli",
        "Otro"
    ];

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
                                `identificacion_muestra_${i}`,
                                `caracteristicas_${i}`,
                                `muestreador_${i}`,
                                `offset_${i}`,
                                `tipo_muestreo_${i}`,
                                `peso_muestra_${i}`,
                                `temperatura_${i}`,
                                `offset2_${i}`,
                                `latitud_label_${i}`,
                                `latitud_grados_${i}`,
                                `latitud_minutos_${i}`,
                                `latitud_segundos_${i}`,
                                `latitud_orientacion_${i}`,
                                `longitud_grados_${i}`,
                                `longitud_minutos_${i}`,
                                `longitud_segundos_${i}`,
                                `longitud_orientacion_${i}`,
                                `fecha_muestreo_${i}`,
                                `hora_muestreo_${i}`,
                                `parametros_${i}`,
                                `otros_${i}`,
                                'edit_food_samples'
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
                            <TextElement
                                :name="`peso_muestra_${i}`"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_alimentos"
                                :default="muestra.peso_muestra">
                                <template #before>
                                    <p class="text-sm">Peso/Vol de la muestra g./l</p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`temperatura_${i}`"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_alimentos"
                                :default="muestra.temperatura">
                                <template #before>
                                    <p class="text-sm">Temperatura °C</p>
                                </template>
                            </TextElement>
                            <StaticElement 
                                content="<div></div>"
                                :columns="{ container: 5, wrapper:12}"
                                :name="`offset_${i}`"
                                v-for="(muestra, i) in order.muestras_aguas"/>
                            <CheckboxElement
                                v-for="(muestra, i) in order.muestras_aguas"
                                :name="`mostrar_coordenadas_${i}`"
                                :default="foodSample.latitud !== 'N/A'">
                                Agregar coordenadas
                            </CheckboxElement>
                            <StaticElement
                                v-for="(muestra, i) in order.muestras_aguas"
                                :name="`latitud_label_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                :content="`<div class='font-bold text-center'>Latitud ${i + 1}</div>`"
                                :columns="{ container:12, wrapper:12 }"/>
                            <TextElement
                                :name="`latitud_grados_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.latitud_grados"
                                placeholder="°"/>
                            <TextElement
                                :name="`latitud_minutos_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.latitud_minutos"
                                placeholder="'"/>
                            <TextElement
                                :name="`latitud_segundos_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.latitud_segundos"
                                placeholder='"'/>
                            <SelectElement
                                :name="`latitud_orientacion_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.latitud_orientacion"
                                :items="[
                                    {label:'Elija una orientacion', value:null},
                                    'Norte',
                                    'Sur',
                                ]"/>
                            <StaticElement
                                v-for="(muestra, i) in order.muestras_aguas"
                                :name="`longitud_label_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                :content="`<div class='font-bold text-center'>longitud ${i + 1}</div>`"
                                :columns="{ container:12, wrapper:12 }"/>
                            <TextElement
                                :name="`longitud_grados_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.longitud_grados"
                                placeholder="°"/>
                            <TextElement
                                :name="`longitud_minutos_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.longitud_minutos"
                                placeholder="'"/>
                            <TextElement
                                :name="`longitud_segundos_${i}`"
                                input-type="number"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.longitud_segundos"
                                placeholder='"'/>
                            <SelectElement
                                :name="`longitud_orientacion_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`, true]]"
                                :columns="{container:3, wrapper:12}"
                                v-for="(muestra, i) in order.muestras_aguas"
                                :default="muestra.longitud_orientacion"
                                :items="[
                                    {label:'Elija una orientacion', value:null},
                                    'Este (E)',
                                    'Oeste (W)',
                                ]"/>
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
                        <ButtonElement 
                            submits
                            name="edit_food_samples"
                            class="mt-3">
                            Editar
                        </ButtonElement>
                    </FormElements>
                </template>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>