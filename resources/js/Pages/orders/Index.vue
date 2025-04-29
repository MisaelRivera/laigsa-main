<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowLink from '@/Components/Shared/ShowLink.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import CircleSwitch from '@/Components/Shared/CircleSwitch.vue';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';
    import FiltersHeader from '@/Components/pages/ordenes/FiltersHeader.vue';
    import TableHeader from '@/Components/pages/ordenes/TableHeader.vue';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    import { usePermission } from '@/composables/permissions';
    const { getMessage, getError } = useMessages();
    const { getRoles } = usePermission();

    const props = defineProps(
        {
            ordersProp: {
                required: true,
            },
            
            filtersProp: Object
        }
    );

    const filtersCopy = reactive({ ...props.filters });

    const applyFilters = () => {
        router.visit(route('orders.index', filtersCopy), {
            preserveState: true,
            preserveScroll: true,
            method: 'get',
        });
    };

    const updateFilter = ({ key, value }) => {
        filtersCopy[key] = value;
        applyFilters();
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
        <div 
            class="mx-auto mt-3">
            <FiltersHeader
                :links="ordersProp.links"
                :filters="filtersCopy"
                @update:filters="updateFilter"/>
            <table 
                class="text-sm text-left text-gray-500 dark:text-gray-400 mx-auto"
                :class="{'w-full': getRoles().includes('admin'), 'w-5/12': getRoles().includes('analist')}">
                <TableHeader
                    :filters="filtersCopy"
                    @update:filters="updateFilter"/>
                <tbody>
                    <tr 
                        class="border-b dark:border-gray-700" 
                        v-for="order in ordersProp.data"
                        :key="order.id">
                        <td scope="row"
                            class="px-2 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <Dropdown
                                align="center"
                                width="64">
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
                            {{ order.muestras_count }}
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
                        <td class="px-2 py-3 text-xs" v-if="!getRoles().includes('analist')">
                            {{ order.cliente.cliente }}
                        </td>
                        <template v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
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
                        </template>
                        <td class="px-2 py-3 text-xs">
                            {{ order.fecha_recepcion ? addDaysWithoutSundays(order.fecha_recepcion, 8):'---' }}
                        </td>
                        <template v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
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
                        </template>
                        <td class="px-2 py-3"></td>
                        <td class="px-2 py-3" v-if="!getRoles().includes('analist') && !getRoles().includes('general')">
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