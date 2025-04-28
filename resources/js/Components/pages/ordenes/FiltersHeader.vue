<script setup>
import { ref } from 'vue';
import FilterManager from '@/Classes/FilterManager';
import IndexTitle from '@/Components/Shared/IndexTitle.vue';
import Pagination from '@/Components/Shared/Pagination.vue';
import VueMultiselect from 'vue-multiselect';
import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';

// Define props (no types)
const props = defineProps({
    links: Array,
    filtersProp: Object,
    filters: Object,
});

// Setup data
const filterOptions = ref(['cesavedac', 'supervisar', 'siralab']);
const activeFilters = ref([]);

const filterManager = new FilterManager(props.filters);

const muestreadorFilter = ref(props.filtersProp.muestreador || '');
const supervisionFilter = ref(props.filtersProp.supervision || '');
const siralabFilter = ref(props.filtersProp.siralab || '');

// Handle events
function handleInputFilter(event) {
    const input = event.target;
    filterManager.applyFilter('muestreador', input.value);
}

function handleCheckboxFilter(filterName, value) {
    if (!activeFilters.value.includes(filterName)) {
        activeFilters.value.push(filterName);
    }
    filterManager.applyFilter(filterName, value);
}

function handleRemoveFilter(filterName) {
    filterManager.removeFilter(filterName);
}
</script>

<template>
<div class="flex justify-between items-center">
    <IndexTitle 
        title="Ordenes"
        :add-link="route('orders.create')"
        :own-link="route('orders.index')"
    />

    <Pagination :links="links" />

    <div v-if="activeFilters.length > 0" class="flex items-center">
        <VueMultiselect
            :options="filterOptions"
            v-model="activeFilters"
            :multiple="true"
            @remove="handleRemoveFilter"
            placeholder="Elige una opción"
        />
    </div>

    <div class="flex items-center">
        <div>
            <label for="muestreador_filter" class="text-xs">Muestreador</label>
            <input
                id="muestreador_filter"
                type="text"
                v-model="muestreadorFilter"
                @input="handleInputFilter"
                class="border rounded-md w-20"
            />
        </div>

        <div class="flex items-center ml-2">
            <CustomCheckbox
                id="cesavedac-filter"
                label-text="cesavedac"
                v-model="props.filters.cesavedac"
                :label-classes="['text-xs']"
                @change-state="(value) => handleCheckboxFilter('cesavedac', value)"
            />
        </div>

        <div class="flex items-center ml-2">
            <CustomCheckbox
                id="supervision-filter"
                label-text="supervision"
                v-model="supervisionFilter"
                :label-classes="['text-xs']"
                @change-state="(value) => handleCheckboxFilter('supervision', value)"
            />
        </div>

        <div class="flex items-center ml-2">
            <CustomCheckbox
                id="siralab-filter"
                label-text="siralab"
                v-model="siralabFilter"
                :label-classes="['text-xs']"
                @change-state="(value) => handleCheckboxFilter('siralab', value)"
            />
        </div>
    </div>
</div>
</template>