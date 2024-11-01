<script setup>
    import { ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, } from '@inertiajs/vue3';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
    });
    let formState = null;
    let form$ = ref(null);
    const handleSubmit = () => {
        formState = useForm({
            nombre: null,
        });
        formState.nombre = form$.value.el$('nombre').value;
        formState.post('/units');
    };
    
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto mt-4 bg-slate-50 py-3 px-5">
            <CreateTitle 
                title="Crear unidad"
                own-link="/units/create"
                back-link="/units"/>
            <Vueform
                :columns="{container: 12, wrapper: 12}"
                :float-placeholders="false"
                ref="form$"
                :endpoint="false"
                @submit="handleSubmit">
                <TextElement
                    name="nombre"
                    before="Unidad"
                    placeholder="g, l etc.">
                    <template 
                        #description
                        v-if="errors.nombre">
                        <p class="text-red-500">{{ errors.nombre }}</p>
                    </template>
                </TextElement>
                <button 
                    class="rounded py-2 px-4 bg-green-400 text-white col-span-3">
                    Crear
                </button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>