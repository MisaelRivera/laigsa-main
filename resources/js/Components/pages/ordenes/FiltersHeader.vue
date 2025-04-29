<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import VueMultiselect from 'vue-multiselect';
    import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';
    const props = defineProps({
        links: Array,
        filters: Object,
    });

    const emit = defineEmits(['update:filters']);

    const filterOptions = ref([
        'cesavedac',
        'supervisar',
        'siralab'
    ]);
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

    const handleCesavedacFilter = (ev) => {
        const value = ev.target.value;
        console.log(value);
        emit('update:filters', { key: 'cesavedac', value });
        if (!activeFilters.value.includes('cesavedac'))
        activeFilters.value.push('cesavedac');
    };

    const handleSiralabFilter = (ev) => {
        const value = ev.target.value;
        emit('update:filters', { key: 'siralab', value });
        if (!activeFilters.value.includes('siralab'))
        activeFilters.value.push('siralab');
    };

    const handleSupervisionFilter = (ev) => {
        const value = ev.target.value;
        emit('update:filters', { key: 'supervision', value });
        if (!activeFilters.value.includes('supervision'))
        activeFilters.value.push('supervision');
    };

    const handleRemove = (removedOption) => {
        props.filters[removedOption] = null;
        const filtersCopy = {};
        Array.from(Object.keys(props.filters)).forEach(item => {
            if (props.filters[item] !== null && props.filters[item] !== '') {
                filtersCopy[item] = props.filters[item];
            }
        });
        console.log(removedOption);
        models[removedOption] = null;
        console.log(models);
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
                    v-model="models.muestreador"
                    @input="handleMuestreadorFilter"
                    class="border rounded-md w-20">
            </div>
            <div class="flex items-center ml-2">
                <input
                    type="checkbox"
                    name="cesavedac"
                    class="border-2 p-2 rounded"
                    v-model="models.cesavedac"
                    value="1"
                    @change="handleCesavedacFilter"/>
                <label for="cesavedac" class="text-xs">Cesavedac</label>
            </div>
            <div class="flex items-center ml-2">
                <input
                    type="checkbox"
                    name="supervision"
                    class="border-2 p-2 rounded"
                    v-model="models.supervision"
                    value="1"
                    id="supervision"
                    @change="handleSupervisionFilter"/>
                <label for="supervision" class="text-xs">Supervision</label>
            </div>
            <div class="flex items-center ml-2">
                <input
                    type="checkbox"
                    name="siralab"
                    class="border-2 p-2 rounded"
                    v-model="models.siralab"
                    value="1"
                    id="siralab"
                    @change="handleSiralabFilter"/>
                <label for="siralab" class="text-xs">Siralab</label>
            </div>
        </div>
    </div>
</template>