<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import { DeleteOutlined } from '@ant-design/icons-vue';
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

    const isDeleteModalOpen = ref(false);
    const deleteForm = useForm({
        id: null,
    });
    const handleAddParameterCombination = () => {
       addingForm.post(route('parameters-combinations.add_param_combination',  props.rule.id));
       isAddModalOpen.value = false;
       addingForm.reset();
    };

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };

    const handleOpenDeleteItem = (parameterCombination) => {
        isDeleteModalOpen.value = true;
        deleteForm.id = parameterCombination.id;
    };

    const handleDelete = () => {
        try {
            console.log(deleteForm.id);
            deleteForm.delete(route('parameters-combinations.remove_param_combination', deleteForm.id));
            isDeleteModalOpen.value = false;
        } catch (e) {
            console.log(e);
        }
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
                        <DeleteOutlined 
                            class="text-white bg-red-500 py-0.5 px-1 rounded-full text-xs"
                            @click="() => handleOpenDeleteItem(parameterCombination)"/>
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
        <a-modal
            v-model:open="isDeleteModalOpen"
            title="Eliminar parametro"
            :ok-button-props="{ hidden: true }"
            :cancel-button-props="{ hidden: true }">
            <form
                @submit.prevent="handleDelete">
                <p>Seguro que deseas remover este parametro?</p>
                <button class="btn btn-danger">Eliminar</button>
            </form>
        </a-modal>
    </AuthenticatedLayout>
</template>