<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parameterCombination: {
            type: Object
        },

        parameters: {
            type: Array
        },

        methods: {
            type: Array
        },

        units: {
            type: Array
        },

    });
    const lcps = ref(props.parameterCombination.parametro.formattedLcps);
    const formState = useForm({
        id_unidad: null,
        id_metodo: null,
        id_parametro: null,
        clasificacion: null,
        id_lcp: '',
        alias: '',
        abreviacion: null,
        arrange: null,
        subcontratado: null,
        compuesto: null,
        ema: null,
        cna: null,
        ssa: null,
        supervisar: null,
        fecha_resultado_final: null,
        parametro_campo: null,
        incertidumbre: null,
    });
    const form$ = ref(null);
    onMounted(() => {
        form$.value.update({
            id_unidad: props.parameterCombination.unidad.id,
            id_metodo: props.parameterCombination.metodo.id_metodo,
            id_parametro: props.parameterCombination.parametro.id,
            id_lcp: props.parameterCombination.lcp.id,
            clasificacion: props.parameterCombination.clasificacion,
            alias: props.parameterCombination.alias,
            abreviacion: props.parameterCombination.abreviacion,
            arrange: props.parameterCombination.arrange,
        });
    });

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };


    const handleEditCombination = () => {
        try {
            formState.id_unidad = form$.value.data.id_unidad;
            formState.id_metodo = form$.value.data.id_metodo;
            formState.id_parametro = form$.value.data.id_parametro;
            formState.id_lcp = form$.value.data.id_lcp;
            formState.clasificacion = form$.value.data.clasificacion;
            formState.alias = form$.value.data.alias;
            formState.abreviacion = form$.value.data.abreviacion;
            formState.arrange = form$.value.data.arrange;
            formState.subcontratado = form$.value.data.subcontratado;
            formState.compuesto = form$.value.data.compuesto;
            formState.ema = form$.value.data.ema;
            formState.cna = form$.value.data.cna;
            formState.ssa = form$.value.data.ssa;
            formState.supervisar = form$.value.data.supervisar;
            formState.fecha_resultado_final = form$.value.data.fecha_resultado_final;
            formState.parametro_campo = form$.value.data.parametro_campo;
            formState.incertidumbre = form$.value.data.incertidumbre;
            formState.put(route('parameters-combinations.update', props.parameterCombination.id));
        } catch (e) {
            console.log(e);
        }
    };

    const handleParameterSelect = async(paramId) => {
        const res = await axios.get(`/parameters-combinations/${paramId}/get-lcps`);
        lcps.value = [{label: 'Elija un lcp', value:'null', key:0}, ...res.data]; 
        const lcp = form$.value.el$('id_lcp');
        lcp.clear();
        lcp.update('null');
    };
    console.log(form$.value);
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
            <h1>Editar combinaciones de parametros </h1>
            <Vueform
                :endpoint="false"
                @submit="handleEditCombination"
                :columns="{ container: 12, wrapper: 12 }"
                :scroll-to-invalid="false"
                :display-errors="false"
                ref="form$">
                <SelectElement 
                    name="id_parametro"
                    :items="parameters"
                    :search="true"
                    before="Parametro"
                    :columns="{ container: 6, wrapper: 12 }"
                    @select="handleParameterSelect">
                    <template #description>
                        <p v-if="formState.errors.id_parametro">{{ formState.errors.id_parametro }}</p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_metodo"
                    :items="methods"
                     :search="true"
                    before="Metodo"
                    :columns="{ container: 6, wrapper: 12 }">
                    <template #description>
                        <p v-if="formState.errors.id_metodo">{{ formState.errors.id_metodo }}</p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_unidad"
                    :items="units"
                     :search="true"
                    before="Unidad"
                    :columns="{ container: 6, wrapper: 12 }">
                    <template #description>
                        <p v-if="formState.errors.id_unidad">{{ formState.errors.id_unidad }}</p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_lcp"
                    :items="lcps"
                     :search="true"
                    before="LCP"
                    :columns="{ container: 6, wrapper: 12 }"/>
                    <TextElement
                    name="alias"
                    :columns="{container: 6, wrapper:12}"
                    before="Alias">
                    <template #description>
                        <p v-if="formState.errors.alias">{{ formState.errors.alias }}</p>
                    </template>
                </TextElement>
                <SelectElement
                    name="clasificacion"
                    :columns="{container:3, wrappers:12}"
                    :items="[
                        {value: null, label: 'Elija una opcion'},
                        'Aguas',
                        'Alimentos'
                    ]"
                    before="Clasificacion">
                    <template #description>
                        <p v-if="formState.errors.clasificacion">{{ formState.errors.clasificacion }}</p>
                    </template>
                </SelectElement>
                <TextElement 
                    name="abreviacion"
                    before="Abreviacion"
                    :description="formState.errors.abreviacion ? `<p class='text-red-500'>${formState.errors.abreviacion}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <TextElement 
                    name="arrange"
                    before="Orden de acomodo"
                    input-type="number"
                    :description="formState.errors.arrange ? `<p class='text-red-500'>${formState.errors.arrange}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <CheckboxElement
                    name="subcontratado"
                    :columns="{ container:3, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.subcontratado">
                    Subcontratado
                </CheckboxElement>
                <CheckboxElement
                    name="compuesto"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Compuesto
                </CheckboxElement>
                <CheckboxElement
                    name="ema"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.ema">
                    Ema
                </CheckboxElement>
                <CheckboxElement
                    name="cna"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.cna">
                    CNA
                </CheckboxElement>
                <CheckboxElement
                    name="ssa"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.ssa">
                    SSA
                </CheckboxElement>
                <CheckboxElement
                    name="supervisar"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.supervisar">
                    Supervisar
                </CheckboxElement>
                <CheckboxElement
                    name="fecha_resultado_final"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.fecha_resultado_final">
                    Fecha resultados final
                </CheckboxElement>
                <CheckboxElement
                    name="parametro_campo"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.parametro_campo">
                    Parametro de campo
                </CheckboxElement>
                <CheckboxElement
                    name="incertidumbre"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }"
                    :default="parameterCombination.incertidumbre">
                    Incertidumbre
                </CheckboxElement>
                <button 
                    class="btn btn-primary">Editar</button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>