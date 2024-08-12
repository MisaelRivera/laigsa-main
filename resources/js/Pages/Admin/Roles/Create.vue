<script setup>
    import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import NavLayout from '@/Layouts/NavLayout.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    const props = defineProps({
        roles: Object,
    });
    const page = usePage();
    const form = useForm({
        name: '',
    });
    const handleSubmit = () => {
        try{
            form.post(route('roles.store'));
        } catch (e) {
            console.log(e)
        }
    };
</script>
<template>
    <Head title="Manage roles"/>
    <AuthenticatedLayout>
        <NavLayout>
            <div class="flex justify-between">
                <h1>Roles index page</h1>
                <Link 
                    :href="route('roles.create')"
                    class="py-2 px-3 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
                    New role
                </Link>
            </div>
            <div class="mt-6">
                <form @submit.prevent="handleSubmit">
                    <div>
                        <label for="name">Name</label>
                        <input 
                            type="text"
                            name="name"
                            id="name"
                            class="w-full block mt-1"
                            v-model="form.name"
                            required
                            />
                    </div>
                    <button class="rounded bg-green-500 text-white py-2.5 px-4 mt-2">Create</button>
                </form>
            </div>
        </NavLayout>
    </AuthenticatedLayout>
</template>