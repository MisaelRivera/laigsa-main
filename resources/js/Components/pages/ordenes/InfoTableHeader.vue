<script setup>
    import { ref } from 'vue';
    const props = defineProps({
        filters: Object,
    });

    const emit = defineEmits(['update:filters']);

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
    <thead class="bg-gray-50 dark:bg-neutral-700">
        <tr>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[3%]">
                Folio
                <input 
                    type="text"
                    id="busqueda"
                    name="busqueda"
                    class="h-8 w-20 rounded border px-3"
                    v-model="folioFilter"
                    @input="handleFolioFilter">
            </th>
            <th scope="col" class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[1%]">No.</th>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[7%]">
                Fecha de recepcion
            </th>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[7%]">
                Hora de recepcion
            </th>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[20%]">
                Cliente
                <input 
                    type="text"
                    id="busqueda"
                    name="busqueda"
                    class="h-8 w-40 rounded border px-3"
                    v-model="clientFilter"
                    @input="handleClientFilter">
            </th>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[7%]">
                Fecha resultados clientes
            </th>
            <th 
                scope="col" 
                class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[3%]">
                Reporte aprobado
            </th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[3%]"></th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[8%]">No. de factura</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400">Editar</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400">Pagado</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[8%]">Fecha de pago</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[3%]">p.p.d</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[3%]">Compl.</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[8%]">No. de O. C.</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[8%]">Numero de cotización</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400">Termómetro</th>
            <th class="px-2 py-3 text-start text-xs font-medium text-gray-500 dark:text-neutral-400 w-[8%]">Temp °C</th>
        </tr>
    </thead>
</template>