<script setup>
    import { ref, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
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
        router.post(`/food_samples?inicio_muestras=${props.inicioMuestras}&numero_muestras=${props.numeroMuestras}&id_orden=${props.order.id}`, vueFormData);
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
                            :label="`MFQ-${order.folio} - ${i}`"
                            :elements="[
                                `tipo_muestra_${i}`, 
                                `identificacion_muestra_${i}`, 
                                `caracteristicas_${i}`,
                                `muestreador_${i}`,
                                `tipo_muestreo_${i}`,
                                `peso_${i}`,
                                `temperatura_${i}`,
                                `mostrar_coordenadas_${i}`,
                                `latitud_label_${i}`,
                                `latitud_grados_${i}`,
                                `latitud_minutos_${i}`,
                                `latitud_segundos_${i}`,
                                `latitud_orientacion_${i}`,
                                `longitud_label_${i}`,
                                `longitud_grados_${i}`,
                                `longitud_minutos_${i}`,
                                `longitud_segundos_${i}`,
                                `longitud_orientacion_${i}`,
                                `fecha_muestreo_${i}`,
                                `hora_muestreo_${i}`,
                                `parametros_${i}`,
                                `otros_${i}`,
                                `preservacion_correcta_${i}`,
                                `offset_${i}`,
                                `offset2_${i}`,
                                'create_food_samples'
                            ]"
                            :add-class="{
                                wrapper_active: ['border-b-4', 'bg-red-100'],
                            }"
                            v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"/>
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`identificacion_muestra_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`caracteristicas_${i}`"
                                :columns="{container:6, wrapper:12}"
                                 v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
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
                                :columns="{container:2, wrapper:12}" v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Muestreador ${i}` }}</div>
                                </template> 
                            </SelectElement>
                            <StaticElement 
                                content="<div></div>"
                                :columns="{ container: 4, wrapper:12}"
                                :name="`offset_${i}`"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"/>
                            <TextElement
                                :name="`tipo_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`peso_${i}`"
                                :columns="{ container: 3, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Peso/Vol de la muestra g./l ${i}` }}</div>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`temperatura_${i}`"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Temperatura °C ${i}` }}</div>
                                </template>
                            </TextElement>
                            <CheckboxElement
                                :name="`mostrar_coordenadas_${i}`"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                Agrerar coordenadas
                            </CheckboxElement>
                            <StaticElement
                                :name="`latitud_label_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                content="<div class='font-bold text-center'>Latitud</di>"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"/>
                            <TextElement
                                :name="`latitud_grados_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="°"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_grados_${i}`]">
                                        {{ errors[`latitud_grados_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`latitud_minutos_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="'"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_minutos_${i}`]">
                                        {{ errors[`latitud_minutos_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`latitud_segundos_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder='"'
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_segundos_${i}`]">
                                        {{ errors[`latitud_segundos_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <SelectElement
                                :name="`latitud_orientacion_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                :columns="{ container:3, wrapper:12 }"
                                :items="[
                                    {label: 'Elija la orientacion', value: null},
                                    'Norte',
                                    'Sur'
                                ]"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_orientacion_${i}`]">
                                        {{ errors[`latitud_orientacion_${i}`] }}
                                    </p>
                                </template>
                            </SelectElement>
                            <StaticElement
                                :name="`longitud_label_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                content="<div class='font-bold text-center'>Longitud</di>"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"/>
                            <TextElement
                                :name="`longitud_grados_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="°"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_grados_${i}`]">
                                        {{ errors[`longitud_grados_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`longitud_minutos_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="'"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_minutos_${i}`]">
                                        {{ errors[`longitud_minutos_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`longitud_segundos_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder='"'
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_segundos_${i}`]">
                                        {{ errors[`longitud_segundos_${i}`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <SelectElement
                                :name="`longitud_orientacion_${i}`"
                                :conditions="[[`mostrar_coordenadas_${i}`,true]]"
                                :columns="{ container:3, wrapper:12 }"
                                :items="[
                                    {label: 'Elija la orientacion', value: null},
                                    'Este (E)',
                                    'Oeste (W)'
                                ]"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_orientacion_${i}`]">
                                        {{ errors[`longitud_orientacion_${i}`] }}
                                    </p>
                                </template>
                            </SelectElement>
                            <DateElement
                                :name="`fecha_muestreo_${i}`"
                                :columns="{ container: 2, wrapper: 12 }"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"
                                display-format="MMMM DD, YYYY">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo ${i}` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo_${i}`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de muestreo ${i}` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros_${i}`"
                                :columns="{ container:12, wrapper:12 }"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)">
                                <template #before>
                                    <p class="text-sm">{{ `Parametros ${i}` }}</p>
                                </template>
                            </SelectElement>
                            <TextareaElement 
                                :name="`otros_${i}`"
                                v-for="i in createRange(inicioMuestras + 1, inicioMuestras + numeroMuestras)"
                                :conditions="[
                                    [`parametros_${i}`, 'Otro']
                                ]"
                                >
                                <template #before>
                                    <div class="text-sm">{{ `Otros ${i}` }}</div>
                                </template>
                            </TextareaElement>
                        <ButtonElement 
                            submits
                            name="create_food_samples"
                            class="mt-3">
                            Crear
                        </ButtonElement>
                    </FormElements>
                </template>
            </Vueform>
        </div> 
    </AuthenticatedLayout>
</template>