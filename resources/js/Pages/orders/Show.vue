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
    import MyModal from '@/Components/Shared/MyModal.vue';

    const props = defineProps({
        order: Object,
    });

    const { getMessage, getError } = useMessages();
    if (getMessage()) {
        push.success(getMessage());
    }

    if (getError()) {
        push.error(getError());
    }

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

    const handleAddSubmit = () => {
        let url = '';
        if (props.order.v_libreta_resultados) {
            url = `/water_samples/create/v2/${props.order.folio}/${numeroMuestras.value}/${props.order.numero_muestras}`;
        } else {
            url = `/water_samples/create/${props.order.folio}/${numeroMuestras.value}/${props.order.numero_muestras}`;
        }
        router.visit(url);
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
            <div class="w-8/12 mx-auto">
                <CreateTitle 
                    title="Datos de la orden"
                    backLink="/orders"
                    :ownLink="`/orders/show/`"/>
            </div>
            <div class="bg-white p-3">
                <section class="flex">
                    <div class="w-4/12 mr-11 items-center">
                        <form 
                            class="grid grid-cols-4 gap-2"
                            @submit.prevent="handleAddSubmit">
                            <label 
                                for="muestras-adicionales">
                                Muestras a agregar
                            </label>
                            <input 
                                type="number" 
                                min="1"
                                class="w-full py-1.5 px-3 h-8 rounded-md col-span-2 border-slate-300 border-2 outline-none focus:border-blue-300 focus:border-4"
                                placeholder="Ingresa No."
                                v-model="numeroMuestras"
                                id="muestras-adicionales">
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
                    </div>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Encargado</th>
                                <th scope="col" class="px-4 py-3">Teléfono</th>
                                <th scope="col" class="px-4 py-3">Correo</th>
                                <th scope="col" class="px-4 py-3">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ order.cliente.encargado }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ order.cliente.telefono }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ order.cliente.correo_electronico }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ order.cliente.observaciones }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Folio</th>
                            <th scope="col" class="px-4 py-3">C. T.</th>
                            <th scope="col" class="px-4 py-3">No. cotizacion</th>
                            <th scope="col" class="px-4 py-3"></th>
                            <th scope="col" class="px-4 py-3"></th>
                            <th scope="col" class="px-4 py-3">Cliente</th>
                            <th scope="col" class="px-4 py-3">No.</th>
                            <th scope="col" class="px-4 py-3">Sitio de muestreo</th>
                            <th scope="col" class="px-4 py-3">No. de termómetro</th>
                            <th scope="col" class="px-4 py-3">Temp °C</th>
                            <th scope="col" class="px-4 py-3">Fecha de recepcion</th>
                            <th scope="col" class="px-4 py-3">Hora de recepcion</th>
                            <th scope="col" class="px-4 py-3">Fecha de resultados</th>
                            <th scope="col" class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    MFQ-{{ order.folio }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.cliente.cuarto_transitorio ? 'Si':'No' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ order.numero_cotizacion }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <template v-if="order.aguas_alimentos === 'Aguas'">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full">

                                    </div>
                                </template>
                                <template v-else>
                                    <div class="w-6 h-6 bg-yellow-500 rounded-full">

                                    </div>
                                </template>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <EditLink 
                                    :url="`/orders/edit/${order.id}`"/>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.cliente.cliente }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.numero_muestras }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.direccion_muestreo }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.numero_termometro }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.temperatura }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.fecha_recepcion ?? '---' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.hora_recepcion ? order.hora_recepcion.substr(0, 5):'---' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ order.hora_recepcion ? addDaysWithoutSundays(order.fecha_recepcion, 8):'---' }}
                            </td>
                            <td>
                                <DeleteButton
                                    :funct="() => {isDeleteModalVisible = true;}"
                                    :args="[]"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center">
                    <section class="grid grid-cols-12 gap-2">
                        <CustomInput 
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
                            :href="route('water_samples.edit_all', {folio: order.folio})">
                            Editar todas las muestras
                        </Link>
                    </section>
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
                                    :url="`/water_samples/${sample.id}/edit`"/>
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
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Identificación de muestra</p>
                            <p class="py-1.5 px-2"> {{ sample.identificacion_muestra }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Latitud</p>
                            <p class="py-1.5 px-2"> {{ sample.latitud }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Longitud</p>
                            <p class="py-1.5 px-2"> {{ sample.longitud }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Parametros:</p>
                            <p class="py-1.5 px-2"> {{ sample.parametros }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Caracteristicas:</p>
                            <p class="py-1.5 px-2"> {{ sample.caracteristicas }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Fecha de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Hora de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_muestreo.substr(0, 5) }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Tipo de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.tipo_muestreo }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Fecha de fin de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_final_muestreo }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Hora de fin de muestreo:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_final_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Muestreador:</p>
                            <p class="py-1.5 px-2"> {{ sample.muestreador }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Cloro:</p>
                            <p class="py-1.5 px-2"> {{ sample.cloro }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.cloro === 'Si' || sample.cloro === 'No' ">
                            <p class="font-bold py-1.5 px-2">Valor del cloro:</p>
                            <p class="py-1.5 px-2"> {{ sample.valor_cloro === 'Si' ? sample.valor_cloro:'< 0.1' }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">SIRALAB:</p>
                            <p class="py-1.5 px-2"> {{ sample.siralab ? 'Si':'No' }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Fecha de composicion:</p>
                            <p class="py-1.5 px-2"> {{ sample.fecha_composicion }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Hora de composicion:</p>
                            <p class="py-1.5 px-2"> {{ sample.hora_composicion }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Tratada biológicamente:</p>
                            <p class="py-1.5 px-2"> {{ sample.tratada_biologicamente ? 'Si':'No' }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">pH:</p>
                            <p class="py-1.5 px-2"> {{ sample.pH }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Conductividad (uS/cm):</p>
                            <p class="py-1.5 px-2"> {{ sample.conductividad }}</p>
                        </div>
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">pH Cr VI:</p>
                            <p class="py-1.5 px-2"> {{ sample.ph_cromo_hexavalente }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Preservación correcta:</p>
                            <p class="py-1.5 px-2"> {{ sample.preservacion_correcta }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 1 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_1 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 2 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_2 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 3 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_3 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_4' || sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 4 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_4 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 5 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_5 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
                            v-if="sample.tipo_muestreo === 'Compuesto_6'">
                            <p class="font-bold py-1.5 px-2">Flujo 6 (l/s):</p>
                            <p class="py-1.5 px-2"> {{ sample.flujo_6 }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
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
                                        :columns="{container: 4, wrapper:12}"
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