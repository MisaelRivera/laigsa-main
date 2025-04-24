<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parameterCombination: Object,
    });
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto mt-7 bg-slate-50 border-2 p-4">
            <div class="grid grid-cols-12">
                <p class="col-span-2 font-bold">Pametro</p>
                <p class="col-span-2 font-bold">Metodo</p>
                <p class="col-span-1 font-bold">Unidad</p>
                <p class="col-span-1 font-bold">LCP</p>
                <p class="col-span-2 font-bold">Alias</p>
                <p class="col-span-1 font-bold">Categoria</p>
                <p class="col-span-1 font-bold">Abreviacion</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2">{{ parameterCombination.parametro.parametro }}</p>
                <p class="col-span-2">{{ parameterCombination.metodo.nombre }}</p>
                <p class="col-span-1">{{ parameterCombination.unidad.nombre }}</p>
                <p class="col-span-1">{{ parameterCombination.lcp.valor }}</p>
                <p class="col-span-2">{{ parameterCombination.alias }}</p>
                <p class="col-span-1">{{ parameterCombination.clasificacion }}</p>
                <p class="col-span-1">{{ parameterCombination.abreviacion }}</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2 font-bold">Orden de acomodo</p>
                <p class="col-span-2 font-bold">Subcontratado</p>
                <p class="col-span-1 font-bold">Compuesto</p>
                <p class="col-span-1 font-bold">Ema</p>
                <p class="col-span-1 font-bold">CNA</p>
                <p class="col-span-1 font-bold">SSA</p>
                <p class="col-span-1 font-bold">Supervisar</p>
            </div>
            <div class="grid grid-cols-12">
                <p class="col-span-2">{{ parameterCombination.arrange }}</p>
                <p class="col-span-2">{{ parameterCombination.subcontratado ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.compuesto ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.ema ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.cna ? 'Si':'No'}}</p>
                <p class="col-span-1">{{ parameterCombination.ssa ? 'Si':'No' }}</p>
                <p class="col-span-1">{{ parameterCombination.supervisar ? 'Si':'No' }}</p>
            </div>
            <table class="border bg-slate-100 w-5/12 mt-10">
                <thead>
                    <tr>
                        <th class="border py-1 px-2">Analistas</th>
                        <th class="border py-1 px-2">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </AuthenticatedLayout>
</template>