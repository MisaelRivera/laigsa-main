<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object
    });
    const form$ = ref(null);

    const handleSubmit = (form$) => {
        const vueformData = form$.requestData;
        router.post(route('params_description.store'), vueformData);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-6/12 mx-auto">
            <CreateTitle 
                title="Crear Descripcion de parametros"
                :back-link="route('params_description.index')"/>
            <Vueform
                :endpoint="false"
                :columns="{ container:12, wrapper:12 }"
                ref="form$"
                class="mt-7"
                @submit="handleSubmit">
                <TextareaElement 
                    name="descripcion"
                    :columns="{container:12, wrapper:12}"
                    before="Descripcion de parametros">
                    <template #description>
                        <p v-if="errors['descripcion']" class="text-red-500">
                            {{ errors['descripcion'] }}
                        </p>
                    </template>
                </TextareaElement>
                <SelectElement 
                    name="tipo"
                    :columns="{container:4, wrapper:12}"
                    before="Tipo"
                    :items="[
                        'Aguas',
                        'Alimentos'
                    ]">
                    <template #description>
                        <p v-if="errors['tipo']" class="text-red-500">
                            {{ errors['tipo'] }}
                        </p>
                    </template>
                </SelectElement>
                <CheckboxElement
                    name="cesavedac"
                    :columns="{ container: 4, wrapper: 12 }"
                    :add-class="{
                        wrapper: 'mt-8'
                    }">
                    <template #description>
                        <p v-if="errors['cesavedac']" class="text-red-500">
                            {{ errors['cesavedac'] }}
                        </p>
                    </template>
                    Cesavedac
                </CheckboxElement>
                <StaticElement 
                    content="<div></div>"
                    :columns="{ container:12, wrapper:12}"/>
                <button class="py-2 px-4 rounded text-white bg-green-500 col-span-2">Crear</button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>