<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Notivue, Notification, push } from 'notivue';
    import axios from 'axios';
    const form$ = ref(null);
    const props = defineProps({
        last_order: {
            required: true,
            type: Object,
        }
    });
    let formState = useForm({
        folio: null,
        numero_cotizacion: null,
        numero_muestras: null,
        aguas_alimentos: null,
        direccion_muestreo: null,
        id_cliente: null,
        fecha_recepcion: null,
        hora_recepcion: null,
        termometro: null,
        temperatura: null,
        observaciones: null,
        cesavedac: false,
        area_recepcion_muestras_limpia: false,
    });
    const clientOptions = ref([]);

    const handleSubmit = async() => {
        formState.folio = form$.value.data.folio;
        formState.numero_cotizacion = form$.value.data.numero_cotizacion;
        formState.numero_muestras = form$.value.data.numero_muestras;
        formState.aguas_alimentos = form$.value.data.aguas_alimentos;
        formState.id_cliente = form$.value.data.id_cliente;
        formState.fecha_recepcion = form$.value.data.fecha_recepcion;
        formState.hora_recepcion = form$.value.data.hora_recepcion;
        formState.termometro = form$.value.data.termometro;
        formState.temperatura = form$.value.data.temperatura;
        formState.observaciones = form$.value.data.observaciones;
        formState.cesavedac = form$.value.data.cesavedac;
        formState.area_recepcion_muestras_limpia = form$.value.data.area_recepcion_muestras_limpia;
        if (formState.id_cliente) {
            try {
                const cliente = await axios.get(`/api/clients/${formState.id_cliente}`);
                formState.direccion_muestreo = cliente.data.direccion_muestreo;
                console.log(formState);
                formState.post('/orders');
            } catch (e) {
                push.error("Ocurrio un error");
            }
        }
    };

    const handleClientSearch = async(searchQuery) => 
    {
        const res = await axios.get(`/orders/get-client-for-order?search=${searchQuery}`);
        console.log(res.data);
        let clients = res.data.map((client) => {
           return { value: client.id, label: client.cliente}
        });
        clientOptions.value = clients;
    };


</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto my-2">
            <CreateTitle 
                title="Crear orden"
                :own-link="route('orders.create')"
                :back-link="route('orders.index')"/>
            <div class="bg-white py-4 px-6 rounded-md">
                <p>Ultimo folio: MFQ-{{ last_order.folio }} | {{ last_order.fecha_recepcion }} | {{ last_order.hora_recepcion }}</p>
                <p>
                    Cliente: 
                    <div 
                        class="inline-block h-5 w-5 rounded-full"
                        :class="{'bg-blue-500': last_order.aguas_alimentos === 'Aguas', 'bg-yellow-500': last_order.aguas_alimentos === 'Alimentos'}"></div>
                </p>
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
        </div>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>