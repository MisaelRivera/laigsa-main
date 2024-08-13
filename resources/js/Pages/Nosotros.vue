<script setup>
    import { ref } from 'vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import SimpleCard from '@/Components/Shared/SimpleCard.vue';
    const props = defineProps({
        title: String
    });
</script>

<template>
    <GuestLayout>
            <div class="w-6/12 mx-auto">
                <SimpleCard
                    borders-prop="y">
                    <h1 class="text-3xl text-center mb-4">Acerca de Nosotros</h1>
                    <p class="text-justify leading-normal">
                        Somos una organización que desarrolla actividades como laboratorio de ensayos en el analisis de agua, alimentos, bebidas alcohólicas y ensayos metalúrgicos, desarrollamos también actividades fuera de las instalaciones como muestreos, ensayos de pH, conductividad electrolítica, materia flotante, oxigeno disuelto, cloro y temperatura, todo con completo apego al sistema de gestión implementado.
                    </p>
                </SimpleCard>
                <SimpleCard
                    borders-prop="y"
                    class="mt-4">
                    <h1 class="text-3xl text-center mb-4">Quejas y sugerencias</h1>
                    <p class="text-justify leading-normal">
                        Somos una organización que desarrolla actividades como laboratorio de ensayos en el analisis de agua, alimentos, bebidas alcohólicas y ensayos metalúrgicos, desarrollamos también actividades fuera de las instalaciones como muestreos, ensayos de pH, conductividad electrolítica, materia flotante, oxigeno disuelto, cloro y temperatura, todo con completo apego al sistema de gestión implementado.
                    </p>
                </SimpleCard>
            </div>
    </GuestLayout>
</template>
<style>
    .my-height {
        min-height: calc(100vh - 80px);
    }
</style>