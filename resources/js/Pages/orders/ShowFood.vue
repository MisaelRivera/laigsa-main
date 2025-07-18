<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import EditLink from '@/Components/Shared/EditLink.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';  
    import OrderShowTables from '@/Layouts/OrderShowTables.vue';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    import { usePermission } from '@/composables/permissions';
    import { useUserData } from '@/composables/userData';
    import MyModal from '@/Components/Shared/MyModal.vue';

    const props = defineProps({
        order: Object,
        numeroMuestrasActual: Number,
        previousOrder: Number|null,
        nextOrder: Number|null,
    });

    console.log(props.nextOrder);

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
</script>

<template>
    <AuthenticatedLayout>
        <OrderShowTables
            :order="order"
            :numero-muestras-actual="numeroMuestrasActual"
            :previous-order-id="previousOrder ? previousOrder.id:null"
            :next-order-id="nextOrder ? nextOrder.id:null">
            <div class="grid grid-cols-3 gap-4 items-start">
                <div 
                    class="p-1 px-1 border-2 border-slate-400 rounded-lg min-h-0"
                    v-for="(sample, index) in order.muestras"
                    :key="index">
                    <div class="grid grid-cols-2">
                        <p class="bg-slate-500 font-bold py-1.5 px-2">
                            Folio
                            <EditLink 
                                :url="`/food_samples/${sample.id}/edit`"/>
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
                    <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="!getRoles().includes('analist')">
                        <p class="font-bold py-1.5 px-2">Identificación de muestra</p>
                        <p class="py-1.5 px-2"> {{ sample.identificacion_muestra }}</p>
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
                        <p class="py-1.5 px-2"> {{ sample.hora_muestreo}}</p>
                    </div>
                    <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Tipo de muestreo:</p>
                        <p class="py-1.5 px-2"> {{ sample.tipo_muestreo }}</p>
                    </div>
                    <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300" v-if="!getRoles().includes('analist')">
                        <p class="font-bold py-1.5 px-2">Muestreador:</p>
                        <p class="py-1.5 px-2"> {{ sample.muestreador }}</p>
                    </div>
                    <div 
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Peso/Vol de la muestra g./l:</p>
                        <p class="py-1.5 px-2"> {{ sample.peso_muestra }}</p>
                    </div>
                    <div 
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Temperatura °C:</p>
                        <p class="py-1.5 px-2"> {{ sample.temperatura }}</p>
                    </div>
                    <div 
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Latitud:</p>
                        <p class="py-1.5 px-2"> {{ sample.latitud }}</p>
                    </div>
                    <div 
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Longitud:</p>
                        <p class="py-1.5 px-2"> {{ sample.longitud }}</p>
                    </div>
                </div>
            </div>
        </OrderShowTables>
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