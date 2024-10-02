<script setup>
    import { useForm, Head } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';

    const props = defineProps({
        errors: Object,
        user: Object,
    });
    const form = useForm({
        name: props.user.name,
        email: props.user.email,
    });


    const submit = (form$, FormData) => {
        form.put(route('users.update', props.user.id));
    };
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Register" />
        <NavLayout>
            <div class="grid grid-cols-12">
                <CreateTitle 
                    title="Edita al usuario"
                    :own-link="route('users.create')"
                    :back-link="route('users.index')"
                    class="col-span-6 col-start-3 mb-2"/>
                <Vueform
                    v-model="form"
                    :endpoint="false"
                    class="col-start-3 col-span-6 border p-4 rounded-lg"
                    @submit="submit"
                    :columns="{container:12, wrapper:12}"
                    sync>
                    <TextElement 
                        name="name"
                        before="Name"
                        :description="errors.name ? `<p class='text-red-600'>${errors.name}</p>`:null"
                        :columns="{container:6, wrapper:12}"
                        v-model="form.name"/>
                    <TextElement 
                        name="email"
                        input-type="email"
                        :description="errors.email ? `<p class='text-red-600'>${errors.email}</p>`:null"
                        before="Email"
                        :columns="{container:6, wrapper:12}"
                        v-model="form.email"/>
                    <button
                        name="create_user"
                        class="py-1 px-2 rounded bg-blue-500 text-white col-span-2">
                        Editar
                    </button>
                </Vueform>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>