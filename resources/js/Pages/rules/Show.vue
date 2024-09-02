<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        rule: {
            type: Object,
        },

        parametersCombinations: {
            type: Array
        },
    });

    const addingForm = useForm({
        alias: null,

    });

    const isAddModalOpen = ref(false);
    const handleOpenAddParameterCombinationModal = () => {
        isAddModalOpen.value = true;
    };
    const handleAddParameterCombination = (value) => {
        addingForm.post();
    };

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12">
            <div class="grid grid-cols-5">
                <div class="col-span-5">
                    <button 
                        class="py-1.5 px-3 text-white bg-yellow-500 rounded-full"
                        @click="handleOpenAddParameterCombinationModal">
                        +
                    </button>
                </div>
                <div 
                    class="col-span-1"
                    v-for="parameterCombination in rule.parametersCombinations">
                    Texto
                </div>
            </div>
        </div>
        <a-modal
            v-model:open="isAddModalOpen"
            title="Agregar parametro"
            :ok-button-props="{hidden: true}">
            <a-auto-complete
                @select="handleAddParameterCombination"
                :options="parametersCombinations"
                :filter-option="filterOption"
                class="w-full"/>
        </a-modal>
    </AuthenticatedLayout>
</template>