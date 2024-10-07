<script setup>
    import { ref, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
        id_unidad: '',
        id_metodo: '',
        id_parametro: null,
        clasificacion: null,
        id_lcp: '',
        alias: '',
    });

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };

    const lcps = ref([]);

    const handleCreateCombination = () => {
        try {
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
            <h1>Crear combinaciones de parametros </h1>
            <div class="grid grid-cols-3 gap-2">
                    <Vueform
                        :endpoint="false"
                        @submit="handleCreateCombination"
                        :columns="{ container: 12, wrapper:12}">
                        <TextElement 
                            />
                    </Vueform>
            </div>
                <button 
                    class="btn btn-success">Crear</button>
        </div>
    </AuthenticatedLayout>
</template>