<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parameter: {
            type: Object
        },

        methods: {
            type: Array
        },

        units: {
            type: Array
        },

        lcps: {
            type: Array
        }
    });

    const formState = useForm({
        unidad: '',
        metodo: '',
        lcp: ''
    });

    const handleCreateCombination = () => {
        try {
            formState.post(route('parameters.combination_store', props.parameter.id));
        } catch (e) {
            console.log(e);
        }
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
            <h1>{{ parameter.parametro }}</h1>
            <a-form
                :model="formState"
                layout="vertical"
                @finish="handleCreateCombination">
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <h1 class="text-center">Unidades</h1>
                    <a-form-item
                        label="Unidad"
                        name="unidad"
                        class="mb-0"
                        :style="{marginBottom: 0}">
                        <a-auto-complete
                            v-model:value="formState.unidad"
                            :options="units"/>
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
                            :options="methods"/>
                            <p class="text-red-500"
                                v-if="formState.errors.metodo">
                                {{ formState.errors.metodo }}
                            </p>
                    </a-form-item>
                </div>
                <div>
                    <h1 class="text-center">LCPs</h1>
                    <a-form-item
                        label="LCPs"
                        name="lcp">
                        <a-auto-complete
                            v-model:value="formState.lcp"
                            :options="lcps"/>
                            <p class="text-red-500"
                                v-if="formState.errors.lcp">
                                {{ formState.errors.lcp }}
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