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
    const form$ = ref(null);
    onMounted(() => {
        form$.value.update({
            id_unidad: props.parameterCombination.unidad.id,
            id_metodo: props.parameterCombination.metodo.id,
            id_parametro: props.parameterCombination.parametro.id,
            id_lcp: props.parameterCombination.lcp.id,
            clasificacion: props.parameterCombination.clasificacion,
            alias: props.parameterCombination.alias,
        });
    });
    const formState = useForm({
        unidad: props.parameterCombination.unidad.nombre,
        metodo: props.parameterCombination.metodo.nombre,
        parametro: props.parameterCombination.parametro.parametro,
        clasificacion: props.parameterCombination.clasificacion,
        lcp: props.parameterCombination.lcp.valor,
        alias: props.parameterCombination.alias,
    });

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };


    const handleEditCombination = () => {
        try {
            formState.put(route('parameters-combinations.update', props.parameterCombination.id));
        } catch (e) {
            console.log(e);
        }
    };

    const handleParameterSelect = async(event) => {
        const paramId = event.target.value;
        const res = await axios.get(`/parameters-combinations/${paramId}/get-lcps`);
        lcps.value = res.data; 
    };

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
                    before="Parametro"
                    :columns="{ container: 6, wrapper: 12 }"
                    @input="handleParameterSelect"/>
                <SelectElement 
                    name="id_metodo"
                    :items="methods"
                    before="Metodo"
                    :columns="{ container: 6, wrapper: 12 }"/>
                <SelectElement 
                    name="id_unidad"
                    :items="units"
                    before="Unidad"
                    :columns="{ container: 6, wrapper: 12 }"/>
                <SelectElement 
                    name="id_lcp"
                    :items="lcps"
                    before="LCP"
                    :columns="{ container: 6, wrapper: 12 }"/>
            </Vueform>
           <!-- <a-form
                :model="formState"
                layout="vertical"
                @finish="handleEditCombination">
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <a-form-item
                        label="Parametro"
                        name="parametro">
                        <a-auto-complete
                            v-model:value="formState.parametro"
                            :options="parameters"
                            :filter-option="filterOption"
                            @select="handleParameterSelect"/>
                            <p class="text-red-500"
                                v-if="formState.errors.parametro">
                                {{ formState.errors.parametro }}
                            </p>
                    </a-form-item>
                </div>
                <div>
                    <a-form-item
                        label="Unidad"
                        name="unidad"
                        class="mb-0"
                        :style="{marginBottom: 0}">
                        <a-auto-complete
                            v-model:value="formState.unidad"
                            :options="units"
                            :filter-option="filterOption"/>
                            <p class="text-red-500"
                                v-if="formState.errors.unidad">
                                {{ formState.errors.unidad }}
                            </p>
                    </a-form-item>
                </div>
                <div>
                    <a-form-item
                        label="Metodos"
                        name="metodo">
                        <a-auto-complete
                            v-model:value="formState.metodo"
                            :options="methods"
                            :filter-option="filterOption"/>
                            <p class="text-red-500"
                                v-if="formState.errors.metodo">
                                {{ formState.errors.metodo }}
                            </p>
                    </a-form-item>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <a-form-item
                        label="LCPs"
                        name="lcp">
                        <a-auto-complete
                            v-model:value="formState.lcp"
                            :options="lcps"
                            :filter-option="filterOption"
                            />
                            <p class="text-red-500"
                                v-if="formState.errors.lcp">
                                {{ formState.errors.lcp }}
                            </p>
                    </a-form-item>
                </div>
                <div>
                    <a-form-item
                        label="Clasificacion">
                        <a-select
                            v-model:value="formState.clasificacion">
                            <a-select-option
                                :value="null"
                                key="elije">
                                Elije una opcion
                            </a-select-option>
                            <a-select-option
                                value="Aguas"
                                key="aguas">
                                Aguas
                            </a-select-option>
                            <a-select-option
                                value="Alimentos"
                                key="alimentos">
                                Alimentos
                            </a-select-option>
                        </a-select>
                        <p class="text-red-500"
                            v-if="formState.errors.clasificacion">
                            {{ formState.errors.clasificacion }}
                        </p>
                    </a-form-item>
                </div>
                <div>
                    <a-form-item
                        label="Alias">
                        <a-input
                            v-model:value="formState.alias"
                            class="h-8 rounded border-slate-200"/> 
                        <p class="text-red-500"
                            v-if="formState.errors.alias">
                            {{ formState.errors.alias }}
                        </p>            
                    </a-form-item>
                </div>
            </div>
                <button 
                    class="btn btn-primary">Edit</button>
            </a-form>-->
        </div>
    </AuthenticatedLayout>
</template>