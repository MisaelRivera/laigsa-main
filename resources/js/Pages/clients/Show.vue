<script setup>
    import { ref } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import EditDetailsTitle from '@/Components/Shared/EditDetailsTitle.vue';
    import DeleteButton from '@/Components/Shared/DeleteButton.vue';
    import MyModal from '@/Components/Shared/MyModal.vue';
    const props = defineProps({
        client: {
            type: Object
        },

        errors: {
            Object
        }
    });

    const isDeleteClientModalVisible = ref(false);

    const handleDeleteClient = () => {
        alert('Test');
    };

    const handleOpenDeleteClientModal = () => {
        isDeleteClientModalVisible.value = true;
    };

    const handleCloseDeleteClientModal = () => {
        isDeleteClientModalVisible.value = false;
    };

    const handleSubmitCreate = (form$) => {
        //console.log(form$.requestData);
        router.post('/clientes/create_sample_identification', {...form$.requestData, id_cliente:props.client.id});
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
                    <tr class="bg-gray-50 border-2">
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
                            'Norte',
                            'Sur'
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
            </div>
        </div>
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
    </AuthenticatedLayout>
</template>