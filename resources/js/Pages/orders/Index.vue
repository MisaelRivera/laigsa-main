<script setup>
    /*import { ref, reactive } from 'vue';
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
                type: Object,
                required: true,
            },
            
            filtersProp: Object
        }
    );

    const filtersCopy = reactive({ ...props.filters });

    console.log(props.ordersProp.data);

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
    };*/
    import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import OrdersTable from '@/Components/OrdersTable.vue'
import MyPagination2 from '@/Components/MyPagination2.vue'

const props = defineProps({
  orders: Object,
  filters: Object,
})

const filters = reactive({
  sampler: props.filters.sampler || '',
  cesavedac: !!props.filters.cesavedac,
  siralab: !!props.filters.siralab,
  supervision: !!props.filters.supervision,
})

const samplerList = [
  'Irving', 'Pedro', 'Crisanta',
    'Julio', 'Miguel', 'Lizeth', 'Cliente',
    'RCHH', 'JHM', 'FESR', 'JPMS', 'QSM', 'ACL', 'APPC', 'LMQH' 
];

const applyFilters = () => {
  router.get(route('orders.index'), filters, {
    preserveState: true,
    replace: true,
  })
}

const clearFilters = () => {
  filters.sampler = ''
  filters.cesavedac = false
  filters.siralab = false
  filters.supervision = false
  applyFilters()
}
</script>
<template>
    <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Orders</h1>

    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <h2 class="text-lg font-semibold mb-2">Filters</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- Sampler Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sampler</label>
          <select v-model="filters.sampler" class="w-full border-gray-300 rounded">
            <option value="">All</option>
            <option v-for="name in samplerList" :key="name" :value="name">{{ name }}</option>
          </select>
        </div>

        <!-- CESAVEDAC Filter -->
        <div class="flex items-center space-x-2">
          <input id="cesavedac" type="checkbox" v-model="filters.cesavedac" class="h-4 w-4 text-blue-600 border border-gray-300 rounded focus:ring-blue-500" value="1"/>
          <label for="cesavedac" class="text-sm font-medium">CESAVEDAC Orders</label>
        </div>

        <!-- Siralab Filter -->
        <div class="flex items-center space-x-2">
          <input id="siralab" type="checkbox" v-model="filters.siralab" class="h-4 w-4 text-blue-600 border border-gray-300 rounded focus:ring-blue-500" value="1"/>
          <label for="siralab" class="text-sm font-medium">Siralab Samples</label>
        </div>

        <!-- Supervision Filter -->
        <div class="flex items-center space-x-2">
          <input id="supervision" type="checkbox" v-model="filters.supervision" class="h-4 w-4 text-blue-600 border border-gray-300 rounded focus:ring-blue-500" value="1"/>
          <label for="supervision" class="text-sm font-medium">Requires Supervision</label>
        </div>
      </div>

      <div class="mt-4 flex space-x-3">
        <button @click="applyFilters" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Apply Filters
        </button>
        <button @click="clearFilters" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
          Clear
        </button>
      </div>
    </div>

    <OrdersTable :orders="orders.data" />
    <MyPagination2 :links="orders.links" />
  </div>
    <!--<AuthenticatedLayout>
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
                        <template v-if="!getRoles().includes('analist') && !getRoles().includes('lector')">
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
                        <template v-if="!getRoles().includes('analist') && !getRoles().includes('lector')">
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
                        <td class="px-2 py-3" v-if="!getRoles().includes('analist') && !getRoles().includes('lector')">
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
    </AuthenticatedLayout>-->
</template>