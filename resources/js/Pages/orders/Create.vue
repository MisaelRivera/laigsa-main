<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Row, Col, Input, InputNumber, Select, SelectOption, Form, FormItem, AutoComplete, Textarea, Checkbox } from 'ant-design-vue';
    import { ArrowLeftOutlined } from '@ant-design/icons-vue';
    const props = defineProps({
        last_order: {
            required: true,
            type: Object,
        }
    });

    const formState = useForm({
        folio: null,
        numero_cotizacion: null,
        numero_muestras: null,
        aguas_alimentos: null,
        cliente: null,
        fecha_recepcion: null,
        hora_recepcion: null,
        numero_termometro: null,
        temperatura: null,
        observaciones: null,
        cesavedac: false,
        area_recepcion_muestras_limpia: false,
    });
    
    const clientOptions = ref([]);

    const handleSubmit = () => {
        formState.post('/orders');
    };

    const handleClientSearch = async(searchText) => 
    {
        const res = await axios.get(`/orders/get-client-for-order?search=${searchText}`);
        console.log(res.data);
        let clients = res.data.map((client) => {
           return { value: client.cliente}
        });
        clientOptions.value = clients;
    };


</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto my-2">
            <h1 class="text-center text-green-700 text-2xl"> 
                <a href="/orders">
                    <ArrowLeftOutlined />
                </a>
                Crear Orden
            </h1>
            <div class="bg-white py-4 px-6 rounded-md">
                <p>Ultimo folio: MFQ-{{ last_order.folio }} | {{ last_order.fecha_recepcion }} | {{ last_order.hora_recepcion }}</p>
                <p>
                    Cliente: 
                    <div 
                        class="inline-block h-5 w-5 rounded-full"
                        :class="{'bg-blue-500': last_order.aguas_alimentos === 'Aguas', 'bg-yellow-500': last_order.aguas_alimentos === 'Alimentos'}"></div>
                </p>
                <Form
                    :model="formState"
                    @finish="handleSubmit"
                    layout="vertical">
                    <Row>
                        <Col :span="5">
                            <FormItem
                                label="Folio"
                                name="folio"
                                :rules="[{required: true, message: 'Introduce el folio'}]">
                                <Input 
                                    v-model:value="formState.folio"
                                    class="h-8 border-slate-300 rounded-md"
                                    placeholder="Folio"/>
                            </FormItem>
                        </Col>
                        <Col 
                            :span="5"
                            :offset="1">
                            <FormItem
                                label="Numero de cotizacion"
                                name="numero_cotizacion">
                                <Input 
                                    v-model:value="formState.numero_cotizacion"
                                    class="h-8 border-slate-300 rounded-md"
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
                                    v-model:value="formState.numero_muestras"
                                    placeholder="Numero de muestras"
                                    :min="0"
                                    class="w-full"
                                    :max="30"/>
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
                                    v-model:value="formState.aguas_alimentos">
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
                                    v-model:value="formState.cliente"
                                    @search="handleClientSearch"
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
                                    class="h-8 border-slate-300 rounded-md" 
                                    v-model:value="formState.fecha_recepcion"/>
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
                                    class="h-8 border-slate-300 rounded-md" 
                                    v-model:value="formState.hora_recepcion"/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col :span="5">
                            <FormItem
                                label="Termometro no."
                                name="numero_termometro">
                                <Input 
                                    v-model:value="formState.numero_termometro"
                                    class="h-8 border-slate-300 rounded-md"/>
                            </FormItem>
                        </Col>
                        <Col :span="5" :offset="1">
                            <FormItem
                                label="Temperatura °C"
                                name="temperatura">
                                <Input 
                                    v-model:value="formState.temperatura"
                                    class="h-8 border-slate-300 rounded-md"/>
                            </FormItem>
                        </Col>
                    </Row>
                    <FormItem 
                        label="Observaciones"
                        name="observaciones">
                        <Textarea 
                            :style="{'width': '400px', 'height': '100px', 'min-height': '100px', 'max-height': '100px'}"
                            v-model:value="formState.observaciones"></Textarea>
                    </FormItem>
                    <Row>
                        <Col :span="3">
                            <Checkbox v-model:checked="formState.cesavedac">Cesavedac</Checkbox>
                        </Col>
                        <Col :span="15">
                            <Checkbox v-model:checked="formState.area_recepcion_muestras_limpia">Se realizó desinfección en el areá de recepción después de recibir la última muestra.</Checkbox>
                        </Col>
                    </Row>
                    <button class="bg-green-600 text-white py-2 px-4 rounded">
                        Crear 
                    </button>
                </Form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>