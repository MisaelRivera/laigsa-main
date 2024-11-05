<script setup>
    import { ref, onMounted } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, } from '@inertiajs/vue3';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
        unit: Object
    });
    const form$ = ref(null);
    const formState = useForm({
        nombre: props.unit.nombre
    });

    const handleSubmit = () => {
        formState.nombre = form$.value.el$('nombre').value;
        formState.put('/units/' + props.unit.id);
    };

    onMounted(() => {
        form$.value.el$('nombre').update(props.unit.nombre);
    });
    
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto mt-4 bg-slate-50 py-4 px-7">
            <div class="grid grid-cols-12">
                <div class="col-span-9">
                    <CreateTitle 
                        :title="`Editar unidad ${unit.nombre}`"
                        own-link="/units/create"
                        back-link="/units"/>
                </div>
            </div>
            <Vueform
                :columns="{ container: 12, wrapper: 12 }"
                ref="form$"
                :endpoint="false"
                :float-placeholders="false"
                @submit="handleSubmit">
                <TextElement
                    name="nombre"
                    before="Unidad"
                    placeholder="g, l, etc."
                    :columns="{ container: 9, wrapper: 12 }">
                    <template 
                        #description>
                        <p 
                            class="text-red-500"
                            v-if="formState.errors.nombre">{{ formState.errors.nombre }}</p>
                    </template>
                </TextElement>
                <button 
                    class="rounded py-2 px-4 bg-blue-400 text-white col-span-3 h-12 mt-4 hover:bg-blue-600">
                    Editar
                </button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>