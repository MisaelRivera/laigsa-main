<script setup>
    import { ref, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
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

    const { getError } = useMessages();

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

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };

    const form$ = ref(null);

    const lcps = ref([]);

    const handleCreateCombination = () => {
        try {
            formState.id_unidad = form$.value.data.id_unidad;
            formState.id_metodo = form$.value.data.id_metodo;
            formState.id_parametro = form$.value.data.id_parametro;
            formState.clasificacion = form$.value.data.clasificacion;
            formState.id_lcp = form$.value.data.id_lcp;
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
            formState.post(route('parameters-combinations.store'));
        } catch (e) {
            console.log(e);
        }
    };

    const handleParameterSelect = async(value) => {
        const res = await axios.get(`/parameters-combinations/${value}/get-lcps`);
        lcps.value = res.data;
        console.log(res);
    };
    console.log(getError());
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
            <CreateTitle
                title="Crear combinaciones de parametros"
                :own-link="route('parameters-combinations.create')"
                :back-link="route('parameters-combinations.index')"/>
            <Vueform
                :endpoint="false"
                @submit="handleCreateCombination"
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
                        <p 
                            v-if="formState.errors.id_parametro"
                            class="text-red-500">
                            {{ formState.errors.id_parametro }}
                        </p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_metodo"
                    :items="methods"
                    :search="true"
                    before="Metodo"
                    :columns="{ container: 6, wrapper: 12 }">
                    <template #description>
                        <p 
                            v-if="formState.errors.id_metodo"
                            class="text-red-500">
                            {{ formState.errors.id_metodo }}
                        </p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_unidad"
                    :items="units"
                    :search="true"
                    before="Unidad"
                    :columns="{ container: 6, wrapper: 12 }">
                    <template #description>
                        <p 
                            v-if="formState.errors.id_unidad"
                            class="text-red-500">
                            {{ formState.errors.id_unidad }}
                        </p>
                    </template>
                </SelectElement>
                <SelectElement 
                    name="id_lcp"
                    :items="lcps"
                     :search="true"
                    before="LCP"
                    :columns="{ container: 6, wrapper: 12 }">
                    <template #description>
                        <p 
                            v-if="formState.errors.id_lcp"
                            class="text-red-500">
                            {{ formState.errors.id_lcp }}
                        </p>
                    </template>
                </SelectElement>
                <TextElement
                    name="alias"
                    :columns="{container: 6, wrapper:12}"
                    before="Alias">
                    <template #description>
                        <p 
                            v-if="formState.errors.alias"
                            class="text-red-500">
                            {{ formState.errors.alias }}
                        </p>
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
                        <p 
                            v-if="formState.errors.clasificacion"
                            class="text-red-500">
                            {{ formState.errors.clasificacion }}
                        </p>
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
                    }">
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
                    }">
                    Ema
                </CheckboxElement>
                <CheckboxElement
                    name="cna"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    CNA
                </CheckboxElement>
                <CheckboxElement
                    name="ssa"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
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
                    :default="true">
                    Supervisar
                </CheckboxElement>
                <CheckboxElement
                    name="fecha_resultado_final"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Fecha resultados final
                </CheckboxElement>
                <CheckboxElement
                    name="parametro_campo"
                    :columns="{ container:2, wrapper:12 }"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
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
                    :default="true">
                    Incertidumbre
                </CheckboxElement>
                <button 
                    class="btn btn-success">Crear</button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>