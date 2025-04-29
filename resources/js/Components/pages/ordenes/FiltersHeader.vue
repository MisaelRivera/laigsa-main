<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import VueMultiselect from 'vue-multiselect';
    import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';
    const props = defineProps({
        links: Array,
        filtersProp: Object,
        filters: Object,
    });

    const filterOptions = ref([
        'cesavedac',
        'supervisar',
        'siralab'
    ]);
    const activeFilters = ref([]);
    const muestreadorFilter = ref(Object.keys(props.filtersProp).includes('muestreador') ? props.filtersProp:null);
    const supervisionFilter = ref(Object.keys(props.filtersProp).includes('supervision') ? props.filtersProp:null);
    const siralabFilter = ref(Object.keys(props.filtersProp).includes('siralab') ? props.filtersProp:null);
    const handleMuestreadorFilter = (ev) => {
        const filtersCopy = {};
        const value = ev.target.value;
        props.filters['muestreador'] = value;
        Array.from(Object.keys(props.filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };

    const handleCesavedacFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('cesavedac')) {
            activeFilters.value.push('cesavedac');
        }
        props.filters['cesavedac'] = value;
        Array.from(Object.keys(props.filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };

    const handleSiralabFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('siralab')) {
            activeFilters.value.push('siralab');
        }
        props.filters['siralab'] = value;
        Array.from(Object.keys(filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };

    const handleSupervisionFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('supervision')) {
            activeFilters.value.push('supervision');
        }
        props.filters['supervision'] = value;
        Array.from(Object.keys(props.filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };

    const handleRemove = (removedOption) => {
        props.filters[removedOption] = null;
        const filtersCopy = {};
        Array.from(Object.keys(props.filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };
</script>

<template>
    <div class="flex justify-between items-center">
        <IndexTitle 
            title="Ordenes"
            :add-link="route('orders.create')"
            :own-link="route('orders.index')"/>
        <Pagination 
            :links="links"/>
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
                    ref="muestreadorFilter"
                    @input="handleMuestreadorFilter"
                    class="border rounded-md w-20">
            </div>
            <div class="flex items-center ml-2">
                <CustomCheckbox
                    name="cesavedac_filter"
                    id="cesavedac-filter"
                    v-model="filters['cesavedac']"
                    label-text="cesavedac"
                    :label-classes="['text-xs']"
                    @change-state="handleCesavedacFilter"/>
            </div>
            <div class="flex items-center ml-2">
                <CustomCheckbox
                    name="supervision_filter"
                    id="supervision-filter"
                    label-text="supervision"
                    v-model="supervisionFilter"
                    :label-classes="['text-xs']"
                    @change-state="handleSupervisionFilter"/>
            </div>
            <div class="flex items-center ml-2">
                <CustomCheckbox
                    name="siralab_filter"
                    id="siralab-filter"
                    label-text="siralab"
                    v-model="siralabFilter"
                    :label-classes="['text-xs']"
                    @change-state="handleSiralabFilter"/>
            </div>
        </div>
    </div>
</template>