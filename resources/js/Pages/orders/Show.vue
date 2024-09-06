<script setup>
    import { ref, reactive } from 'vue';
    import { usePage, router } from '@inertiajs/vue3';
    import { Alert, Modal } from 'ant-design-vue';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';  
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';

    const props = defineProps({
        order: Object,
    });

    const page = usePage();

    const numeroMuestras = ref('');
    const openDeleteOrderModal = ref(false);
    const orderInfoEditDisable = ref(true);

    const partialOrderInfo = reactive({
        numero_cotizacion: props.order.numero_cotizacion,
        numero_termometro: props.order.numero_termometro,
        temperatura: props.order.temperatura,
    });

    const handleAddSubmit = () => {
        
    }; 

    const handleDeleteOrderModal = (orderId) => {
        const url = `/orders/${orderId}`;
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
        <div class="w-11/12 mx-auto mt-8">
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
                                    :funct="() => {openDeleteOrderModal = true;}"
                                    :args="[]"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Alert 
                    :message="page.props.flash.error"
                    v-if="page.props.flash.error"
                    type="error"
                    closable/>
                <Alert 
                    :message="page.props.flash.message"
                    v-if="page.props.flash.message"
                    type="success"
                    closable/>
                <div class="flex items-center">
                    <section class="grid grid-cols-12 gap-2 w-5/12">
                        <CustomInput 
                            label="No. cotizacion"
                            :disabled="orderInfoEditDisable"
                            v-model="partialOrderInfo.numero_cotizacion"
                            :label-classes="['text-xs']"
                            size="col-span-5"/>
                        <CustomInput 
                            :disabled="orderInfoEditDisable"
                            size="col-span-2"
                            :label-classes="['text-xs']"
                            v-model="partialOrderInfo.numero_termometro"
                            label="No. termómetro"/>
                        <CustomInput 
                            :disabled="orderInfoEditDisable"
                            size="col-span-2"
                            :label-classes="['text-xs']"
                            v-model="partialOrderInfo.temperatura"
                            label="Temperatura °C"/>
                        <button 
                            class="py-1 px-3 bg-yellow-500 text-white rounded-md h-10 mt-5"
                            @click="orderInfoEditDisable = !orderInfoEditDisable">
                            ¶
                        </button>
                        <button 
                            class="py-1 px-3 bg-blue-500 text-white rounded-md h-10 mt-5 col-span-2 disabled:opacity-70"
                            :disabled="orderInfoEditDisable"
                            @click="() => handleEditPartialInfo(order.id)">
                            Editar
                        </button>
                    </section>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div 
                        class="p-1 px-1 border-2 border-slate-400 rounded-lg"
                        v-for="sample in order.muestras">
                        <div class="grid grid-cols-2">
                            <p class="bg-slate-500 font-bold py-1.5 px-2">Folio</p>
                            <p class="bg-slate-500 py-1.5 px-2">MFQ-{{ order.folio }} - {{ sample.numero_muestra }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Tipo de muestra</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.tipo_muestra }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Identificación de muestra</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.identificacion_muestra }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Latitud</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.latitud }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Longitud</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.longitud }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Parametros:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.parametros }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Caracteristicas:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.caracteristicas }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Fecha de muestreo:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.fecha_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Hora de muestreo:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.hora_muestreo.substr(0, 5) }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Tipo de muestreo:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.tipo_muestreo }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Muestreador:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.muestreador }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">Cloro:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.cloro }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2" v-if="sample.cloro === 'Si' || sample.cloro === 'No' ">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Valor del cloro:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.valor_cloro === 'Si' ? sample.valor_cloro:'< 0.1' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">SIRALAB:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.siralab ? 'Si':'No' }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Tratada biológicamente:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.tratada_biologicamente ? 'Si':'No' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">pH:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.pH }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Conductividad (uS/cm):</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.conductividad }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="bg-gray-200 font-bold py-1.5 px-2">pH Cr VI:</p>
                            <p class="bg-gray-200 py-1.5 px-2"> {{ sample.ph_cromo_hexavalente }}</p>
                        </div>
                        <div 
                            class="grid grid-cols-2">
                            <p class="bg-gray-300 font-bold py-1.5 px-2">Preservación correcta:</p>
                            <p class="bg-gray-300 py-1.5 px-2"> {{ sample.preservacion_correcta }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal
            :open="openDeleteOrderModal"
            :ok-button-props="{type: 'default', innerText: 'Borrar', class: 'bg-red-500 text-white'}"
            :cancel-button-props="{innerText: 'Cerrar'}"
            title="Eliminar orden"
            @cancel="openDeleteOrderModal = false"
            @ok="() =>handleDeleteOrderModal(order.id)">
            <h3>Esta seguro de que desea eliminar la orden MFQ-{{ order.folio }} junto con todas sus muestras?</h3>
            
        </Modal>
    </AuthenticatedLayout>
</template>