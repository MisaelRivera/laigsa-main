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
    const { getName } = useUserData();

    const isDeleteModalVisible = ref(false);
    const isDeleteSampleModalVisible = ref(false);
    const deleteSample = ref(null);
    const isPreservationDisable = ref([]);
    for (let i = 0; i < props.order.muestras.length; i++) {
        isPreservationDisable.value.push(true);
    }
 
    const handlePreservationSubmit = (form$) => {
        router.put(`/orders/edit-preservation/${form$.data.id_muestra}`, form$.requestData, {
            onSuccess: () => {
                push.success(`La preservacion de la muestra se ha editado correctamente`);
            },
            preserveScroll: true,
        });
    };

    const handleToggleEditPreservation = (index) => {
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
        const url = `/water_samples/${deleteSample.value.id}`;
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
                    <template v-if="sample.identificacion_muestra_relacion">
                        <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                            <p class="font-bold py-1.5 px-2">Identificación de muestra</p>
                            <p class="py-1.5 px-2"> 
                                {{ sample.identificacion_muestra_relacion.identificacion_muestra }}
                            </p>
                        </div>
                        <template v-if="getName() !== 'Juan Pablo'">
                            <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Latitud</p>
                                <p class="py-1.5 px-2"> {{ sample.identificacion_muestra_relacion.latitud }}</p>
                            </div>
                            <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                                <p class="font-bold py-1.5 px-2">Longitud</p>
                                <p class="py-1.5 px-2"> {{ sample.identificacion_muestra_relacion.longitud }}</p>
                            </div>
                        </template>
                    </template>
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
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">Tratada biológicamente:</p>
                        <p class="py-1.5 px-2"> {{ sample.tratada_biologicamente ? 'Si':'No' }}</p>
                    </div>
                    <div class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300">
                        <p class="font-bold py-1.5 px-2">pH:</p>
                        <p class="py-1.5 px-2"> {{ sample.pH }}</p>
                    </div>
                    <div 
                        class="grid grid-cols-2 odd:bg-gray-200 even:bg-gray-300"
>
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