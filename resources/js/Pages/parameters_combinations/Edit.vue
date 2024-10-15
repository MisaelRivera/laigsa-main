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
            id_metodo: props.parameterCombination.metodo.id_metodo,
            id_parametro: props.parameterCombination.parametro.id,
            id_lcp: props.parameterCombination.lcp.id,
            clasificacion: props.parameterCombination.clasificacion,
            alias: props.parameterCombination.alias,
        });
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

    const handleParameterSelect = async(paramId) => {
        const res = await axios.get(`/parameters-combinations/${paramId}/get-lcps`);
        lcps.value = [{label: 'Elija un lcp', value:'null', key:0}, ...res.data]; 
        const lcp = form$.value.el$('id_lcp');
        lcp.clear();
        lcp.update('null');
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
                    :search="true"
                    before="Parametro"
                    :columns="{ container: 6, wrapper: 12 }"
                    @select="handleParameterSelect"/>
                <SelectElement 
                    name="id_metodo"
                    :items="methods"
                     :search="true"
                    before="Metodo"
                    :columns="{ container: 6, wrapper: 12 }"/>
                <SelectElement 
                    name="id_unidad"
                    :items="units"
                     :search="true"
                    before="Unidad"
                    :columns="{ container: 6, wrapper: 12 }"/>
                <SelectElement 
                    name="id_lcp"
                    :items="lcps"
                     :search="true"
                    before="LCP"
                    :columns="{ container: 6, wrapper: 12 }"/>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>