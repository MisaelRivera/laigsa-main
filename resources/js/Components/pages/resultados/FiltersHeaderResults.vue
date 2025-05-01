<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { usePermission } from '@/composables/permissions';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import VueMultiselect from 'vue-multiselect';
    import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';
    const props = defineProps({
        links: Array,
        filters: Object,
    });

    const { getRoles } = usePermission();

    const emit = defineEmits(['update:filters']);

    const filterOptions = ref(['cesavedac', 'supervisar', 'siralab']);
    const activeFilters = ref([]);
    const models = reactive({
       muestreador: Object.keys(props.filters).includes('muestreador') ? props.filters['muestreador']:null,
       supervision: Object.keys(props.filters).includes('supervision') ? props.filters['supervision']:null,
       siralab: Object.keys(props.filters).includes('siralab') ? props.filters['siralab']:null,
       cesavedac: Object.keys(props.filters).includes('cesavedac') ? props.filters['cesavedac']:null
    });

    const handleMuestreadorFilter = (ev) => {
        emit('update:filters', { key: 'muestreador', value: ev.target.value });
    };

    const handleCheckboxFilters = (key, value) => {
        emit('update:filters', { key, value });
        if (!activeFilters.value.includes(key))
        activeFilters.value.push(key);
    };


    
    const handleRemove = (removedOption) => {
        models[removedOption] = false; // Update checkbox state
        console.log(models);
        emit('update:filters', { key: removedOption, value: null }); // Notify parent

        const filtersCopy = {};
        for (const key in props.filters) {
            if (props.filters[key] !== null && props.filters[key] !== '') {
                filtersCopy[key] = props.filters[key];
            }
        }

        // Remove from multiselect
        activeFilters.value = activeFilters.value.filter(item => item !== removedOption);

        router.visit(route('water_samples_results.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };
</script>

<template>
    <div class="flex justify-between items-center"
    :class="{'w-6/12': getRoles().includes('analist'), 'mx-auto': true}">
        <IndexTitle 
            title="Resultados"
            :own-link="route('orders.index')"/>
        <Pagination 
            :links="links"/>
        <template v-if="!getRoles().includes('analist')">
            <div class="flex items-center" v-if="activeFilters.length > 0">
                <VueMultiselect
                    :options="filterOptions"
                    :class="['col-span-6']"
                    v-model="activeFilters"
                    :multiple="true"
                    @remove="handleRemove"
                    placeholder="Elije una opcion">
                </VueMultiselect>
            </div>
            <div class="flex items-center">
                <div>
                    <label 
                        for="muestreador_filter" 
                        class="text-xs">Muestreador</label>
                    <input 
                        type="text"
                        id="muestreador-filter"
                        v-model="models.muestreador"
                        @input="handleMuestreadorFilter"
                        class="border rounded-md w-20">
                </div>
                <div class="flex items-center ml-2">
                    <CustomCheckbox
                        v-model="models['cesavedac']"
                        :checked="models['cesavedac']"
                        label-text="Cesavedac"
                        :label-classes="['text-xs']"
                        @change-state="(value) => handleCheckboxFilters('cesavedac', value)"
                    />
                </div>
                <div class="flex items-center ml-2">
                    <CustomCheckbox
                        v-model="models['supervision']"
                        :checked="models['supervision']"
                        label-text="Supervision"
                        :label-classes="['text-xs']"
                        @change-state="(value) => handleCheckboxFilters('supervision', value)"
                    />
                </div>
                <div class="flex items-center ml-2">
                    <CustomCheckbox
                        v-model="models['siralab']"
                        :checked="models['siralab']"
                        label-text="Siralab"
                        :label-classes="['text-xs']"
                        @change-state="(value) => handleCheckboxFilters('siralab', value)"
                    />
                </div>
            </div>
        </template>
        <div v-else class="w-1/12"></div>
    </div>
</template>