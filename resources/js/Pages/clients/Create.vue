<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

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
        formState.cliente = form$.value.data('cliente');
        formState.direccion_muestreo = form$.value.data('direccion_muestreo');
        formState.direccion_fiscal = form$.value.data('direccion_fiscal');
        formState.observaciones = form$.value.data('observaciones');
        formState.observaciones_analistas = form$.value.data('observaciones_analistas');
        formState.encargado = form$.value.data('encargado');
        formState.telefono = form$.value.data('telefono');
        formState.correo_electronico = form$.value.data('correo_electronico');
        formState.cuarto_transitorio = form$.value.data('cuarto_transitorio');
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto">
            <CreateTitle
                title="Crear cliente"
                :own-link="route('clients.create')"
                :back-link="route('clients.index')"/>
            <Vueform
                :endpoint="false"
                :columns="{container:12, wrapper:12}"
                ref="form$">
                <TextElement
                    name="cliente"
                    before="Cliente"
                    >
                </TextElement>
            </Vueform>
        </div>

    </AuthenticatedLayout>
</template>