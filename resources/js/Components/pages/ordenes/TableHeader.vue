<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    const props = defineProps({
        filters: Object,
        filtersProp: Object
    });

    const clientFilter = ref(Object.keys(props.filtersProp).includes('cliente') ? props.filtersProp:null);
    const folioFilter = ref(Object.keys(props.filtersProp).includes('folio') ? props.filtersProp:null);

    const handleClientFilter = (ev) => {
        const filtersCopy = {};
        const value = ev.target.value;
        props.filters['cliente'] = value;
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

    const handleFolioFilter = (ev) => {
        const filtersCopy = {};
        const value = ev.target.value;
        props.filters['folio'] = value;
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
    <thead class="text-xs text-gray-700 bg-gray-50">
        <tr>
            <th 
                scope="col" 
                class="px-2 py-3 w-[3%]">
                Folio
                <input 
                    type="text"
                    id="busqueda"
                    name="busqueda"
                    ref="folioFilter"
                    class="h-8 w-20 rounded border px-3"
                    v-model="props.filters['folio']"
                    @input="handleFolioFilter">
            </th>
            <th scope="col" class="px-2 py-3 w-[1%]">No.</th>
            <th scope="col" class="px-2 py-3 w-[1%]">
                <button>
                    <i class="fas fa-arrow-down"></i>
                </button>
            </th>
            <th scope="col" class="px-2 py-3 w-[1%]"></th>
                <th scope="col" class="px-2 py-3 w-[6%]">Fecha de recepcion</th>
                <th scope="col" class="px-2 py-3 w-[5%]">Hora de recepcion</th>
                <th scope="col" class="px-2 py-3">
                    Cliente
                    <input 
                        type="text"
                        id="busqueda"
                        name="busqueda"
                        ref="clientFilter"
                        class="h-8 w-40 rounded border px-3"
                        v-model="props.filters['cliente']"
                        @input="handleClientFilter">
                </th>
                <th scope="col" class="px-2 py-3 w-[1%]">
                    Cesavedac
                </th>
                <th scope="col" class="px-2 py-3 w-[1%]">
                    Supervisar
                </th>
                <th scope="col" class="px-2 py-3">
                    H. C.
                </th>
                <th scope="col" class="px-2 py-3">
                    C. C.
                </th>
                <th scope="col" class="px-2 py-3 w-[1%]">
                    Croquis
                </th>
                <th scope="col" class="px-2 py-3 w-[6%]">
                    Fecha resultados analistas
                </th>
                <th scope="col" class="px-2 py-3 w-[6%]">
                    Fecha resultados clientes
                </th>
                <th scope="col" class="px-2 py-3 w-[1%]">
                    Reporte entregado
                </th>
                <th scope="col" class="px-2 py-3 w-[5%]">
                    Desecho de muestras
                </th>
                <th scope="col" class="px-2 py-3">
                    PDF
                </th>
        </tr>
    </thead>
</template>