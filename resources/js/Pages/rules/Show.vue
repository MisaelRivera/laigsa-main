<script setup>
    import { ref } from 'vue';
    import { useForm, Link } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        rule: {
            type: Object,
        },

        parametersCombinations: {
            type: Array
        },
        
        page: {
            type: String
        }
    });

    const { getMessage } = useMessages();

    const form$ = ref(null);

    const addingForm = useForm({
        alias: null,

    });

    const isAddOpen = ref(false);
    const handleOpenAdd = () => {
        isAddOpen.value = true;
    };

    const handleCloseAdd = () => {
        isAddOpen.value = false;
    };

    const handleAdd = () => {
        addingForm.alias = form$.value.el$('alias').value;
       addingForm.post(route('parameters-combinations.add_param_combination',  props.rule.id));
       isAddOpen.value = false;
       addingForm.reset();
    };

    const isDeleteOpen = ref(false);
    const deleteForm = useForm({
        id: null,
        alias: null,
    });

    const filterOption = (input, option) => {
        return option.value.toUpperCase().indexOf(input.toUpperCase()) >= 0;
    };
    
    const handleOpenDeleteItem = (parameterCombination) => {
        isDeleteOpen.value = true;
        deleteForm.id = parameterCombination.id;
        deleteForm.alias = parameterCombination.alias;
    };
    
    const handleCloseDeleteItem = () => {
        isDeleteOpen.value = false;
        deleteForm.id = null;
    };

    const handleDelete = () => {
        try {
            console.log(deleteForm.id);
            deleteForm.delete(route('parameters-combinations.remove_param_combination', deleteForm.id));
            isDeleteOpen.value = false;
        } catch (e) {
            console.log(e);
        }
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12">
            <div class="grid grid-cols-5">
                <CreateTitle 
                    title="Agregar parametros a la norma"
                    class="col-span-5"
                    :back-link="route('rules.index', { page: page })"
                    :own-link="route('rules.edit', { rule: props.rule, page: page })"/>
                <div class="col-span-5 flex">
                    <button 
                        class="py-1.5 px-3 text-white bg-yellow-500 rounded-full col-span-1"
                        @click="handleOpenAdd">
                        +
                    </button>
                    <input 
                        type="text"
                        class="border ml-3 px-2">
                </div>
                <div 
                    class="col-span-1 p-5"
                    v-for="parameterCombination in rule.parametersCombinations">
                    <p class="text-sm text-center">
                        {{ parameterCombination.parametro.parametro }}
                        <i
                            class="fas fa-trash text-white bg-red-500 h-6 w-6 rounded-full text-xs py-1"
                            @click="() => handleOpenDeleteItem(parameterCombination)"></i>
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
        <MyModal
            v-model="isAddOpen"
            title="Agregar parametro"
            :ok-button-props="{
                class: ['bg-green-500', 'text-white', 'hidden']
            }"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            @close-from="handleCloseAdd">
            <Vueform
                :endpoint="false"
                @submit="handleAdd"
                :columns="{container:12, wrapper:12}"
                ref="form$">
                <SelectElement
                    :search="true"
                    name="alias"
                    before="Alias"
                    :items="parametersCombinations">
                    <template #description>
                        <p 
                            v-if="addingForm.errors.alias"
                            class="text-red-400">
                            {{ addingForm.errors.alias }}
                        </p>
                    </template>
                </SelectElement>
                <button 
                    class="rounded-md bg-green-500 text-white py-1 px-2 col-span-3">
                    Agregar
                </button>
            </Vueform>
        </MyModal>
        <MyModal
            title="Eliminar parametro"
            v-model="isDeleteOpen"
            @ok="handleDelete"
            @close-from="handleCloseDeleteItem">
            <p>Seguro que deseas eliminar el parametro {{ deleteForm.alias }}?</p>
        </MyModal>
        
    </AuthenticatedLayout>
</template>