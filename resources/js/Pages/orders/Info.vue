<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import InfoTableHeader from '@/Components/pages/ordenes/InfoTableHeader.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    const props = defineProps({
        orders: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object
        }
    });

    const muestreadores = [
        {value: null, label: 'Elija un muestreador'},
        'RCHH',
        'JHM',
        'FESR',
        'JPMS',
        'Cliente',
        'CESAVEDAC',
        'QSM',
        'APPC',
        'LMQH',
    ];
</script>
<template>
    <AuthenticatedLayout>
        <div class="flex items-center w-12/12 mx-auto">
            <IndexTitle 
                title="Ordenes info"
                class="w-3/12"
                :own-link="route('orders.info')"/>
            <div class="flex w-9/12 justify-center">
                <Pagination 
                    :links="orders.data.links"
                    class="mr-16"/>
            </div>
        </div>
        <table class="border">
            <InfoTableHeader :filters="filters"/>
            <tbody>
                <tr 
                    class="border-b dark:border-gray-700"
                    v-for="order in orders.data">
                    <td scope="row"
                        class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <Dropdown
                            align="center"
                            width="96">
                            <template #trigger>
                                <button>
                                    MFQ-{{ order.folio }}
                                </button>
                            </template> 
                            <template #content>
                                <template v-for="muestra in order.muestras" :key="muestra.id">
                                    <ul class="list-none max-w-full" style="white-space: normal; word-wrap: break-word;">
                                        <li class="px-3 py-1 font-black max-w-full">
                                            MFQ-{{ order.folio }}-{{ muestra.numero_muestra }}
                                        </li>
                                        <li class="px-3 py-1 max-w-full">
                                            <span class="font-black">Identificacion de la muestra:</span>
                                            {{ muestra.identificacion_muestra ?  muestra.identificacion_muestra:muestra.identificacion_muestra_relacion.identificacion_muestra }} 
                                        </li>
                                        <li class="px-3 py-1 max-w-full">
                                            <span class="font-black">Parametros: </span>
                                            {{ muestra.parametros }}
                                        </li>
                                        <li>
                                            <form @submit.prevent="">
                                                <div class="grid grid-cols-3">
                                                    <div>
                                                        <label :for="`ph-${muestra.id}`">pH</label>
                                                        <input 
                                                            type="text"
                                                            :value="muestra.ph">
                                                    </div>
                                                     <div>
                                                        <label :for="`muestreador-${muestra.id}`">Muestreador</label>
                                                        <input 
                                                            type="text"
                                                            :value="muestra.muestreador">
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                        <hr class="h-[2px] bg-sky-800 border-0">
                                    </ul>
                                </template>
                            </template>
                        </Dropdown>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>