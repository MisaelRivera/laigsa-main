<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    const emit = defineEmits(['filter']);
    const props = defineProps({
        filters: Array,
        route: String
    });
    const selectedFilter = ref(props.filters[0].value);
    const handleFilter = (ev) => {
        const value = ev.target.value;
        const filterObj = {};
        filterObj[selectedFilter.value] = encodeURIComponent(value);
        router.visit(route(props.route, filterObj), {
            preserveState: true,
            method: 'get'
        });
    };

    const handleChangeFilter = (ev) => {
        selectedFilter.value = ev.target.value;
    };
</script>
<template>
     <div 
        class="flex items-center">
        <div class="w-40 mb-4 mr-3">
            <label for="busqueda">Busqueda</label>
            <input 
                type="text"
                id="busqueda"
                name="busqueda"
                class="h-8 w-40 rounded border px-3"
                @input="handleFilter">
        </div>
        <div class="w-40 mb-4 mr-3">
            <label for="filtro">Filtro</label>
            <select 
                name="filtro" 
                id="filtro"
                class="h-8 w-40 rounded border px-3"
                @change="handleChangeFilter">
                <option 
                    :selected="selectedFilter === filter.value"
                    :value="filter.value"
                    v-for="filter in filters">
                    {{ filter.label }}
                </option>
            </select>
        </div>
    </div>
</template>