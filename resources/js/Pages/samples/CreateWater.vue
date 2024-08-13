<script setup>
    import { ref, reactive, computed } from 'vue';
    import { Alert, Radio, Checkbox, RadioGroup, Textarea, Button, FormItem, Input, InputNumber } from 'ant-design-vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { createRange } from '@/helpers/time_helper.js';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';

    const props = defineProps({
        order: Object,
        numeroMuestras: Number,
        inicioMuestras: Number,
        identificacionesMuestra: Array,
        parametersProp: Array,
        errors: Object,
    });

    const page = usePage();

    const oldParams = [
        "NOM-001-SEMARNAT-2021", "NOM-001-SEMARNAT-2021- incluir DBO5, Solidos Sedimentables, Materia Flotante, Coliformes Fecales", "Nom-001-semarnat-1996", "Nom-001-semarnat-1996/color verd, cloruros, e. coli, enterococos fecales. Contratar toxicidad vibrio fisheri,  cot", "Nom-001-semarnat-1996/sin met y cn", "NOM-127-SSA1-2021 Norma completa", "NOM-127-SSA1-2021, Parte de la Norma",  "Nom-127-ssa1-1994. Parte de la norma", "Nom-127-ssa1-1994. Parte de la norma/con olor y sabor", "Nom-127-ssa1-1994. Norma completa/con olor y sabor", "Nom-002-semarnat-1996", "Nom-003-semarnat-1996", "CT, As, Pb, Fluor", "CF, CT (purificada)", "CT (purificada)", "Salmonella. Contratar toxicidad", "Dureza, alcalinidad, ph, conductividad, metales.",  "E. Coli, cf, ct de nom-127-ssa1-1994.",  "Mesofilicos aerobios",  "Ph, cn",  "Sst, ss, dqo, ntk, nitratos, nitritos, fosforo total, nitrogeno total",  "Nom-004-semarnat-2002",  "Nom-004: ph, conductividad, sulfatos, nitratos, cloruros, dt, sdt, cf, ca, na, k",  "Nom-127: cn",  "Nom-127-ssa1-1994/ contratar: btex, trihalometanos, fenoles, yodo residual",  "Ph, cn", "Nueva"
    ];
    const params = props.order.v_libreta_resultados ? parametersProp.map(parameter => parameter.parametro):oldParams;

    const conductividadNa = [];
    const hasValorCloros = [];
    let formState = [];
    const muestreoCompuesto = [];
    const muestreoCompuesto6 = [];
    for (let i = 0; i < props.numeroMuestras; i++) {
        conductividadNa[i] = ref(false);
        formState[i] = reactive({
            tipo_muestra:  '',
            id_identificacion_muestra: null,
            caracteristicas: '',
            muestreador: null,
            pH: 'N/A',
            tratada_biologicamente: false,
            cloro: 'N/A',
            valor_cloro: 'N/A',
            ph_cromo_hexavalente: 'N/A',
            tipo_muestreo: null,
            conductividad: '',
            fecha_muestreo: '',
            hora_muestreo:'',
            fecha_final_muestreo:null,
            hora_final_muestreo:null,
            fecha_composicion:null,
            hora_composicion:null,
            flujo_1:null,
            flujo_2:null,
            flujo_3:null,
            flujo_4:null,
            flujo_5:null,
            flujo_6:null,
            parametros: '',
            otros_parametros: null,
            preservacion_correcta: 'Si',
        });

        muestreoCompuesto.push(computed(() => {
            return formState[i].tipo_muestreo === 'Compuesto_4' || formState[i].tipo_muestreo === 'Compuesto_6';
        }));

       muestreoCompuesto6.push(computed(() => {
            return formState[i].tipo_muestreo === 'Compuesto_6';
        }));

        if (props.order.v_libreta_resultados) {
            formState[i].incertidumbres = false;
        }

        if (props.order.v_libreta_resultados) {
            formState[i].incrementar_parametros = false;
        }
 

        let hasValorCloro = null;
       if(!props.order.v_libreta_resultados) {
            hasValorCloro = computed(() => {
                return (formState[i].cloro === 'Presente' || formState[i].cloro === 'Ausente') && formState[i].tipo_muestreo === 'Simple';
            });
            hasValorCloros[i] = hasValorCloro;
       } else {

       }
    }

    const handleSubmit = () => {
        for (let i = 0; i < props.numeroMuestras; i++) {    
            if (formState[i].tipo_muestreo !== 'Compuesto_4' && formState[i].tipo_muestreo !== 'Compuesto_6' || !formState[i].tipo_muestreo) {
                delete formState[i].fecha_final_muestreo;
                delete formState[i].hora_final_muestreo;
                delete formState[i].fecha_composicion;
                delete formState[i].hora_composicion;
                delete formState[i].flujo_1;
                delete formState[i].flujo_2;
                delete formState[i].flujo_3;
                delete formState[i].flujo_4;
                delete formState[i].flujo_5;
                delete formState[i].flujo_6;
            }
            if (!props.order.v_libreta_resultados) {
                if (formState[i].otros_parametros) {
                    formState[i].parametros = formState[i].otros_parametros;
                    formState[i].otros_parametros = 1
                } else {
                    formState[i].otros_parametros = 0;
                }
            } else {
                delete formState[i].otros_parametros;
            }
        }
        const url = `/muestras/${props.order.folio}/${props.numeroMuestras}/${props.inicioMuestras}`;
        router.post(url, { samples: formState } );
        formState.shift();
    };
    console.log(props.errors);
</script>

<template>
    <AdminLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle
                title="Crear muestras"
                :ownLink="`/muestras/create/${order.folio}/${numeroMuestras}`"
                :backLink="'/orders'"/>
            <form @submit.prevent="handleSubmit">
                <div 
                    v-for="i in createRange(0, props.numeroMuestras - 1)"
                    :key="i"
                    class="grid grid-cols-12 gap-4">
                    <h2 class="col-span-12 text-3xl text-center font-bold">
                        Muestra MFQ-{{ order.folio }} - {{ i + inicioMuestras }}
                    </h2>
                    <div class="col-span-6">
                        <CustomInput
                            id="tipo-muestra"
                            v-model="formState[i].tipo_muestra"
                            label="Tipo de muestra"
                            required/>
                        <Alert 
                            v-if="errors.tipo_muestra"
                            type="error"
                            class="mt-2"
                            :message="errors.tipo_muestra"
                            closable/>
                    </div>
                    <div class="mb-4 col-span-6">
                        <label for="identificacion-muestra">Identificacion de la muestra</label>
                        <select 
                            id="identificacion-muestra"
                            v-model="formState[i].id_identificacion_muestra"
                            class="border-2 border-slate-300 rounded-lg px-4 h-10 block w-full text-gray-900 text-sm focus:outline-none focus:border-blue-200">
                            <option :value="null">
                                Elija una identificacion
                            </option>
                            <option
                                v-for="identificacionMuestra in identificacionesMuestra" 
                                :key="identificacionMuestra.id"
                                :value="identificacionMuestra.id">{{ identificacionMuestra.identificacion_muestra }}</option>
                        </select>
                        <Alert 
                            v-if="errors.id_identificacion_muestra"
                            type="error"
                            class="mt-2"
                            :message="errors.id_identificacion_muestra"
                            closable/>
                    </div>
                    <div class="mb-4 col-span-6">
                        <label>Caracteristicas</label>
                        <Textarea 
                            v-model:value="formState[i].caracteristicas"
                            placeholder="Caracteristicas"
                            class="max-h-16 h-16 min-h-6" />
                        <p 
                            v-if="errors.caracteristicas"
                            class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                            {{ errors.caracteristicas }}
                        </p>
                    </div>
                    <div class="mb-4 col-span-2">
                        <label for="muestreador">Muestreador</label>
                        <select 
                            id="muestreador"
                            v-model="formState[i].muestreador"
                            class="border-2 border-slate-300 rounded-lg px-4 h-10 block w-full text-gray-900 text-sm focus:outline-none focus:border-blue-200">
                            <option :value="null">
                                Elija un muestreador
                            </option>
                            <option value="RCHH">RCHH</option>
                            <option value="JHM">JHM</option>
                            <option value="FESR">FESR</option>
                            <option value="JPMS">JPMS</option>
                            <option value="Cliente">Cliente</option>
                            <option value="CESAVEDAC">CESAVEDAC</option>
                            <option value="QSM">QSM</option>
                            <option value="APPC">APPC</option>
                            <option value="LMQH">LMQH</option>
                        </select>
                        <p 
                            v-if="errors.muestreador"
                            class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                            {{ errors.muestreador }}
                        </p>
                    </div>
                    <div class="col-span-1">
                        <CustomInput
                            id="pH"
                            label="pH"
                            v-model="formState[i].pH"
                            required/>
                            <p 
                                v-if="errors.pH"
                                class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                                {{ errors.pH }}
                            </p>
                    </div>
                    <div class="mb-4 flex justify-center items-center col-span-2">
                        <Checkbox
                            v-model:checked="formState[i].tratada_biologicamente">Tratada biologicamente</Checkbox>
                            <p 
                                v-if="errors.tratada_biologicamente"
                                class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                                {{ errors.tratada_biologicamente }}
                            </p>
                    </div>
                    <div 
                        class="mb-4 flex justify-center items-center col-span-1"
                        v-if="order.v_libreta_resultados">
                        <Checkbox
                            v-model:checked="formState[i].incertidumbres">Incertidumbres</Checkbox>
                            <p 
                                v-if="errors.incertidumbres"
                                class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                                {{ errors.incertidumbres }}
                            </p>
                    </div>
                    <div class="mb-4 col-span-4 col-start-1">
                        <label for="" class="block w-full font-bold ml-12">Cloro</label>
                        <div class="flex justify-center items-center">
                            <RadioGroup v-model:value="formState[i].cloro">
                                <Radio value="Presente">Presente</Radio>
                                <Radio value="Ausente">Ausente</Radio>
                                <Radio value="Laboratorio">Laboratorio</Radio>
                                <Radio value="N/A">N/A</Radio>
                            </RadioGroup>
                            <p 
                                v-if="errors.cloro"
                                class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                                {{ errors.cloro }}
                            </p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <CustomInput 
                            label="Valor del cloro"
                            v-model="formState[i].valor_cloro"
                            id="valor-cloro"
                            required
                            :disabled="!hasValorCloros[i].value"/>
                        <p 
                            v-if="errors.valor_cloro"
                            class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                            {{ errors.valor_cloro }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <CustomInput 
                            label="pH Cr VI"
                            v-model="formState[i].ph_cromo_hexavalente"
                            id="ph-hexavalente"
                            required/>
                        <p 
                            v-if="errors.ph_cromo_hexavalente"
                            class="bg-red-200 text-red-500 mt-2 p-2 rounded-md">
                            {{ errors.ph_cromo_hexavalente }}
                        </p>
                    </div>
                    <div class="mb-4 col-span-2">
                        <label for="tipo_muestreo">Tipo de muestreo</label>
                        <select 
                            id="tipo_muestreo"
                            v-model="formState[i].tipo_muestreo"
                            class="border-2 border-slate-300 rounded-lg px-4 h-10 block w-full text-gray-900 text-sm focus:outline-none focus:border-blue-200"
                            required>
                            <option :value="null">
                                Elija un tipo de muestreo
                            </option>
                            <option value="Simple">Simple</option>
                            <option value="Compuesto_4">Compuesto 4</option>
                            <option value="Compuesto_6">Compuesto 6</option>
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    <CustomInput 
                        label="Conductividad"
                        v-model="formState[i].conductividad"
                        size="col-span-1"
                        id="conductividad"
                        required
                        :disabled="conductividadNa[i].value"/>
                    <div class="mb-4 flex justify-center items-center">
                        <Checkbox
                            v-model:checked="conductividadNa[i].value">Cond. N/A</Checkbox>
                    </div>
                    <CustomInput
                        id="fecha-muestreo"
                        label="Fecha de muestreo"
                        type="date"
                        size="col-span-2"
                        v-model="formState[i].fecha_muestreo"
                        required/>
                    <CustomInput
                        id="hora-muestreo"
                        label="Hora de muestreo"
                        type="time"
                        size="col-span-2"
                        v-model="formState[i].hora_muestreo"
                        required/>
                    <CustomInput
                        id="fecha-final-muestreo"
                        label="Fecha de fin de muestreo"
                        size="col-span-2"
                        type="date"
                        v-model="formState[i].fecha_final_muestreo"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput
                        id="hora-final-muestreo"
                        label="Hora de fin de muestreo"
                        type="time"
                        size="col-span-2"
                        v-model="formState[i].hora_final_muestreo"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput
                        id="fecha-composicion"
                        label="Fecha de composicion"
                        size="col-span-2"
                        type="date"
                        v-model="formState[i].fecha_composicion"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput
                        id="hora-composicion"
                        label="Hora de composicion"
                        size="col-span-2"
                        type="time"
                        v-model="formState[i].hora_composicion"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput 
                        id="flujo-1"
                        size="col-span-2"
                        label="Flujo 1 (l/s)"
                        v-model="formState[i].flujo_1"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput 
                        id="flujo-2"
                        size="col-span-2"
                        label="Flujo 2 (l/s)"
                        v-model="formState[i].flujo_2"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput 
                        id="flujo-3"
                        size="col-span-2"
                        label="Flujo 3 (l/s)"
                        v-model="formState[i].flujo_3"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput 
                        id="flujo-4"
                        size="col-span-2"
                        label="Flujo 4 (l/s)"
                        v-model="formState[i].flujo_4"
                        required
                        :disabled="!muestreoCompuesto[i].value"/>
                    <CustomInput 
                        id="flujo-5"
                        size="col-span-2"
                        label="Flujo 5 (l/s)"
                        v-model="formState[i].flujo_5"
                        required
                        :disabled="!muestreoCompuesto6[i].value"/>
                    <CustomInput 
                        id="flujo-6"
                        size="col-span-2"
                        label="Flujo 6 (l/s)"
                        v-model="formState[i].flujo_6"
                        required
                        :disabled="!muestreoCompuesto6[i].value"/>
                    <div class="mb-4 col-span-12">
                        <label :for="`parametros-${i + 1}`">Parametros</label>
                        <select
                            :id="`parametros-${i + 1}`" 
                            class="border-2 border-slate-300 rounded-lg px-4 h-10 block w-full text-gray-900 text-sm focus:outline-none focus:border-blue-200"
                            v-model="formState[i].parametros">
                            <option 
                                v-for="(param, index) in params"
                                :key="index"
                                :value="param">
                                {{ param }}
                            </option>
                            <option 
                                value="Otro"
                                v-if="!order.v_libreta_resultados">
                                Otro
                            </option>
                        </select>
                    </div>
                    <div 
                        class="mb-4 col-span-6 col-start-4"
                        v-if="!order.v_libreta_resultados">
                        <label for="">Otros parametros</label>
                        <Textarea 
                            v-model:value="formState[i].otros_parametros"
                            placeholder="Otros parametros"
                            class="max-h-16 h-16 min-h-6"
                            :disabled="formState[i].parametros !== 'Otro'"/>
                    </div>
                    <div class="mb-4 col-span-4">
                        <label for="" class="block w-full font-bold ml-32">Preservada correctamente</label>
                        <div class="flex justify-center items-center">
                            <RadioGroup v-model:value="formState[i].preservacion_correcta">
                                <Radio value="Si">Si</Radio>
                                <Radio value="No">No</Radio>
                                <Radio value="N/A">N/A</Radio>
                            </RadioGroup>
                        </div>
                    </div>
                    <hr class="border-blue-500 col-span-12 my-6">
                </div>
                <Button 
                    type="default"
                    html-type="submit"
                    class="bg-green-500 text-white">
                    Crear
                </Button>
            </form>
        </div>
        
    </AdminLayout>
</template>