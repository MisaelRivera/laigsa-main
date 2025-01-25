<script setup>
    import { ref, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper.js';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';


    const props = defineProps({
        foodSample: Object,
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
        router.put(`/food_samples`, vueFormData);
    };
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle
                title="Editar muestra"
                :ownLink="`/food_samples/${foodSample.id}/edit`"
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
                            :name="`muestra`"
                            :label="`MFQ-${foodSample.orden.folio} - ${foodSample.numero_muestra}`"
                            :elements="[
                                `tipo_muestra`, 
                                `identificacion_muestra`, 
                                `caracteristicas`,
                                `muestreador`,
                                `tipo_muestreo`,
                                `peso_muestra`,
                                `temperatura`,
                                `mostrar_coordenadas`,
                                `latitud_label`,
                                `latitud_grados`,
                                `latitud_minutos`,
                                `latitud_segundos`,
                                `latitud_orientacion`,
                                `longitud_label`,
                                `longitud_grados`,
                                `longitud_minutos`,
                                `longitud_segundos`,
                                `longitud_orientacion`,
                                `fecha_muestreo`,
                                `hora_muestreo`,
                                `parametros`,
                                `otros`,
                                `preservacion_correcta`,
                                `offset`,
                                `offset2`,
                                'create_food_samples'
                            ]"
                            :add-class="{
                                wrapper_active: ['border-b-4', 'bg-red-100'],
                            }"
                            />
                    </FormTabs>
                    <FormElements>
                            <TextElement 
                                :name="`tipo_muestra`"
                                :columns="{container:6, wrapper:12}"
                                 
                                :default="foodSample.tipo_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Tipo de muestra` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`identificacion_muestra`"
                                :columns="{container:6, wrapper:12}"
                                 
                                 :default="foodSample.identificacion_muestra">
                                 <template #before>
                                    <div class="text-sm">{{ `Identificacion de muestra` }}</div>
                                </template>
                            </TextElement>
                            <TextElement 
                                :name="`caracteristicas`"
                                :columns="{container:6, wrapper:12}"
                                 
                                 :default="foodSample.caracteristicas">
                                 <template #before>
                                    <div class="text-sm">{{ `Caracteristicas` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement 
                                :name="`muestreador`"
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
                                :columns="{container:2, wrapper:12}" 
                                :default="foodSample.muestreador">
                                <template #before>
                                    <div class="text-sm">{{ `Muestreador` }}</div>
                                </template> 
                            </SelectElement>
                            <StaticElement 
                                content="<div></div>"
                                :columns="{ container: 4, wrapper:12}"
                                :name="`offset`"
                                />
                            <TextElement
                                :name="`tipo_muestreo`"
                                :columns="{ container: 2, wrapper: 12}"
                                
                                :default="foodSample.tipo_muestreo">
                                <template #before>
                                    <div class="text-sm">{{ `Tipo de muestreo` }}</div>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`peso_muestra`"
                                :columns="{ container: 3, wrapper: 12}"
                                :default="foodSample.peso_muestra">
                                <template #before>
                                    <div class="text-sm">{{ `Peso/Vol de la muestra g./l` }}</div>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`temperatura`"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="foodSample.temperatura">
                                <template #before>
                                    <div class="text-sm">{{ `Temperatura °C` }}</div>
                                </template>
                            </TextElement>
                            <CheckboxElement
                                :name="`mostrar_coordenadas`"
                                :default="foodSample.latitud !== 'N/A'"
                                >
                                Agrerar coordenadas
                            </CheckboxElement>
                            <StaticElement
                                :name="`latitud_label`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                content="<div class='font-bold text-center'>Latitud</di>"
                                :columns="{ container:12, wrapper:12 }"
                                />
                            <TextElement
                                :name="`latitud_grados`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="°"
                                :default="foodSample.latitud !== 'N/A' ? foodSample.latitud_grados:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_grados`]">
                                        {{ errors[`latitud_grados`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`latitud_minutos`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="'"
                                :default="foodSample.latitud !== 'N/A' ? foodSample.latitud_minutos:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_minutos`]">
                                        {{ errors[`latitud_minutos`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`latitud_segundos`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder='"'
                                :default="foodSample.latitud !== 'N/A' ? foodSample.latitud_segundos:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_segundos`]">
                                        {{ errors[`latitud_segundos`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <SelectElement
                                :name="`latitud_orientacion`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                :columns="{ container:3, wrapper:12 }"
                                :items="[
                                    {label: 'Elija la orientacion', value: null},
                                    'Norte',
                                    'Sur'
                                ]"
                                :default="foodSample.latitud !== 'N/A' ? foodSample.latitud_orientacion:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`latitud_orientacion`]">
                                        {{ errors[`latitud_orientacion`] }}
                                    </p>
                                </template>
                            </SelectElement>
                            <StaticElement
                                :name="`longitud_label`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                content="<div class='font-bold text-center'>Longitud</di>"
                                :columns="{ container:12, wrapper:12 }"
                                />
                            <TextElement
                                :name="`longitud_grados`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="°"
                                :default="foodSample.longitud !== 'N/A' ? foodSample.longitud_grados:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_grados`]">
                                        {{ errors[`longitud_grados`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`longitud_minutos`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder="'"
                                :default="foodSample.longitud !== 'N/A' ? foodSample.longitud_minutos:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_minutos`]">
                                        {{ errors[`longitud_minutos`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <TextElement
                                :name="`longitud_segundos`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                input-type="number"
                                :columns="{ container:3, wrapper:12 }"
                                placeholder='"'
                                :default="foodSample.longitud !== 'N/A' ? foodSample.longitud_segundos:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_segundos`]">
                                        {{ errors[`longitud_segundos`] }}
                                    </p>
                                </template>
                            </TextElement>
                            <SelectElement
                                :name="`longitud_orientacion`"
                                :conditions="[[`mostrar_coordenadas`,true]]"
                                :columns="{ container:3, wrapper:12 }"
                                :items="[
                                    {label: 'Elija la orientacion', value: null},
                                    'Este (E)',
                                    'Oeste (W)'
                                ]"
                                :default="foodSample.longitud !== 'N/A' ? foodSample.longitud_orientacion:null">
                                <template #description>
                                    <p 
                                        class="text-red-500"
                                        v-if="errors[`longitud_orientacion`]">
                                        {{ errors[`longitud_orientacion`] }}
                                    </p>
                                </template>
                            </SelectElement>
                            <DateElement
                                :name="`fecha_muestreo`"
                                :columns="{ container: 2, wrapper: 12 }"
                                display-format="MMMM DD, YYYY"
                                :default="foodSample.fecha_muestreo">
                                <template #before>
                                    <div class="text-sm">{{ `Fecha de muestreo` }}</div>
                                </template>
                            </DateElement>
                            <TextElement 
                                :name="`hora_muestreo`"
                                input-type="time"
                                :columns="{ container: 2, wrapper: 12}"
                                :default="foodSample.hora_muestreo">
                                <template #before>
                                    <div class="text-sm">{{ `Hora de muestreo` }}</div>
                                </template>
                            </TextElement>
                            <SelectElement
                                :items="oldParams"
                                :name="`parametros`"
                                :columns="{ container:12, wrapper:12 }"
                                :default="foodSample.otros_parametros ? 'Otro':foodSample.parametros">
                                <template #before>
                                    <p class="text-sm">{{ `Parametros` }}</p>
                                </template>
                            </SelectElement>
                            <TextareaElement 
                                :name="`otros`"
                                :default="foodSample.otros_parametros ? foodSample.parametros:''"
                                :conditions="[
                                    [`parametros`, 'Otro']
                                ]"
                                >
                                <template #before>
                                    <div class="text-sm">{{ `Otros` }}</div>
                                </template>
                            </TextareaElement>
                        <ButtonElement 
                            submits
                            name="create_food_samples"
                            class="mt-3">
                            Editar
                        </ButtonElement>
                    </FormElements>
                </template>
            </Vueform>
        </div> 
    </AuthenticatedLayout>
</template>