<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { useNotification } from "@kyvg/vue3-notification";
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const { notify }  = useNotification();
    const form = useForm({
        parametro: null,
    });
    const handleCreateSubmit = async(form$, FormData) => {
        /*console.log(form$.requestData.parametro);
        form.post('/parameters');*/
    };

    const handleError = (error, details, form$) => {
        console.log(details);
    };
    notify({
        title: "Authorization",
        text: "You have been logged in!",
    });
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-4/12 p-5 rounded-lg bg-gray-100">
            <h2>Crea un nuevo parametro</h2>
            <!--<a-form
                layout="vertical"
                :model="form"
                @finish="handleCreateSubmit">
                <div class="mb-4">
                    <a-form-item 
                        label="Parametro"
                        name="parametro"
                        :rules="[{ required: true, message: 'Ingrese el parametro' }]">
                        <a-input
                            v-model:value="form.parametro"
                            class="rounded h-8"/>
                    </a-form-item>
                    <p 
                        class="text-red-500"
                        v-if="form.errors.parametro">
                        {{ form.errors.parametro }}
                    </p>
                </div>
                <button class="rounded bg-green-400 text-white py-2 px-4">
                    Crear
                </button>
            </a-form>-->
            <Vueform
                :endpoint="false"
                @submit="handleCreateSubmit"
                :display-errors="false"
                @error="handleError">
                <TextElement 
                    name="parametro"
                    before="Parametro"
                    rules="required"/>
                <ButtonElement
                    submits
                    name="submit">
                    Crear
                </ButtonElement>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>