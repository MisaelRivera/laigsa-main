<script setup>
    import { ref, reactive } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import EditDetailsTitle from '@/Components/Shared/EditDetailsTitle.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    import { useMessages } from '@/composables/messages';
    import { Notivue, Notification, push } from 'notivue';
    import axios from 'axios';
    import { explodeCoordinates, implodeCoordinates } from '@/helpers/string_helper';
    import CustomInput from '@/Components/Shared/CustomInput.vue';
    import CustomCheckbox from '@/Components/Shared/CustomCheckbox.vue';
    const { getMessage } = useMessages();
    const props = defineProps({
        client: {
            type: Object
        },

        errors: {
            Object
        }
    });

    if (getMessage()) {
        push.success(getMessage());
    }

    const identificacionMuestraFilter = ref(null);

    const isDeleteClientModalVisible = ref(false);
    const isDeleteSampleIdentificationModalVisible = ref(false);
    const isEditSampleIdentificationVisible = ref(false);

    const deleteSampleIdentification = ref(null);
    let editSampleIdentificationId = ref(null);
    let editSampleIdentification = reactive({
        identificacion_muestra: '',
        latitud_grados: '',
        latitud_minutos: '',
        latitud_segundos: '',
        latitud_orientacion: null,
        longitud_grados: '',
        longitud_minutos: '',
        longitud_segundos: '',
        longitud_orientacion: null,
        id_cliente: null,
        siralab: false,
        obsoleta: false,
    });

    const handleSampleIdentificationFilter = async(ev) => {
        const value = ev.target.value;
        const result = await axios.get(`/clientes/filter_sample_identifications/${props.client.id}?val=${value}`);
        props.client.identificaciones_muestra_activas = result.data;
    };

    const coordenadas = ref(false);
    
    const handleDeleteClient = () => {
        alert('Test');
    };

    const handleDeleteSampleIdentification = () => {
        isDeleteSampleIdentificationModalVisible.value = false;
        router.put(`/clientes/set_sample_identification_obsolete/${deleteSampleIdentification.value.id}`, {
            onSuccess: () => {
                push.success(`La identificacion de mustra ${deleteSampleIdentification.value.identificacion_muestra} ha sido puesta obsoleta correctamente`);
            }
        });
    };

    const handleOpenEditSampleIdentificationModal = (sampleIdentification) => {
        console.log(sampleIdentification);
        editSampleIdentificationId.value = sampleIdentification.id;
        isEditSampleIdentificationVisible.value = true;
        editSampleIdentification.obsoleta = sampleIdentification.obsoleta;
        editSampleIdentification.identificacion_muestra = sampleIdentification.identificacion_muestra;
        if (sampleIdentification.latitud_grados !== 'Sin Dato' && sampleIdentification.latitud_grados !== '') {
            coordenadas.value = true;
            const latitudInfo = explodeCoordinates(sampleIdentification.latitud);
            const longitudInfo = explodeCoordinates(sampleIdentification.longitud);
            editSampleIdentification.latitud_grados = latitudInfo.grados;
            editSampleIdentification.latitud_minutos = latitudInfo.minutos;
            editSampleIdentification.latitud_segundos = latitudInfo.segundos;
            editSampleIdentification.latitud_orientacion = latitudInfo.orientacion;
            console.log(latitudInfo);
            editSampleIdentification.longitud_grados = longitudInfo.grados;
            editSampleIdentification.longitud_minutos = longitudInfo.minutos;
            editSampleIdentification.longitud_segundos = longitudInfo.segundos;
            editSampleIdentification.longitud_orientacion = longitudInfo.orientacion;
            editSampleIdentification.id_cliente = sampleIdentification.id_cliente;
            console.log(editSampleIdentification);
        }
    };

    const handleCloseEditSampleIdentificationModal = () => {
        editSampleIdentification.identificacion_muestra = '';
        editSampleIdentification.latitud_grados = '';
        editSampleIdentification.latitud_minutos = '';
        editSampleIdentification.latitud_segundos = '';
        editSampleIdentification.latitud_orientacion = null;
        editSampleIdentification.longitud_grados = '';
        editSampleIdentification.longitud_minutos = '';
        editSampleIdentification.longitud_segundos = '';
        editSampleIdentification.longitud_orientacion = null;
        editSampleIdentification.id_cliente = null;
        editSampleIdentification.siralab = false;
        editSampleIdentification.obsoleta = false;
        isEditSampleIdentificationVisible.value = false;
    };

    const handleEditSample = () => {
        router.put(`/clientes/edit_sample_identification/${editSampleIdentificationId.value}`, editSampleIdentification, {
            onSuccess: () => {
                const sampleIdentificationName = editSampleIdentification.identificacion_muestra;
                editSampleIdentification.identificacion_muestra = '';
                editSampleIdentification.latitud_grados = '';
                editSampleIdentification.latitud_minutos = '';
                editSampleIdentification.latitud_segundos = '';
                editSampleIdentification.latitud_orientacion = null;
                editSampleIdentification.longitud_grados = '';
                editSampleIdentification.longitud_minutos = '';
                editSampleIdentification.longitud_segundos = '';
                editSampleIdentification.longitud_orientacion = null;
                editSampleIdentification.id_cliente = null;
                editSampleIdentification.siralab = false;
                editSampleIdentification.obsoleta = false;
                isEditSampleIdentificationVisible.value = false;
                push.success(`La identificacion de muestra ${sampleIdentificationName} se ha editado correctamente`);
            }
        });
    };

    const handleOpenDeleteClientModal = () => {
        isDeleteClientModalVisible.value = true;
    };

    const handleOpenDeleteSampleIdentificationModal = (identificacionMuestra) => {
        deleteSampleIdentification.value = identificacionMuestra;
        isDeleteSampleIdentificationModalVisible.value = true;
    };
    
    const handleCloseDeleteClientModal = () => {
        isDeleteClientModalVisible.value = false;
    };
    
    const handleCloseDeleteSampleIdentificationModal = () => {
        deleteSampleIdentification.value = null;
        isDeleteSampleIdentificationModalVisible.value = false;
    };

    const handleSubmitCreate = (form$) => {
        router.post('/clientes/create_sample_identification', {...form$.requestData, id_cliente:props.client.id}, {
            onSuccess: () => {
                push.success(`Se ha creado la identificacion de muestra correctamente ${form$.requestData.identificacion_muestra}`);
            }
        });
    };

    const handleCoordenatesChangeState = (coordinates) => {
        coordenadas.value = coordinates;
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <EditDetailsTitle
                title="Datos del cliente"
                :back-link="route('clients.index')"
                align="justify-center"
                :edit-link="route('clients.show', {client: client.id})"/>
            <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 bg-gray-100 border-2">
                    <tr>
                        <th class="px-2 py-3 text-left">Cliente</th>
                        <th class="px-2 py-3 text-left">Sitio de muestreo</th>
                        <th class="px-2 py-3 text-left">Encargado</th>
                        <th class="px-2 py-3 text-left">Teléfono</th>
                        <th class="px-2 py-3 text-left">Correo electrónico</th>
                        <th class="px-2 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-2">
                        <td class="px-2 py-3">{{ client.cliente }}</td>
                        <td class="px-2 py-3">{{ client.sitio_muestreo }}</td>
                        <td class="px-2 py-3">{{ client.encargado }}</td>
                        <td class="px-2 py-3">{{ client.telefono }}</td>
                        <td class="px-2 py-3">{{ client.correo_electronico }}</td>
                        <td class="px-2 py-3">
                            <DeleteButton
                                :args="[]"
                                :funct="handleOpenDeleteClientModal"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full text-sm text-gray-500 dark:text-gray-400 mt-5">
                <thead class="text-gray-700 bg-gray-100 border-2">
                    <tr>
                        <th class="px-2 py-3 text-left">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-gray-50 border-2">
                        <td class="px-2 py-3">{{ client.observaciones }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full text-sm text-gray-500 dark:text-gray-400 mt-5">
                <thead class="text-xs text-gray-700 bg-gray-100 border-2">
                    <tr>
                        <th class="px-2 py-3">Observaciones analistas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-gray-50 border-2">
                        <td class="px-2 py-3">{{ client.observaciones_analistas }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="w-10/12 mx-auto">
                <Vueform 
                    :endpoint="false"
                    :columns="{container:12, wrapper:12}"
                    @submit="handleSubmitCreate">
                    <TextElement
                        name="identificacion_muestra"
                        placeholder="Identificacion de muestra">
                        <template #before>
                            <p>Identificacion de la muestra</p>
                        </template>
                        <template #description>
                            <p 
                                class="text-red-500"
                                v-if="errors['identificacion_muestra']">
                                {{ errors['identificacion_muestra'] }}
                            </p>
                        </template>
                    </TextElement>
                    <TextElement
                        name="latitud_grados"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder="°"
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label="°"
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: ['text-3xl', 'mr-2'],
                            }
                        }">
                        <template #before>
                            <p>Latitud</p>
                        </template>
                        <template #description>
                            <p 
                                v-if="errors['latitud_grados']"
                                class="text-red-500">
                                {{ errors['latitud_grados'] }}
                            </p>
                        </template>
                    </TextElement>
                    <TextElement
                        name="latitud_minutos"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder="'"
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label="'"
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: 'text-3xl',
                            },
                            ElementLayout: {
                                innerWrapper: 'mt-6'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['latitud_minutos']"
                                class="text-red-500">
                                {{ errors['latitud_minutos'] }}
                            </p>
                        </template>
                    </TextElement>
                    <TextElement
                        name="latitud_segundos"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder='"'
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label='"'
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: 'text-3xl',
                            },
                            ElementLayout: {
                                innerWrapper: 'mt-6'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['latitud_segundos']"
                                class="text-red-500">
                                {{ errors['latitud_segundos'] }}
                            </p>
                        </template>
                    </TextElement>
                    <SelectElement
                        name="latitud_orientacion"
                        :columns="{container:2, wrapper:12}"
                        :conditions="[['show_coordinates', true]]"
                        :items="[
                            {value: null, label: 'Elija orientacion'},
                            { label: 'Norte', value: 'N'},
                            { label: 'Sur', value: 'S'}
                        ]"
                        :add-classes="{
                            ElementLayout: {
                                innerWrapper: 'mt-7'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['latitud_orientacion']"
                                class="text-red-500">
                                {{ errors['latitud_orientacion'] }}
                            </p>
                        </template>

                    </SelectElement>
                    <TextElement
                        name="longitud_grados"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder="°"
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label="°"
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: ['text-3xl', 'mr-2'],
                            },
                        }">
                        <template #before>
                            <p>Longitud</p>
                        </template>
                        <template #description>
                            <p v-if="errors['longitud_grados']"
                                class="text-red-500">
                                {{ errors['longitud_grados'] }}
                            </p>
                        </template>
                    </TextElement>
                    <TextElement
                        name="longitud_minutos"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder="'"
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label="'"
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: 'text-3xl',
                            },
                            ElementLayout: {
                                innerWrapper: 'mt-6'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['longitud_minutos']"
                                class="text-red-500">
                                {{ errors['longitud_minutos'] }}
                            </p>
                        </template>
                    </TextElement>
                    <TextElement
                        name="longitud_segundos"
                        :columns="{container:1, label: 2, wrapper:12}"
                        placeholder='"'
                        :conditions="[['show_coordinates', true]]"
                        input-type="number"
                        label='"'
                        :remove-classes="{
                            ElementLabel: {
                                container: 'items-start',
                                container_md: 'form-text'
                            }
                        }"
                        :add-classes="{
                            ElementLabel: {
                                container: ['items-center', 'mt-7'],
                                container_md: 'text-3xl',
                            },
                            ElementLayout: {
                                innerWrapper: 'mt-6'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['longitud_segundos']"
                                class="text-red-500">
                                {{ errors['longitud_segundos'] }}
                            </p>
                        </template>
                    </TextElement>
                    <SelectElement
                        name="longitud_orientacion"
                        :columns="{container:2, wrapper:12}"
                        :conditions="[['show_coordinates', true]]"
                        :items="[
                            {value: null, label: 'Elija orientacion'},
                            'Este (E)',
                            'Oeste (W)'
                        ]"
                        :add-classes="{
                            ElementLayout: {
                                innerWrapper: 'mt-7'
                            }
                        }">
                        <template #description>
                            <p v-if="errors['longitud_orientacion']"
                                class="text-red-500">
                                {{ errors['longitud_orientacion'] }}
                            </p>
                        </template>

                    </SelectElement>
                    <CheckboxElement
                        name="show_coordinates"
                        :add-classes="{
                            ElementLayout: {
                                container: 'mt-8'
                            },
                            CheckboxElement: {
                                text: ['text-xs', 'mt-1']
                            }
                        }"
                        :columns="{container:1, wrapper:12}">
                        Coordenadas
                    </CheckboxElement>
                    <CheckboxElement
                        name="siralab"
                        :columns="{container:1, wrapper:12}"
                        before="Siralab"
                        :add-classes="{
                           ElementLayout: {
                                innerWrapper: 'mt-2'
                            },
                        }">
                        Si
                    </CheckboxElement>
                    <button class="bg-green-500 text-white rounded-md py-2 px-2">Crear</button>
                </Vueform>
                <table class="border-2 w-full">
                    <thead>
                        <tr class="border-2 bg-gray-100">
                            <th class="py-1 px-2 text-left">
                                Identificacion de la muestra
                                <input 
                                    name="identificacion_muestra"
                                    type="text"
                                    ref="identificacionMuestraFilter"
                                    class="border"
                                    @input="handleSampleIdentificationFilter">
                            </th>
                            <th class="py-1 px-2 text-left">Latitud</th>
                            <th class="py-1 px-2 text-left">Longitud</th>
                            <th class="py-1 px-2 text-left">SIRALAB</th>
                            <th class="py-1 px-2 text-left">Obsoleta</th>
                            <th class="py-1 px-2 text-left"></th>
                            <th class="py-1 px-2 text-left"></th>
                        </tr>
                    </thead>
                    <tbody class="[&>*:nth-child(odd)]:bg-gray-50 [&>*:nth-child(even)]:bg-white">
                        <tr 
                            v-for="(identificacion_muestra, index) in client.identificaciones_muestra_activas"
                            :key="index"
                            class="border-2">
                            <td class="py-1 px-2"
                                :class="{'text-green-500':identificacion_muestra.siralab,
                                 'text-red-500': identificacion_muestra.obsoleta}">
                                {{ identificacion_muestra.identificacion_muestra }}
                            </td>
                            <td class="py-1 px-2" 
                                :class="{'text-green-500':identificacion_muestra.siralab,
                                 'text-red-500': identificacion_muestra.obsoleta}">
                                {{ identificacion_muestra.latitud }}
                            </td>
                            <td class="py-1 px-2" 
                                :class="{'text-green-500':identificacion_muestra.siralab,
                                 'text-red-500': identificacion_muestra.obsoleta}">
                                {{ identificacion_muestra.longitud }}
                            </td>
                            <td class="py-1 px-2" 
                                :class="{'text-green-500':identificacion_muestra.siralab,
                                 'text-red-500': identificacion_muestra.obsoleta}">
                                {{ identificacion_muestra.siralab ? 'Si':'No' }}
                            </td>
                            <td class="py-1 px-2" 
                                :class="{'text-green-500':identificacion_muestra.siralab,
                                 'text-red-500': identificacion_muestra.obsoleta}">
                                {{ identificacion_muestra.obsoleta ? 'Si':'No' }}
                            </td>
                            <td class="py-1 px-2">
                                <button 
                                    class="py-1 px-3 bg-yellow-500 text-white rounded-md h-10 col-span-1"
                                    @click="() => handleOpenEditSampleIdentificationModal(identificacion_muestra)">
                                    ¶
                                </button>
                            </td>
                            <td class="py-1 px-2">
                                <DeleteButton
                                    :funct="() => handleOpenDeleteSampleIdentificationModal(identificacion_muestra)"
                                    :args="[]"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="border py-1 text-center">
                    <i class="fas fa-redo"></i>
                </div>
                <div>
                    <table class="w-full border-2 bg-gray-100">
                        <thead>
                            <th class="py-1 px-2">Identificacion de muestra</th>
                            <th class="py-1 px-2">Latitud</th>
                            <th class="py-1 px-2">Longitud</th>
                            <th class="py-1 px-2">SIRALAB</th>
                            <th class="py-1 px-2">Obsoleta</th>
                        </thead>
                        <tbody class="[&>*:nth-child(odd)]:bg-gray-50 [&>*:nth-child(even)]:bg-white">
                            <tr 
                                v-for="(identificacion_muestra, index) in client.identificaciones_muestra_obsoletas"
                                :key="index"
                                class="border-2">
                                <td class="py-1 px-2">{{ identificacion_muestra.identificacion_muestra }}</td>
                                <td class="py-1 px-2 text-center">{{ identificacion_muestra.latitud }}</td>
                                <td class="py-1 px-2 text-center">{{ identificacion_muestra.longitud }}</td>
                                <td class="py-1 px-2 text-center">{{ identificacion_muestra.siralab ? 'Si':'No' }}</td>
                                <td class="py-1 px-2 text-center">{{ identificacion_muestra.obsoleta ? 'Si':'No' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <MyModal
            title="Editar identificacion de muestra"
            v-model="isEditSampleIdentificationVisible"
            :cancel-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            :ok-button-props="{
                class: ['bg-yellow-500', 'text-white'],
                innerText: 'Editar'
            }"
            @close-from="handleCloseEditSampleIdentificationModal"
            @ok="handleEditSample"
            size="75%">
            <form @submit.prevent="handleEditSample" class="w-full">
                <div class="grid grid-cols-12">
                    <div class="col-span-12 mb-4">
                        <CustomInput
                            name="identificacion_muestra"
                            id="identificacion-muestra"
                            label="Identificacion de muestra"
                            v-model="editSampleIdentification.identificacion_muestra"/>
                    </div>
                </div>
                <div class="grid grid-cols-12">
                    <div class="col-span-1 mb-4">
                        <label class="flex items-center">
                            <CustomCheckbox 
                                name="obsoleta"
                                v-model="editSampleIdentification.obsoleta"
                                label-text="Obsoleta"/>
                        </label>
                    </div>
                    <div class="col-span-1 mb-4">
                        <label class="flex items-center">
                            <CustomCheckbox
                                v-model="coordenadas"
                                label-text="Coordenadas"
                                name="coordenadas"
                                :checked="editSampleIdentification.latitud_grados !== 'Sin dato'"/>
                        </label>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="latitud_grados"
                            id="latitud-grados"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label="°"
                            v-model="editSampleIdentification.latitud_grados"/>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="latitud_minutos"
                            id="latitud-minutos"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label="'"
                            :label-classes="['text-2xl']"
                            v-model="editSampleIdentification.latitud_minutos"/>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="latitud_segundos"
                            id="latitud-segundos"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label='"'
                            :label-classes="['text-2xl']"
                            v-model="editSampleIdentification.latitud_segundos"/>
                    </div>
                    <div class="col-span-2 mb-4" v-if="coordenadas">
                        <select
                            class="border-2 border-slate-300 rounded-lg py-2 px-4 text-gray-900 text-sm focus:outline-none focus:border-blue-200"
                            v-model="editSampleIdentification.latitud_orientacion">
                            <option :value="null">Elija orientacion</option>
                            <option value="N" :selected="editSampleIdentification.latitud_orientacion === 'N'">Norte</option>
                            <option value="S" :selected="editSampleIdentification.latitud_orientacion === 'S'">Sur</option>
                        </select>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="longitud_grados"
                            id="longitud-grados"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label="°"
                            v-model="editSampleIdentification.longitud_grados"/>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="longitud_minutos"
                            id="longitud-minutos"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label="'"
                            :label-classes="['text-2xl']"
                            v-model="editSampleIdentification.longitud_minutos"/>
                    </div>
                    <div class="col-span-1 mb-4" v-if="coordenadas">
                        <CustomInput
                            name="longitud_segundos"
                            id="longitud-segundos"
                            :input-classes="['w-8/12']"
                            :inline="true"
                            label='"'
                            :label-classes="['text-2xl']"
                            v-model="editSampleIdentification.longitud_segundos"/>
                    </div>
                    <div class="col-span-2 mb-4" v-if="coordenadas">
                        <select
                            class="border-2 border-slate-300 rounded-lg py-2 px-4 text-gray-900 text-sm focus:outline-none focus:border-blue-200"
                            v-model="editSampleIdentification.longitud_orientacion">
                            <option :value="null">Elija orientacion</option>
                            <option value="Este (E)" :selected="editSampleIdentification.longitud_orientacion === 'Este (E)'">Este (E)</option>
                            <option value="Oeste (W)" :selected="editSampleIdentification.longitud_orientacion === 'Oeste (W)'">Oeste (W)</option>
                        </select>
                    </div>
                </div>
            </form>
            <!--<Vueform
                :endpoint="false"
                :columns="{container:12, wrapper:12}"
                ref="editSampleIdentification">
                <StaticElement
                    name="coordinates_gap"
                    content="<div></div>"
                    :columns="{container:2, wrapper:12}"
                    :conditions="[['coordenadas', true]]"/>
                <StaticElement
                    name="latitud_label"
                    content="<p class='font-bold text-center'>Latitud</p>"
                    :columns="{container:5, wrapper:12}"
                    :conditions="[['coordenadas', true]]"/>
                <StaticElement
                    name="longitud_label"
                    content="<p class='font-bold text-center'>Longitud</p>"
                    :columns="{container:5, wrapper:12}"
                    :conditions="[['coordenadas', true]]"/>
                <CheckboxElement
                    name="obsoleta"
                    :columns="{container:1, wrapper:12}"
                    :add-classes="{
                        ElementLayout: {
                            innerWrapper: ['mt-2']
                        }
                    }">
                    Obsoleta
                </CheckboxElement>
                <CheckboxElement
                    name="coordenadas"
                    :columns="{container:1, wrapper:12}"
                    :add-classes="{
                        ElementLayout: {
                            innerWrapper: ['mt-2']
                        }
                    }"
                    :default="editSampleIdentification.latitud_grados !== 'Sin Dato'">
                    Coordenadas
                </CheckboxElement>
                <TextElement
                    name="latitud_grados"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label="°"
                    :default="editSampleIdentification.latitud_grados"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['latitud_grados']"
                            class="text-sm text-red-500">
                            {{ errors['latitud_grados'] }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="latitud_minutos"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label="'"
                    :default="editSampleIdentification.latitud_minutos"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['latitud_minutos']"
                            class="text-sm text-red-500">
                            {{ errors['latitud_minutos'] }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="latitud_segundos"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label='"'
                    :default="editSampleIdentification.latitud_segundos"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['latitud_segundos']"
                            class="text-sm text-red-500">
                            {{ errors['latitud_segundos'] }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="latitud_orientacion"
                    :columns="{container:2, wrapper:12}"
                    :default="editSampleIdentification.latitud_orientacion"
                    :items="[
                        {label: 'Elija la orientacion', value: null},
                        { label: 'Norte', value: 'N'},
                        { label: 'Sur', value: 'S'}
                    ]"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['latitud_orientacion']"
                            class="text-red-500">
                            {{ errors['latitud_orientacion'] }}
                        </p>
                    </template>
                </SelectElement>
                <TextElement
                    name="longitud_grados"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label="°"
                    :default="editSampleIdentification.longitud_grados"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['longitud_grados']"
                            class="text-sm text-red-500">
                            {{ errors['longitud_grados'] }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="longitud_minutos"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label="'"
                    :default="editSampleIdentification.longitud_minutos"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['longitud_minutos']"
                            class="text-sm text-red-500">
                            {{ errors['longitud_minutos'] }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="longitud_segundos"
                    input-type="number"
                    :columns="{container:1, label:2, wrapper:12}"
                    label='"'
                    :default="editSampleIdentification.longitud_segundos"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['longitud_segundos']"
                            class="text-sm text-red-500">
                            {{ errors['longitud_segundos'] }}
                        </p>
                    </template>
                </TextElement>
                <SelectElement
                    name="longitud_orientacion"
                    :columns="{container:2, wrapper:12}"
                    :default="editSampleIdentification.longitud_orientacion"
                    :items="[
                        {label: 'Elija la orientacion', value: null},
                        'Este (E)',
                        'Oeste (W)'
                    ]"
                    :conditions="[['coordenadas', true]]">
                    <template #description>
                        <p 
                            v-if="errors['longitud_orientacion']"
                            class="text-red-500">
                            {{ errors['longitud_orientacion'] }}
                        </p>
                    </template>
                </SelectElement>
            </Vueform>-->
        </MyModal>
        <MyModal
            title="Eliminar cliente"
            v-model="isDeleteClientModalVisible"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            @close-from="handleCloseDeleteClientModal"
            @ok="handleDeleteClient">
            <p>Seguro que deseas eliminar al cliente {{ client.cliente }}?</p>
        </MyModal>
        <MyModal
            title="Eliminar identificacion de muestra"
            v-model="isDeleteSampleIdentificationModalVisible"
            :cancel-button-props="{
                class: ['bg-red-500', 'text-white']
            }"
            :ok-button-props="{
                class: ['bg-blue-500', 'text-white']
            }"
            @close-from="handleCloseDeleteSampleIdentificationModal"
            @ok="handleDeleteSampleIdentification">
            <p>Seguro que deseas hacer obsoleta la identificacion de muestra {{ deleteSampleIdentification.identificacion_muestra }}?</p>
        </MyModal>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>