<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowLink from '@/Components/Shared/ShowLink.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import CircleSwitch from '@/Components/Shared/CircleSwitch.vue';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';
    import FiltersHeader from '@/Components/pages/ordenes/FiltersHeader.vue';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    const { getMessage, getError } = useMessages();

    const props = defineProps(
        {
            ordersProp: {
                required: true,
            },
            
            filtersProp: Object
        }
    );

    const clientFilter = ref(Object.keys(props.filtersProp).includes('cliente') ? props.filtersProp:null);
    const folioFilter = ref(Object.keys(props.filtersProp).includes('folio') ? props.filtersProp:null);
    const filters = reactive({
        cliente: Object.keys(props.filtersProp).includes('cliente') ? props.filtersProp.cliente:null,
        folio: Object.keys(props.filtersProp).includes('folio') ? props.filtersProp.folio:null,
        muestreador: Object.keys(props.filtersProp).includes('muestreador') ? props.filtersProp.muestreador:null,
        cesavedac: Object.keys(props.filtersProp).includes('cesavedac') ? props.filtersProp.cesavedac:null,
        supervision: Object.keys(props.filtersProp).includes('supervision') ? props.filtersProp.supervision:null,
        siralab: Object.keys(props.filtersProp).includes('siralab') ? props.filtersProp.siralab:null,
    });

    const handleClientFilter = (ev) => {
        const filtersCopy = {};
        const value = ev.target.value;
        filters['cliente'] = value;
        Array.from(Object.keys(filters)).forEach(item => {
            if (filters[item] !== null && filters[item] !== '') {
                filtersCopy[item] = filters[item];
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
        filters['folio'] = value;
        Array.from(Object.keys(filters)).forEach(item => {
            if (filters[item] !== null && filters[item] !== '') {
                filtersCopy[item] = filters[item];
            }
        });
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            method: 'get',
        });
    };

    if (getMessage()) {
        push.success(getMessage());
    }

    if (getError()) {
        push.success(getError());
    }

    const handleClick = () => {
        if (!multiselectTest.value.includes('Texas'))
        multiselectTest.value.push('Texas');
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-full mx-auto mt-3">
            <FiltersHeader
                :links="ordersProp.links"
                :filters-prop="filtersProp"/>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                <tbody>
                    <tr 
                        class="border-b dark:border-gray-700" 
                        v-for="order in ordersProp.data"
                        :key="order.id">
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
                                                <span class="font-black">Caracteriticas: </span>
                                                {{ muestra.caracteristicas }}
                                            </li>
                                            <li class="px-3 py-1 max-w-full">
                                                <span class="font-black">Muestreador: </span>
                                                {{ muestra.muestreador }}
                                            </li>
                                            <li class="px-3 py-1 max-w-full" v-if="order.aguas_alimentos === 'Aguas'">
                                                <span class="font-black">pH: </span>
                                                {{ muestra.pH }}
                                            </li>
                                            <li class="px-3 py-1 max-w-full">
                                                <span class="font-black">Parametros: </span>
                                                {{ muestra.parametros }}
                                            </li>
                                            <li 
                                                class="px-3 py-1 max-w-full" 
                                                v-if="order.aguas_alimentos === 'Aguas'">
                                                <span class="font-black">Cloro: </span>
                                                {{ muestra.cloro }}
                                            </li>
                                            <li 
                                                class="px-3 py-1 max-w-full" 
                                                v-if="order.aguas_alimentos === 'Aguas'">
                                                <span class="font-black">Valor cloro: </span>
                                                {{ muestra.valor_cloro }}
                                            </li>
                                        </ul>
                                    </template>
                                </template>
                            </Dropdown>
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.numero_muestras }}
                        </td>
                        <td class="px-2 py-3">
                            <template v-if="order.aguas_alimentos === 'Aguas'">
                                <div class="w-6 h-6 bg-blue-500 rounded-full">

                                </div>
                            </template>
                            <template v-else>
                                <div class="w-6 h-6 bg-yellow-500 rounded-full">

                                </div>
                            </template>
                        </td>
                        <td class="px-2 py-3">
                            <ShowLink :url="`/orders/show/${order.id}`"/>
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.fecha_recepcion ?? '---' }}
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.hora_recepcion ? order.hora_recepcion:'---' }}
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.cliente.cliente }}
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                v-if="order.cesavedac"
                                :value="order.reporte_cesavedac_entregado"
                                :key="order.id"
                                url="/orders/toggle-cesavedac"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                v-if="order.supervision !== false"
                                :value="order.supervision"
                                :key="order.id"
                                url="/orders/toggle-supervision"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                v-if="order.siralab"
                                :value="order.siralab.hoja_campo"
                                :key="order.id"
                                url="/orders/toggle-hoja-campo"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                v-if="order.siralab"
                                :value="order.siralab.cadena_custodia"
                                :key="order.id"
                                url="/orders/toggle-cadena-custodia"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                v-if="order.siralab"
                                :value="order.siralab.croquis"
                                :key="order.id"
                                url="/orders/toggle-croquis"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.fecha_recepcion ? addDaysWithoutSundays(order.fecha_recepcion, 8):'---' }}
                        </td>
                        <td class="px-2 py-3 text-xs">
                            {{ order.fecha_recepcion ? addDaysWithoutSundays(order.fecha_recepcion, 10):'---' }}
                        </td>
                        <td class="px-2 py-3">
                            <CircleSwitch
                                :value="order.reporte_entregado"
                                :key="order.id"
                                url="/orders/toggle-reporte-entregado"
                                :orderId="order.id"/>
                        </td>
                        <td class="px-2 py-3"></td>
                        <td class="px-2 py-3">
                            <a 
                                class="text-white bg-green-500 py-1 px-2 rounded-lg"
                                :href="`/orders/generate-pdf/${order.id}`">
                                PDF
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-center mt-8">
                <button @click="push.success('Something good has been pushed!')">Push</button>
                <div class="grid grid-cols-12 w-full">
                    
                    <button @click="handleClick">add</button>
                </div>
                <Notivue v-slot="item">
                    <Notification :item="item" />
                </Notivue>
            </div>
        </div>
    </AuthenticatedLayout>
</template>