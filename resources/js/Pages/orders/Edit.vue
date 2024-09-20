<script setup>
    import { reactive, ref } from 'vue';
    import axios from 'axios';
    import { Head, router } from '@inertiajs/vue3';
    import { AutoComplete, Button, Textarea, Checkbox, Input, Form, Select, SelectOption, FormItem, Row, Col, InputNumber } from 'ant-design-vue';
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
                <Form
                    :model="order"
                    @finish="handleSubmit"
                    layout="vertical">
                    <Row>
                        <Col :span="5">
                            <FormItem
                                label="Folio"
                                name="folio"
                                :rules="[{required: true, message: 'Introduce el folio'}]">
                                <Input 
                                    v-model:value="order.folio"
                                    placeholder="Folio"
                                    disabled/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Numero de cotizacion"
                                name="numero_cotizacion">
                                <Input 
                                    v-model:value="order.numero_cotizacion"
                                    placeholder="Numero cotizacion"/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Numero de muestras"
                                name="numero_muestras"
                                :rules="[{required: true, message: 'Introduce el numero de muestras'}]">
                                <InputNumber 
                                    v-model:value="order.numero_muestras"
                                    placeholder="Numero de muestras"
                                    :min="0"
                                    class="w-full"
                                    :max="30"
                                    disabled/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Aguas o alimentos"
                                name="aguas_alimentos"
                                :rules="[{required: true, message: 'Elije una de las opciones'}]">
                                <Select 
                                    v-model:value="order.aguas_alimentos">
                                    <SelectOption :value="null">
                                        Elije una opcion
                                    </SelectOption>
                                    <SelectOption value="Aguas">
                                        Aguas
                                    </SelectOption>
                                    <SelectOption value="Alimentos">
                                        Alimentos
                                    </SelectOption>
                                </Select>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :span="11">
                            <FormItem
                                label="Cliente"
                                name="cliente"
                                :rules="[{ required: true, message: 'Seleccione un cliente'}]">
                                <AutoComplete 
                                    v-model:value="order.cliente.cliente"
                                    @search="onSearch"
                                    :options="clientOptions"/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Fecha de recepcion"
                                name="fecha_recepcion">
                                <Input 
                                    type="date" 
                                    v-model:value="order.fecha_recepcion"/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Hora de recepcion"
                                name="hora_recepcion">
                                <Input 
                                    type="time" 
                                    v-model:value="order.hora_recepcion"/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :span="5">
                            <FormItem
                                label="Termometro no."
                                name="numero_termometro">
                                <Input 
                                    v-model:value="order.numero_termometro"/>
                            </FormItem>
                        </Col>
                        <Col :span="5" :offset="1">
                            <FormItem
                                label="Temperatura °C"
                                name="temperatura">
                                <Input 
                                    v-model:value="order.temperatura"/>
                            </FormItem>
                        </Col>
                    </Row>
                    <FormItem 
                        label="Observaciones"
                        name="observaciones">
                        <Textarea 
                            :style="{'width': '400px', 'height': '100px', 'min-height': '100px', 'max-height': '100px'}"
                            v-model:value="order.observaciones"></Textarea>
                    </FormItem>
                    <Row>
                        <Col :span="3">
                            <Checkbox v-model:checked="order.cesavedac">Cesavedac</Checkbox>
                        </Col>
                        <Col :span="15">
                            <Checkbox v-model:checked="order.area_recepcion_muestras_limpia">Se realizó desinfección en el areá de recepción después de recibir la última muestra.</Checkbox>
                        </Col>
                    </Row>
                    <button class="bg-blue-600 text-white py-2 px-4 rounded">
                        Editar 
                    </button>
                </Form>
        </div>
    </AuthenticatedLayout>
</template>
