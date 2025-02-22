<script setup>
    import { reactive, ref } from 'vue';
    import axios from 'axios';
    import { Head, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';

    const props = defineProps({
        orderProp: {
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
            errors: Object,
    });
  
    const order = reactive(props.orderProp);

    const clientOptions = ref([]);

    const onSearch = async (value) => {
        const clients = await axios.get(`/clients/clients_by_name?name=${value}`);
        console.log(clients.data);
        const clientsNames = clients.data;
        clientOptions.value = clientsNames;
    };

    const onSelect = (value) => {
        const direccion_muestreo = clientOptions.value.find(client => client.value === value).direccion_muestreo;
        order.direccion_muestreo = direccion_muestreo;
    };

    const handleSubmit = () => {
        
        if (!order.fecha_recepcion)
            order.fecha_recepcion = null;
        if (!order.hora_recepcion)
            order.hora_recepcion = null;
        order.cliente = order.cliente.cliente;
        router.put(`/orders/${order.id}`, order);
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
                    ref="form$"
                    @submit="handleSubmit">
                    <TextElement 
                        name="folio"
                        before="Folio"
                        :columns="{ container: 3, wrapper:12 }"
                        rules="required">
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="formState.errors.folio">{{ formState.errors.folio }}</p>
                        </template>
                    </TextElement>
                    <TextElement 
                        name="numero_cotizacion"
                        before="Número cotización"
                        :columns="{ container: 3, wrapper:12 }"/>
                    <TextElement 
                        input-type="number"
                        name="numero_muestras"
                        before="Número de muestras"
                        rules="required|numeric|min:0"
                        :columns="{ container: 3, wrapper:12 }">
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
                        :columns="{ container: 3, wrapper: 12}"
                        display-format="MMMM DD, YYYY" />
                    <TextElement 
                        name="hora_recepcion"
                        before="Hora de recepcion"
                        :columns="{ container: 3, wrapper: 12}"
                        input-type="time"/>
                    <TextElement 
                        name="numero_termometro"
                        before="Termometro no."
                        :columns="{ container: 3, wrapper: 12}"/>
                    <TextElement 
                        name="temperatura"
                        before="Temperatura °C"
                        :columns="{ container: 3, wrapper: 12}"/>
                    <TextareaElement 
                        name="observaciones"
                        before="Observaciones"
                        :columns="{ container: 6, wrapper: 12}"/>
                    <CheckboxElement 
                        name="cesavedac"
                        :columns="{ container: 3, wrapper: 12}">
                        Cesavedac
                    </CheckboxElement>
                    <CheckboxElement 
                        name="area_recepcion_muestras_limpia"
                        :columns="{ container: 9, wrapper: 12}">
                        Se realizó desinfección en el areá de recepción después de recibir la última muestra.
                    </CheckboxElement>
                    <ButtonElement 
                        submits
                        name="create_order">
                        Crear
                    </ButtonElement>
                </Vueform>
        </div>
    </AuthenticatedLayout>
</template>
