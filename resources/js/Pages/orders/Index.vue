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
    import { jsPDF } from 'jspdf';
    const { getMessage, getError } = useMessages();

    const props = defineProps(
        {
            ordersProp: {
                required: true,
            },
            
            filtersProp: Object
        }
    );

    console.log(props.filtersProp);
    const clientFilter = ref(Object.keys(props.filtersProp).includes('cliente') ? props.filtersProp:null);
    const folioFilter = ref(Object.keys(props.filtersProp).includes('folio') ? props.filtersProp:null);
    const muestreadorFilter = ref(Object.keys(props.filtersProp).includes('muestreador') ? props.filtersProp:null);
    const cesavedacFilter = ref(Object.keys(props.filtersProp).includes('cesavedac') ? props.filtersProp:null);
    const supervisionFilter = ref(Object.keys(props.filtersProp).includes('supervision') ? props.filtersProp:null);
    const siralabFilter = ref(Object.keys(props.filtersProp).includes('siralab') ? props.filtersProp:null);
    const isVisiblePDF = ref(false);
    const pdfPreview = ref(null);
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

    const addImage = (doc, src, extension, x, y, width, height) => {
        const img = new Image();
        img.src = src;
        doc.addImage(img, extension, x, y, width, height);
    };

    const text = (doc, text, x, y, fontSize = 7.5, fontStyle = 'normal', fontFamily = 'helvetica') => {
        doc.setFontSize(fontSize);
        doc.setFont(fontFamily, fontStyle);
        doc.text(text, x, y);
    };

    const splitTextToSize = (doc, str, len, x, y, fontSize = 9) => {
        const wrappedText = doc.splitTextToSize(str, len);
        doc.setFontSize(fontSize);
        doc.text(wrappedText, x, y);
    };

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

    const handlePDFGeneration = (order) => {
        const doc = new jsPDF();
        addImage(doc, '/img/logo.png', 'png', 15, 10, 10, 10);
        text(doc, "LABORATORIO DE ANÁLISIS INDUSTRIALES DEL GUADIANA, S.A. DE C.V.", 27, 12, 12, 'bold');
        text(doc, "ANÁLISIS FISICOQUIMICOS Y MICROBIOLÓGICOS DE AGUAS, ALIMENTOS Y RESIDUOS PC-14/R01", 32, 17, 8.5);
        text(doc, "Libreta de recepción", 90, 30, 9, 'bold');
        text(doc, "Dirigir informe a:", 15, 35, 9, 'bold');
        text(doc, "no. de folio:", 154, 35, 9, 'bold');
        text(doc, order.folio, 173, 35, 9);
        text(doc, "Razón social/cliente:", 15, 39, 9);
        text(doc, order.cliente.cliente, 59, 39, 9);
        text(doc, "Dirección fiscal:", 15, 43, 9);
        text(doc, order.cliente.direccion_fiscal, 59, 43, 9);
        text(doc, "Sitio del muestreo:", 15, 47, 9);
        text(doc, order.cliente.direccion_muestreo, 59, 47, 9);
        text(doc, "Fecha y hora de entrega:", 15, 53, 9);
        text(doc, order.fecha_recepcion + ' ' + order.hora_recepcion, 59, 53, 9);
        text(doc, "No. de muestras:", 89, 53, 9);
        text(doc, order.numero_muestras + '', 118, 53, 9);
        text(doc, "Temperatura:", 15, 57, 9);
        text(doc, `${order.temperatura}°C`, 59, 57, 9);
        text(doc, "Termómetro No:", 89, 57, 9);
        text(doc, order.numero_termometro, 118, 57, 9);
        text(doc, "Fecha de entrega de resultados del analista:", 15, 61, 9);
        text(doc, "2025-03-10", 85, 61, 9);
        text(doc, "Fecha de entrega de resultados del cliente:", 15, 65, 9);
        text(doc, "2024-10-16", 85, 65, 9);
        text(doc, "Cotización No: 025 24", 15, 69, 9);
        doc.line(15, 75, 194, 75);
        doc.rect(15, 77, 179, 4);
        text(doc, "MFQ-14975 - 1", 16, 80, 9);
        text(doc, "Tipo de muestra:", 15, 84.5, 9);
        text(doc, "Agua residual", 59, 84.5, 9);
        text(doc, "Identificación de la muestra:", 15, 88.5, 9);
        text(doc, "Impregnado 1", 59, 88.5, 9);
        text(doc, "Latitud:", 15, 92.5, 9);
        text(doc, "24°05'06.7'' N", 59, 92.5, 9);
        text(doc, "Longitud:", 92, 92.5, 9);
        text(doc, "24°05'06.7'' N", 130, 92.5, 9);
        text(doc, "Caracteristicas de la muestra:", 15, 96.5, 9);
        text(doc, "Turbia amarilla", 59, 96.5, 9);
        text(doc, "Muestreador:", 15, 100.5, 9);
        text(doc, "JHM", 59, 100.5, 9);
        text(doc, "*pH:", 92, 100.5, 9);
        text(doc, "9", 130, 100.5, 9);
        text(doc, "pH Cr VI:", 145, 100.5, 9);
        text(doc, "N/A", 164, 100.5, 9);
        text(doc, "Tratada biologicamente:", 15, 104.5, 9);
        text(doc, "No", 59, 104.5, 9);
        text(doc, "Cloro:", 92, 104.5, 9);
        text(doc, "N/A", 130, 104.5, 9);
        text(doc, "Tipo de muestreo:", 15, 108.5, 9);
        text(doc, "Simple", 59, 108.5, 9);
        text(doc, "Fecha de muestreo:", 15, 112.5, 9);
        text(doc, "2024-10-04", 59, 112.5, 9);
        text(doc, "Preservación correcta:", 15, 116.5, 9);
        text(doc, "Si", 59, 116.5, 9);
        text(doc, "Parámetros:", 15, 120.5, 9);
        const parametros = "Potencial Hidrógeno, SST, SDT, Turbidez, Temperatura, Cianuros, Nitratos, Nitritos, Fosforo, DBO, DQO, Sulfatos, Dureza Total como CaCO3, Fluoruros, Nitrógeno de Nitratos (N-NO3 ), Coliformes Fecales, Arsénico Total, Mercurio Total, Cobre Total, Fierro Total, Cromo Total, Manganeso Total, Cadmio Total, Plomo Total, Alcalinidad, Conductividad eléctrica, se contrata fenol.";
        splitTextToSize(doc, parametros, 131, 59, 120.5);
        //doc.line(15, );
        isVisiblePDF.value = true;
       const pdfBlob = doc.output('blob');
       const pdfUrl = URL.createObjectURL(pdfBlob);
       console.log(pdfPreview.value);
       pdfPreview.value.src = pdfUrl;
    };

</script>
<template>
    <AuthenticatedLayout>
        <iframe ref="pdfPreview" style="width:100vw;height: 100vh;;" v-show="isVisiblePDF"></iframe>
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
                        <td class="px-2 py-3"></td>
                        <td class="px-2 py-3">
                            <button 
                                class="text-white bg-green-500 py-1 px-2 rounded-lg"
                                @click="() => handlePDFGeneration(order)">
                                PDF
                            </button>
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