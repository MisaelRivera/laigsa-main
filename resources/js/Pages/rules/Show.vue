<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        rule: {
            type: Object,
        },

        parametersCombinations: {
            type: Array
        },
    });

    const { getMessage } = useMessages();

    const addingForm = useForm({
        alias: null,

    });

    const isAddModalOpen = ref(false);
    const handleOpenAddParameterCombinationModal = () => {
        isAddModalOpen.value = true;
    };
    const handleAddParameterCombination = () => {
       addingForm.post(route('parameters-combinations.add_param_combination',  props.rule.id));
       isAddModalOpen.value = false;
       addingForm.reset();
    };

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12">
            <a-alert
                type="success"
                v-if="getMessage()"
                :message="getMessage()"/>
            <div class="grid grid-cols-5">
                <div class="col-span-5">
                    <button 
                        class="py-1.5 px-3 text-white bg-yellow-500 rounded-full"
                        @click="handleOpenAddParameterCombinationModal">
                        +
                    </button>
                </div>
                <div 
                    class="col-span-1 p-5"
                    v-for="parameterCombination in rule.parametersCombinations">
                    <p class="text-sm text-center">
                        {{ parameterCombination.parametro.parametro }}
                    </p>
                    <p class="text-sm text-center">
                        {{ parameterCombination.unidad.nombre }}
                    </p>
                    <p class="text-sm text-center">
                        {{ parameterCombination.metodo.nombre }}
                    </p>
                </div>
            </div>
        </div>
        <a-modal
            v-model:open="isAddModalOpen"
            title="Agregar parametro"
            :ok-button-props="{hidden: true}"
            :cancel-button-props="{hidden: true}">
            <form
                @submit.prevent="handleAddParameterCombination">
                <a-auto-complete
                    :options="parametersCombinations"
                    :filter-option="filterOption"
                    class="w-full"
                    v-model:value="addingForm.alias"/>
                <p v-if="addingForm.errors.alias" class="text-red-400">
                    {{ addingForm.errors.alias }}
                </p>
                <button class="btn btn-success mt-12">
                    Agregar
                </button>
            </form>
        </a-modal>
    </AuthenticatedLayout>
</template>