<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        client: Object
    });

    const form$ = ref(null);
    const formState = useForm({
        cliente: null,
        direccion_muestreo: null,
        direccion_fiscal: null,
        observaciones: null,
        observaciones_analistas: null,
        encargado: null,
        telefono: null,
        correo_electronico: null,
        cuarto_transitorio: false,
    });

    const handleCreate = () => {
        formState.cliente = form$.value.el$('cliente').value;
        formState.direccion_muestreo = form$.value.el$('direccion_muestreo').value;
        formState.direccion_fiscal = form$.value.el$('direccion_fiscal').value;
        formState.observaciones = form$.value.el$('observaciones').value;
        formState.observaciones_analistas = form$.value.el$('observaciones_analistas').value;
        formState.encargado = form$.value.el$('encargado').value;
        formState.telefono = form$.value.el$('telefono').value;
        formState.correo_electronico = form$.value.el$('correo_electronico').value;
        formState.cuarto_transitorio = form$.value.el$('cuarto_transitorio').value;
        formState.put(route('clients.update', {client: client.id}));
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto">
            <CreateTitle
                title="Editar cliente"
                :own-link="route('clients.edit', {client: client.id})"
                :back-link="route('clients.index')"/>
            <Vueform
                :endpoint="false"
                :columns="{container:12, wrapper:12}"
                ref="form$"
                @submit="handleCreate">
                <TextElement
                    name="cliente"
                    before="Cliente"
                    :default="client.cliente">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.cliente">
                            {{ formState.errors.cliente }}
                        </p>
                    </template>
                </TextElement>
                <TextareaElement
                    name="direccion_muestreo"
                    before="Sitio de muestreo"
                    :default="client.direccion_muestreo">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.direccion_muestreo">
                            {{ formState.errors.direccion_muestreo }}
                        </p>
                    </template>
                </TextareaElement>
                <TextareaElement
                    name="direccion_fiscal"
                    before="Direccion fiscal"
                    default="">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.direccion_fiscal">
                            {{ formState.errors.direccion_fiscal }}
                        </p>
                    </template>
                </TextareaElement>
                <TextareaElement
                    name="observaciones"
                    before="Observaciones"
                    default="">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.observaciones">
                            {{ formState.errors.observaciones }}
                        </p>
                    </template>
                </TextareaElement>
                <TextareaElement
                    name="observaciones_analistas"
                    before="Observaciones analistas"
                    default="">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.observaciones_analistas">
                            {{ formState.errors.observaciones_analistas }}
                        </p>
                    </template>
                </TextareaElement>
                <TextElement
                    name="encargado"
                    before="Encargado"
                    placeholder="Ej. Ramon perez"
                    :columns="{container: 4, wrapper:12}"
                    default="">
                    <template #description>
                        <p 
                            v-if="formState.errors.encargado"
                            class="text-red-500">
                            {{ formState.errors.encargado }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="telefono"
                    before="Telefono"
                    placeholder="Ej. 6187908890"
                    :columns="{container: 4, wrapper:12}"
                    default="">
                    <template #description>
                        <p 
                            v-if="formState.errors.telefono"
                            class="text-red-500">
                            {{ formState.errors.telefono }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="correo_electronico"
                    before="Correo electronico"
                    placeholder="Ej. 6187908890"
                    :columns="{container: 4, wrapper:12}"
                    default="">
                    <template #description>
                        <p 
                            v-if="formState.errors.correo_electronico"
                            class="text-red-500">
                            {{ formState.errors.correo_electronico }}
                        </p>
                    </template>
                </TextElement>
                <CheckboxElement
                    name="cuarto_transitorio">
                    Cuarto transitorio
                </CheckboxElement>
                <button class="bg-green-500 text-white rounded py-2 px-2 col-span-2">Crear</button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>