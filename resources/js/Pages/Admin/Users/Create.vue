<script setup>
    import { useForm, Head } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const props = defineProps({
        errors: Object
    });
    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });
    const submit = (form$, FormData) => {
        form.name = form$.requestData.name;
        form.email = form$.requestData.email;
        form.password = form$.requestData.password;
        form.password_confirmation = form$.requestData.password_confirmation;
        form.post(route('users.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Register" />
        <NavLayout>
            <div class="grid grid-cols-12">
                <CreateTitle 
                    title="Crear un usuario"
                    :own-link="route('users.create')"
                    :back-link="route('users.index')"
                    class="col-span-6 col-start-3 mb-2"/>
                <Vueform
                    :endpoint="false"
                    class="col-start-3 col-span-6 border p-4 rounded-lg"
                    @submit="submit"
                    :columns="{container:12, wrapper:12}">
                    <TextElement 
                        name="name"
                        before="Name"
                        :description="errors.name ? `<p class='text-red-600'>${errors.name}</p>`:null"
                        :columns="{container:6, wrapper:12}"/>
                    <TextElement 
                        name="email"
                        input-type="email"
                        :description="errors.email ? `<p class='text-red-600'>${errors.email}</p>`:null"
                        before="Email"
                        :columns="{container:6, wrapper:12}"/>
                    <TextElement 
                        name="password"
                        input-type="password"
                        :description="errors.password ? `<p class='text-red-600'>${errors.password}</p>`:null"
                        before="Password"
                        :columns="{container:6, wrapper:12}"/> 
                    <TextElement 
                        name="password_confirmation"
                        input-type="password"
                        before="Confirmar password"
                        :columns="{container:6, wrapper:12}"/>
                    <ButtonElement
                        submits
                        name="create_user">
                        Crear
                    </ButtonElement>
                </Vueform>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>