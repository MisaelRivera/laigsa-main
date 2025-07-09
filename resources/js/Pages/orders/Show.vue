<script setup>
    import { ref, reactive } from 'vue';
    import { usePage, router, Link } from '@inertiajs/vue3';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';  
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    import { usePermission } from '@/composables/permissions';
    import MyModal from '@/Components/Shared/MyModal.vue';

    const props = defineProps({
        order: Object,
        numeroMuestrasActual: Number,
    });

    const { getMessage, getError } = useMessages();
    if (getMessage()) {
        push.success(getMessage());
    }

    if (getError()) {
        push.error(getError());
    }

    const { getRoles } = usePermission();

    const isDeleteModalVisible = ref(false);
    const isDeleteSampleModalVisible = ref(false);
    const deleteSample = ref(null);
    const isPreservationDisable = ref([]);
    for (let i = 0; i < props.order.muestras.length; i++) {
        isPreservationDisable.value.push(true);
    }

    const numeroMuestras = ref('');
    const orderInfoEditDisable = ref(true);

    const partialOrderInfo = reactive({
        numero_cotizacion: props.order.numero_cotizacion,
        numero_termometro: props.order.numero_termometro,
        temperatura: props.order.temperatura,
    });

    const editAllUrl = props.order.aguas_alimentos === 'Aguas' ? `water_samples.edit_all`:`food_samples.edit_all`,
        editUrl = props.order.aguas_alimentos === 'Aguas' ? `/water_samples`:`/food_samples`;

    const handleAddSubmit = () => {
        let url = '';
        if (props.order.v_libreta_resultados) {
            if (props.order.aguas_alimentos === 'Aguas') {
                url = route(`water_samples.create_v2`, { order:props.order.id, numero_muestras: numeroMuestras.value});
            } else {
                url = `/food_samples/add_samples/${props.order.id}/${numeroMuestras.value}`;
            }
        } else {
            if (props.order.aguas_alimentos === 'Aguas') {
                url = route(`water_samples.create`, { order:props.order.id, numero_muestras: numeroMuestras.value});
            } else {
                url = `/food_samples/create/${props.order.id}/${numeroMuestras.value}`;
            }
        }
        router.get(url);
    }; 
    const handlePreservationSubmit = (form$) => {
        router.put(`/orders/edit-preservation/${form$.data.id_muestra}`, form$.requestData, {
            onSuccess: () => {
                push.success(`La preservacion de la muestra se ha editado correctamente`);
            },
            preserveScroll: true,
        });
    };

    const handleToggleEditPreservation = (index) => {
        console.log(isPreservationDisable.value);
        isPreservationDisable.value[index] = !isPreservationDisable.value[index];
    };

    const handleDeleteOrderModal = (orderId) => {
        const url = `/orders/${orderId}`;
        router.visit(url, { method: 'DELETE' });
    };

    const handleDeleteSampleModalOpening = (sample) => {
        deleteSample.value = sample;
        isDeleteSampleModalVisible.value = true;
    };

    const handleDeleteSampleModal = () => {
        const sampleType = props.order.aguas_alimentos === 'Aguas' ? 'water_samples':'food_samples';
        const url = `/${sampleType}/${deleteSample.value.id}`;
        router.visit(url, { method: 'DELETE' });
    };

    const handleEditPartialInfo = (orderId) => {
        if (!partialOrderInfo.numero_cotizacion)
            partialOrderInfo.numero_cotizacion = null;

        if (!partialOrderInfo.numero_termometro)
            partialOrderInfo.numero_termometro = null;

        if (!partialOrderInfo.temperatura)
            partialOrderInfo.temperatura = null;
        const url = `/orders/editPartialInfo/${orderId}`
        router.visit(url, {method: 'PATCH', data: partialOrderInfo});
    };
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full mx-auto mt-8">
            <div class="w-9/12 mx-auto" v-if="getRoles().includes('admin')">
                <CreateTitle 
                    title="Datos de la orden"
                    backLink="/orders"
                    :ownLink="`/orders/show/`"/>
                    <section class="grid grid-cols-12">
                        <table class="col-span-12 text-sm text-left text-gray-500 dark:text-gray-400 border border-slate-300">
                            <thead class="text-xs text-gray-700 bg-gray-200">
                                <tr>
                                    <th class="px-4 py-3 w-64 border border-slate-300">Encargado</th>
                                    <th class="px-4 py-3 w-64 border border-slate-300">Teléfono</th>
                                    <th class="px-4 py-3 w-64 border border-slate-300">Correo</th>
                                    <th class="px-4 py-3 w-64 border border-slate-300">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                        {{ order.cliente.encargado }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                        {{ order.cliente.telefono }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                        {{ order.cliente.correo_electronico }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50 whitespace-normal">
                                        {{ order.cliente.observaciones }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
            </div>
            <div class="p-3">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-slate-300 border">
                    <thead class="text-xs text-gray-700 bg-gray-200">
                        <tr>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Folio</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">C. T.</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300"></th>
                            <th scope="col" class="px-4 py-3 border border-slate-300"></th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Cliente</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">No.</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Sitio de muestreo</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">No. de termómetro</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Temp °C</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Fecha de recepcion</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Hora de recepcion</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300">Fecha de resultados</th>
                            <th scope="col" class="px-4 py-3 border border-slate-300"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                MFQ-{{ order.folio }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.cliente.cuarto_transitorio ? 'Si':'No' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                <template v-if="order.aguas_alimentos === 'Aguas'">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full">

                                    </div>
                                </template>
                                <template v-else>
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full">

                                    </div>
                                </template>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                <EditLink 
                                    :url="`/orders/edit/${order.id}`"/>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.cliente.cliente }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ numeroMuestrasActual }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.direccion_muestreo }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.numero_termometro }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.temperatura }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.fecha_recepcion ?? '---' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.hora_recepcion ? order.hora_recepcion:'---' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.hora_recepcion ? addDaysWithoutSundays(order.fecha_recepcion, 8):'---' }}
                            </td>
                            <td class="bg-gray-50">
                                <DeleteButton
                                    :funct="() => {isDeleteModalVisible = true;}"
                                    :args="[]"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center justify-between mt-4 gap-3">
                    <!--<CustomInput 
                        label="No. cotizacion"
                        :disabled="orderInfoEditDisable"
                        v-model="partialOrderInfo.numero_cotizacion"
                        :label-classes="['text-xs']"
                        size="col-span-2"/>
                    <CustomInput 
                        :disabled="orderInfoEditDisable"
                        size="col-span-1"
                        :label-classes="['text-xs']"
                        v-model="partialOrderInfo.numero_termometro"
                        label="No. termómetro"/>
                    <CustomInput 
                        :disabled="orderInfoEditDisable"
                        size="col-span-1"
                        :label-classes="['text-xs']"
                        :input-classes="['bg-red-300', 'disabled:bg-slate-300']"
                        v-model="partialOrderInfo.temperatura"
                        label="Temperatura °C"/>
                    <button 
                        class="py-1 px-3 bg-yellow-500 text-white rounded-md h-10 mt-5 col-span-1"
                        @click="orderInfoEditDisable = !orderInfoEditDisable">
                        ¶
                    </button>
                    <button 
                        class="py-1 px-3 bg-blue-500 text-white rounded-md h-10 mt-5 disabled:opacity-70 col-span-1"
                        :disabled="orderInfoEditDisable"
                        @click="() => handleEditPartialInfo(order.id)">
                        Editar
                    </button>
                    <Link 
                        class="col-start-11 col-span-2 rounded text-white bg-blue-500 py-1 px-2 row-start-1 self-center text-center"
                        :href="route(editAllUrl, {folio: order.folio})">
                        Editar todas las muestras
                    </Link>-->
                    <div class="flex items-center gap-3">
                        <div class="mb-4 w-44">
                            <label 
                                for="numero-cotizacion"
                                class="text-xs">No. cotizacion</label>
                            <input 
                                type="text"
                                id="numero-cotizacion"
                                v-model="partialOrderInfo.numero_cotizacion"
                                class="w-full border rounded py-0.5 px-2.5 text-sm"
                                :disabled="orderInfoEditDisable">
                        </div>
                        <div class="mb-4 w-24">
                            <label 
                                for="numero-termometro"
                                class="text-xs">No. termómetro</label>
                            <input 
                                type="text"
                                id="numero-termometro"
                                v-model="partialOrderInfo.numero_termometro"
                                class="w-full border rounded py-0.5 px-2.5 text-sm"
                                :disabled="orderInfoEditDisable">
                        </div>
                        <div class="mb-4 w-24">
                            <label 
                                for="temperatura"
                                class="text-xs">Temperatura °C</label>
                            <input 
                                type="text"
                                id="temperatura"
                                v-model="partialOrderInfo.temperatura"
                                class="w-full border rounded py-0.5 px-2.5 text-sm"
                                :disabled="orderInfoEditDisable">
                        </div>
                        <div class="mb-4 w-34">
                            <label 
                                for="entregar-a"
                                class="text-xs">Entrega:</label>
                            <input 
                                type="text"
                                id="entregar"
                                class="w-full border rounded py-0.5 px-2.5 text-xs"
                                :disabled="orderInfoEditDisable">
                        </div>
                         <button 
                            class="py-0.5 px-3 bg-yellow-500 text-white rounded-md h-8 col-span-1"
                            @click="orderInfoEditDisable = !orderInfoEditDisable">
                            ¶
                        </button>
                        <button 
                            class="py-0.5 px-1.5 bg-blue-500 text-white rounded-md h-8 disabled:opacity-70 col-span-1"
                            :disabled="orderInfoEditDisable"
                            @click="() => handleEditPartialInfo(order.id)">
                            Editar
                        </button>
                    </div>
                    <div class="flex">
                        <form 
                            class="flex gap-2 items-center"
                            @submit.prevent="handleAddSubmit">
                            <div class="flex-col mb-5">
                                <label 
                                    class="block"
                                    for="muestras-adicionales">
                                    Muestras a agregar
                                </label>
                                <input 
                                    type="number" 
                                    min="1"
                                    class="w-32 py-1.5 px-3 h-8 rounded-md col-span-2 border-slate-300 border-2 outline-none focus:border-blue-300 focus:border-4"
                                    placeholder="Ingresa No."
                                    v-model="numeroMuestras"
                                    id="muestras-adicionales">
                            </div>
                            <button 
                                class="py-1 px-2 font-bold text-white w-10 h-8 bg-yellow-500 text-xl rounded-lg">
                                +
                            </button>
                        </form>
                        <button 
                            class="bg-green-500 text-white py-1 px-2"
                            v-if="order.numero_cotizacion">
                            PDF
                        </button>
                        <button class="btn btn-primary self-center ml-3">
                            Editar muestras
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 items-start">
                    <div 
                        class="p-1 px-1 border-2 border-slate-400 rounded-lg min-h-0"
                        v-for="(sample, index) in order.muestras"
                        :key="index">
                        <div class="grid grid-cols-2">
                            <p class="bg-slate-500 font-bold py-1.5 px-2">
                                Folio
                                <EditLink 
                                    :url="`${editUrl}/${sample.id}/edit`"/>
                            </p>
                            <p class="bg-slate-500 py-1.5 px-2 flex justify-between">
                                MFQ-{{ order.folio }} - {{ sample.numero_muestra }}
                                <DeleteButton
                                    :funct="() => handleDeleteSampleModalOpening(sample)"
                                    :args="[]"/>
                            </p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Tipo de muestra</p>
                            <p class="py-1.5 px-2"> {{ sample.tipo_muestra }}</p>
                        </div>
                        <template v-if="!getRoles().includes('analist')">
                            <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Identificación de muestra</p>
                                <p class="py-1.5 px-2"> {{ sample.identificacion_muestra_relacion.identificacion_muestra }}</p>
                            </div>
                            <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Latitud</p>
                                <p class="py-1.5 px-2"> {{ sample.identificacion_muestra_relacion.latitud }}</p>
                            </div>
                            <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Longitud</p>
                                <p class="py-1.5 px-2"> {{ sample.identificacion_muestra_relacion.longitud }}</p>
                            </div>
                        </template>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Parametros:</p>
                            <p class="py-1.5 px-2"> {{ sample.parametros }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Caracteristicas:</p>
                            <p class="py-1.5 px-2"> {{ sample.caracteristicas }}</p>
                        </div> 
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Alimentos'">
                            <p class="font-bold py-1.5 px-2">Peso/Vol de la muestra g./l:</p>
                            <p class="py-1.5 px-2"> {{ sample.peso_muestra }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Alimentos'">
                            <p class="font-bold py-1.5 px-2">Temperatura °C:</p>
                            <p class="py-1.5 px-2"> {{ sample.temperatura }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Fecha de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Hora de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_muestreo}}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Tipo de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.tipo_muestreo }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto 4' || sample.tipo_muestreo === 'Compuesto 6'">
                            <p class="font-bold py-1.5 px-2">Fecha de fin de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_final_muestreo }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto 4' || sample.tipo_muestreo === 'Compuesto 6'">
                            <p class="font-bold py-1.5 px-2">Hora de fin de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_final_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="!getRoles().includes('analist')">
                            <p class="font-bold py-1.5 px-2">Muestreador:</p>
                            <p class="py-1.5 px-2"> {{ sample.muestreador }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">Cloro:</p>
                            <p class="py-1.5 px-2"> {{ sample.cloro }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.cloro === 'Si' || sample.cloro === 'No' ">
                            <p class="font-bold py-1.5 px-2">Valor del cloro:</p>
                            <p class="py-1.5 px-2"> {{ sample.valor_cloro === 'Si' ? sample.valor_cloro:'< 0.1' }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">SIRALAB:</p>
                            <p class="py-1.5 px-2"> {{ sample.siralab ? 'Si':'No' }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto 4' || sample.tipo_muestreo === 'Compuesto 6'">
                            <p class="font-bold py-1.5 px-2">Fecha de composicion:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_composicion }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto 4' || sample.tipo_muestreo === 'Compuesto 6'">
                            <p class="font-bold py-1.5 px-2">Hora de composicion:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_composicion }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">Tratada biológicamente:</p>
                            <p class="py-1.5 px-2"> {{ sample.tratada_biologicamente ? 'Si':'No' }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">pH:</p>
                            <p class="py-1.5 px-2"> {{ sample.pH }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                                v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">Conductividad (uS/cm):</p>
                            <p class="py-1.5 px-2"> {{ sample.conductividad }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">pH Cr VI:</p>
                            <p class="py-1.5 px-2"> {{ sample.ph_cromo_hexavalente }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">Preservación correcta:</p>
                            <p class="py-1.5 px-2"> {{ sample.preservacion_correcta }}</p>
                        </div>
                        <template v-if="sample.tipo_muestreo === 'Compuesto 4' || sample.tipo_muestreo === 'Compuesto 6'">
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 1 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_1 }}</p>
                            </div>
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 2 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_2 }}</p>
                            </div>
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 3 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_3 }}</p>
                            </div>
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 4 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_4 }}</p>
                            </div>
                        </template>
                        <template v-if="sample.tipo_muestreo === 'Compuesto 6'">
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 5 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_5 }}</p>
                            </div>
                            <div 
                                class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Flujo 6 (l/s):</p>
                                <p class="py-1.5 px-2"> {{ sample.flujo_6 }}</p>
                            </div>
                        </template>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="order.aguas_alimentos === 'Aguas'">
                            <p class="font-bold py-1.5 px-2">Preservacion correcta</p>
                            <p class="py-1.5 px-2">
                                <Vueform
                                    :endpoint="false"
                                    @submit="handlePreservationSubmit"
                                    :scroll-to-invalid="false"
                                    :columns="{container: 12, wrapper:12}">
                                    <RadiogroupElement 
                                        name="preservacion_correcta"
                                        :items="[
                                            'Si',
                                            'No',
                                            'N/A'
                                        ]"
                                        :remove-class="{
                                            wrapper: 'flex-col',
                                            
                                        }"

                                        :add-class="{
                                            wrapper: ['gap-1', 'text-xs'],
                                            container: 'items-center',
                                            text: 'self-center',
                                        }"
                                        :add-classes="{
                                            RadiogroupRadio: {
                                                container: 'items-center',
                                                input: 'max-h-3 max-w-3'
                                            },
                                        }"
                                        :columns="{container: 5, wrapper:12}"
                                        :disabled="isPreservationDisable[index]"
                                        :default="sample.preservacion_correcta">  
                                    </RadiogroupElement>
                                    <HiddenElement 
                                        name="id_muestra"
                                        :default="sample.id"/>
                                    <button 
                                        class="py-1 px-2 rounded bg-yellow-500 text-white col-span-2 cursor-pointer"
                                        @click="() => handleToggleEditPreservation(index)"
                                        type="button">
                                        ¶
                                    </button>
                                    <button 
                                        class="py-1 px-2 rounded bg-blue-500 text-white col-span-4 disabled:bg-slate-100 disabled:text-black"
                                        :disabled="isPreservationDisable[index]">
                                        Editar
                                    </button>
                                </Vueform>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Notivue v-slot="item">
            <Notification :item="item" />
        </Notivue>
        <MyModal
            v-model="isDeleteModalVisible"
            title="Eliminar orden"
            @ok="() => handleDeleteOrderModal(order.id)"
            @close-from="isDeleteModalVisible = false"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }">
            <p>Estas seguro de que deseas eliminar la orden MFQ-{{ order.folio }}</p>
        </MyModal>
        <MyModal
            v-model="isDeleteSampleModalVisible"
            title="Eliminar Muestra"
            @ok="() => handleDeleteSampleModal(deleteSample.id)"
            @close-from="isDeleteSampleModalVisible = false"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }">
            <p>Estas seguro de que deseas eliminar la muestra MFQ-{{ order.folio }} - {{ deleteSample.numero_muestra }}</p>
        </MyModal>
    </AuthenticatedLayout>
</template>