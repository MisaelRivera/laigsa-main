<script setup>
    import { reactive, ref, onMounted } from 'vue';
    import {useForm} from '@inertiajs/vue3';
    import axios from 'axios';
    import { Head, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';

    const props = defineProps({
        order: {
            type: Object,
            required: true,
        },

        lastOrder: {
            type: Object,
            required: true,
        },

        aguas: {
            type: Boolean,
        },

        clients: {
            type: Array
        }
    });
  
    const formState = useForm({
        folio: props.order.folio,
        numero_cotizacion: props.order.numero_cotizacion,
        numero_muestras: props.order.numero_muestras,
        aguas_alimentos: props.order.aguas_alimentos,
        id_cliente: props.order.cliente.id,
        fecha_recepcion: props.order.fecha_recepcion,
        hora_recepcion: props.order.hora_recepcion,
        numero_termometro: props.order.numero_termometro,
        temperatura: props.order.temperatura,
        observaciones: props.order.observaciones,
        cesavedac: props.order.cesavedac,
        area_recepcion_muestras_limpia: props.order.area_recepcion_muestras_limpia,
    });

    const clientOptions = ref([]);

    onMounted(() => {
        clientOptions.value = props.clients;
    });

    const handleClientSearch = async(searchQuery) => 
    {
        const res = await axios.get(`/orders/get-client-for-order?search=${searchQuery}`);
        let clients = res.data.map((client) => {
           return { value: client.id, label: client.cliente}
        });
        clientOptions.value = clients;
    };

    const handleSubmit = () => {
        
        if (!props.order.fecha_recepcion)
            props.order.fecha_recepcion = null;
        if (!props.order.hora_recepcion)
            props.order.hora_recepcion = null;
        props.order.cliente = props.order.cliente.cliente;
        router.put(`/orders/${props.order.id}`, props.order);
    }

</script>

<template>
    <AuthenticatedLayout>
        <div class="w-8/12 mx-auto mt-3">
            <CreateTitle 
                title="Editar Orden"
                :ownLink="`/orders/edit/${order.id}`"
                :backLink="`/orders/show/${order.id}`"/>
                <Vueform
                    :columns="{ container:12, wrapper:12 }"
                    :scroll-to-invalid="false"
                    :endpoint="false"
                    @submit="handleSubmit">
                    <TextElement 
                        name="folio"
                        before="Folio"
                        :default="formState.folio"
                        :columns="{ container: 3, wrapper:12 }"
                        rules="required"
                        :disabled="true">
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="formState.errors.folio">{{ formState.errors.folio }}</p>
                        </template>
                    </TextElement>
                    <TextElement 
                        name="numero_cotizacion"
                        before="Número cotización"
                        :default="formState.numero_cotizacion"
                        :columns="{ container: 3, wrapper:12 }"/>
                    <TextElement 
                        input-type="number"
                        name="numero_muestras"
                        before="Número de muestras"
                        rules="required|numeric|min:0"
                        :default="formState.numero_muestras"
                        :columns="{ container: 3, wrapper:12 }"
                        :disabled="true">
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="formState.errors.numero_muestras">
                                {{ formState.errors.numero_muestras }}
                            </p>
                        </template>
                    </TextElement>
                    <SelectElement 
                        name="aguas_alimentos"
                        before="Aguas o alimentos"
                        :items="[
                            {
                                value: null,
                                label: 'Elija una opcion',
                            },
                            {
                                value: 'Aguas',
                                label: 'Aguas'
                            },
                            {
                                value: 'Alimentos',
                                label: 'Alimentos'
                            }
                        ]"
                        :default="formState.aguas_alimentos"
                        :columns="{ container: 3, wrapper:12 }">
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="formState.errors.aguas_alimentos">
                                {{ formState.errors.aguas_alimentos }}
                            </p>
                        </template>
                    </SelectElement>
                    <SelectElement 
                        name="id_cliente"
                        before="Cliente"
                        :default="formState.id_cliente "
                        :columns="{ container:6, wrapper: 12 }"
                        :items="clientOptions"
                        :search="true"
                        rules="required"
                        @search-change="handleClientSearch">
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="formState.errors.cliente">
                                {{ formState.errors.cliente }}
                            </p>
                        </template>
                    </SelectElement>
                    <DateElement 
                        name="fecha_recepcion"
                        before="Fecha de recepcion"
                        :default="formState.fecha_recepcion"
                        :columns="{ container: 3, wrapper: 12}"
                        display-format="MMMM DD, YYYY" />
                    <TextElement 
                        name="hora_recepcion"
                        before="Hora de recepcion"
                        :default="formState.hora_recepcion"
                        :columns="{ container: 3, wrapper: 12}"
                        input-type="time"/>
                    <TextElement 
                        name="numero_termometro"
                        before="Termometro no."
                        :default="formState.numero_termometro"
                        :columns="{ container: 3, wrapper: 12}"/>
                    <TextElement 
                        name="temperatura"
                        before="Temperatura °C"
                        :default="formState.temperatura"
                        :columns="{ container: 3, wrapper: 12}"/>
                    <TextareaElement 
                        name="observaciones"
                        before="Observaciones"
                        :default="formState.observaciones"
                        :columns="{ container: 6, wrapper: 12}"/>
                    <CheckboxElement 
                        name="cesavedac"
                        :columns="{ container: 3, wrapper: 12}"
                        :default="formState.cesavedac">
                        Cesavedac
                    </CheckboxElement>
                    <CheckboxElement 
                        name="area_recepcion_muestras_limpia"
                        :columns="{ container: 9, wrapper: 12}"
                        :default="formState.area_recepcion_muestras_limpia">
                        Se realizó desinfección en el areá de recepción después de recibir la última muestra.
                    </CheckboxElement>
                    <button 
                        class="py-2 px-4 text-white bg-blue-500 rounded col-span-2">
                        Editar
                    </button>
                </Vueform>
        </div>
    </AuthenticatedLayout>
</template>
