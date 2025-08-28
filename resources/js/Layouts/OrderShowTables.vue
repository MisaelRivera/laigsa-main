<script setup>
    import { ref, reactive } from 'vue';
    import { router, Link } from '@inertiajs/vue3';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import { addDaysWithoutSundays } from '@/helpers/time_helper.js';
    import { usePermission } from '@/composables/permissions';
    import MyModal from '@/Components/Shared/MyModal.vue';

    const props = defineProps({
        order: Object,
        numeroMuestrasActual: Number,
        previousOrderId: Number|null,
        nextOrderId: Number|null,
    });

    console.log(props.nextOrderId);

    const { getRoles } = usePermission();

    const isDeleteModalVisible = ref(false);
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
    <div class="w-full mx-auto mt-8">
        <div class="mx-auto p-3" v-if="getRoles().includes('admin')">
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
        <div class="grid grid-cols-12">
            <div class="col-span-6">
                <table 
                    class="col-span-12 text-sm text-left text-gray-500 dark:text-gray-400 border border-slate-300" 
                    v-if="order.observaciones && order.observaciones.length > 0">
                    <thead class="text-xs text-gray-700 bg-gray-200">
                        <tr class="px-4 py-3 w-64 border border-slate-300">
                            <th>Observaciones orden</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white bg-gray-50">
                                {{ order.observaciones }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-6">

            </div>
        </div>
        <div class="p-3">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-slate-300 border">
                <thead class="text-xs text-gray-700 bg-gray-200">
                    <tr>
                        <th 
                            scope="col" 
                            class="px-4 py-3 border border-slate-300">
                            <Link 
                                :href="route('orders.show', { order: previousOrderId})"
                                v-if="previousOrderId">
                                <i class="fas fa-angle-left"></i>
                            </Link>
                            Folio
                            <Link 
                                v-if="nextOrderId"
                                :href="route('orders.show', { order: nextOrderId})">
                                <i class="fas fa-angle-right"></i>
                            </Link>
                        </th>
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
                                :url="`/orders/${order.id}/edit`"/>
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
                    <Link
                        :href="route(`water_samples.edit_all`, {folio: order.folio})" 
                        class="btn btn-primary self-center ml-3">
                        Editar muestras
                    </Link>
                </div>
            </div>
            <slot/>
        </div>
    </div>
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
</template>