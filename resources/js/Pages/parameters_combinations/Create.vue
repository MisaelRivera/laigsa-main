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
        unidad: '',
        metodo: '',
        parametro: '',
        clasificacion: null,
        lcp: '',
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
            <a-form
                :model="formState"
                layout="vertical"
                @finish="handleCreateCombination">
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <h1 class="text-center">Parametros</h1>
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
                    <h1 class="text-center">Unidades</h1>
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
                    <h1 class="text-center">Metodos</h1>
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
                    <h1 class="text-center">LCPs</h1>
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
                    <h1 class="text-center">Clasificacion</h1>
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
                    <h1 class="text-center">Alias</h1>
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
                    class="btn btn-success">Crear</button>
            </a-form>
        </div>
    </AuthenticatedLayout>
</template>