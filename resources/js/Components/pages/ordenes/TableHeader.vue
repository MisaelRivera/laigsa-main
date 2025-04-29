<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { usePermission } from '@/composables/permissions';
    const props = defineProps({
        filters: Object,
    });

    const emit = defineEmits(['update:filters']);
    const { getRoles } = usePermission();
    const clientFilter = ref(Object.keys(props.filters).includes('cliente') ? props.filters['cliente']:null);
    const folioFilter = ref(Object.keys(props.filters).includes('folio') ? props.filters['folio']:null);

    const handleClientFilter = (ev) => {
        const value = ev.target.value;
        emit('update:filters', { key: 'cliente', value });
    };

    const handleFolioFilter = (ev) => {
        const value = ev.target.value;
        emit('update:filters', { key: 'folio', value });
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
                    class="h-8 w-20 rounded border px-3"
                    v-model="folioFilter"
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
                <th 
                    scope="col" 
                    class="px-2 py-3"
                    v-if="!getRoles().includes('analist')">
                    Cliente
                    <input 
                        type="text"
                        id="busqueda"
                        name="busqueda"
                        class="h-8 w-40 rounded border px-3"
                        v-model="clientFilter"
                        @input="handleClientFilter">
                </th>
                <template v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
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
                </template>
                <th scope="col" class="px-2 py-3 w-[6%]">
                    Fecha resultados analistas
                </th>
                <template v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
                    <th scope="col" class="px-2 py-3 w-[6%]">
                        Fecha resultados clientes
                    </th>
                    <th scope="col" class="px-2 py-3 w-[1%]">
                        Reporte entregado
                    </th>
                </template>
                <th scope="col" class="px-2 py-3 w-[5%]">
                    Desecho de muestras
                </th>
                <th scope="col" class="px-2 py-3" v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
                    PDF
                </th>
        </tr>
    </thead>
</template>