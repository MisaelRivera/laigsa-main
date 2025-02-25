<script setup>
    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';
    import VueMultiselect from 'vue-multiselect';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ShowLink from '@/Components/Shared/ShowLink.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import CircleSwitch from '@/Components/Shared/CircleSwitch.vue';
    import { Head } from '@inertiajs/vue3';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import IndexFilter from '@/Components/Shared/IndexFilter.vue';
    import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';
    import AdvancedCustomInput from '@/Components/Shared/AdvancedCustomInput.vue';
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
    
    const clientFilter = ref(null);
    const folioFilter = ref(null);
    const muestreadorFilter = ref(null);
    const cesavedacFilter = ref(null);
    const supervisionFilter = ref(null);
    const siralabFilter = ref(null);
    const filters = reactive({
        cliente: Object.keys(props.filtersProp).includes('cliente') ? props.filtersProp:null,
        folio: Object.keys(props.filtersProp).includes('folio') ? props.filtersProp:null,
        muestreador: Object.keys(props.filtersProp).includes('muestreador') ? props.filtersProp:null,
        cesavedac: Object.keys(props.filtersProp).includes('cesavedac') ? props.filtersProp:null,
        supervision: Object.keys(props.filtersProp).includes('supervision') ? props.filtersProp:null,
        siralab: Object.keys(props.filtersProp).includes('siralab') ? props.filtersProp:null,
    });

    const filterOptions = ref([
        'cesavedac',
        'supervisar',
        'siralab'
    ]);

    const activeFilters = ref([]);

    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.visit(route('orders.index', { byOrder: encodeURIComponent(value) }), {
            preserveState: true,
            method: 'get'
        });
    };

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

    const handleMuestreadorFilter = (ev) => {
        const filtersCopy = {};
        const value = ev.target.value;
        filters['muestreador'] = value;
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

    const handleCesavedacFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('cesavedac')) {
            activeFilters.value.push('cesavedac');
        }
        filters['cesavedac'] = value;
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

    const handleSiralabFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('siralab')) {
            activeFilters.value.push('siralab');
        }
        filters['siralab'] = value;
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

    const handleSupervisionFilter = (value) => {
        const filtersCopy = {};
        if (!activeFilters.value.includes('supervision')) {
            activeFilters.value.push('supervision');
        }
        filters['supervision'] = value;
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

    const handleRemove = (removedOption) => {
        filters[removedOption] = null;
        const filtersCopy = {};
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

    const handleClick = () => {
        if (!multiselectTest.value.includes('Texas'))
        multiselectTest.value.push('Texas');
    };

</script>
<template>
    <AuthenticatedLayout>
        <div class="w-full mx-auto mt-3">
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Ordenes"
                    :add-link="route('orders.create')"
                    :own-link="route('orders.index')"/>
                
                <Pagination 
                    :links="ordersProp.links"/>
                <div class="flex items-center" v-if="activeFilters.length > 0">
                    <VueMultiselect
                        :options="filterOptions"
                        :class="['col-span-6']"
                        v-model="activeFilters"
                        :multiple="true"
                        @remove="handleRemove"
                        placeholder="Elije una opcion">
                    </VueMultiselect>
                </div>
                <div class="flex items-center">
                    <div>
                        <label 
                            for="muestreador_filter" 
                            class="text-xs">Muestreador</label>
                        <input 
                            type="text"
                            id="muestreador-filter"
                            ref="muestreadorFilter"
                            @input="handleMuestreadorFilter"
                            class="border rounded-md w-20">
                    </div>
                    <div class="flex items-center ml-2">
                        <CustomCheckbox
                            name="cesavedac_filter"
                            id="cesavedac-filter"
                            v-model="cesavedacFilter"
                            label-text="cesavedac"
                            :label-classes="['text-xs']"
                            @change-state="handleCesavedacFilter"/>
                    </div>
                    <div class="flex items-center ml-2">
                        <CustomCheckbox
                            name="supervision_filter"
                            id="supervision-filter"
                            label-text="supervision"
                            v-model="supervisionFilter"
                            :label-classes="['text-xs']"
                            @change-state="handleSupervisionFilter"/>
                    </div>
                    <div class="flex items-center ml-2">
                        <CustomCheckbox
                            name="siralab_filter"
                            id="siralab-filter"
                            label-text="siralab"
                            v-model="siralabFilter"
                            :label-classes="['text-xs']"
                            @change-state="handleSiralabFilter"/>
                    </div>
                </div>
            </div>
            
            <!--<Alert 
                :message="usePageCons.props.flash.error"
                v-if="usePageCons.props.flash.error"
                type="error"
                closable/>
            <Alert 
                :message="usePageCons.props.flash.message"
                v-if="usePageCons.props.flash.message"
                class="mb-2"
                type="success"
                closable/>-->
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
                                            <li class="px-3 py-1 max-w-full" v-if="order.aguas_alimentos === 'Aguas'">
                                                <span class="font-black">Cloro: </span>
                                                {{ muestra.cloro }}
                                            </li>
                                            <li class="px-3 py-1 max-w-full" v-if="order.aguas_alimentos === 'Aguas'">
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
                            {{ order.hora_recepcion ? order.hora_recepcion.substr(0, 5):'---' }}
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