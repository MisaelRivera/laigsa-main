<script setup>
    const props = defineProps({
        
    });
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
                    v-model="filters['folio']"
                    @input="handleFolioFilter">
            </th>
            <th scope="col" class="px-2 py-3 w-[1%]">No.</th>
            <th scope="col" class="px-2 py-3 w-[1%]">
                <button>
                    <i class="fas fa-arrow-down"></i>
                </button>
            </th>
            <th scope="col" class="px-2 py-3 w-[1%]"></th>
                <th scope="col" class="px-2 py-3 w-[5%]">Fecha de recepcion</th>
                <th scope="col" class="px-2 py-3 w-[5%]">Hora de recepcion</th>
                <th scope="col" class="px-2 py-3">
                    Cliente
                    <input 
                        type="text"
                        id="busqueda"
                        name="busqueda"
                        ref="clientFilter"
                        class="h-8 w-40 rounded border px-3"
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
                <th scope="col" class="px-2 py-3 w-[5%]">
                    Fecha resultados analistas
                </th>
                <th scope="col" class="px-2 py-3 w-[5%]">
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