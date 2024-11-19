<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
       task: Object 
    });

    const formState = useForm({
        name: null
    });
    const form$ = ref(null);
    const handleCreate = () => {
        formState.name = form$.value.el$('name').value;
        formState.post(`/tasks`);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto mt-16">
            <h1>
                <Link
                    :href="route('tasks.index')">
                    <i class="fas fa-left-arrow"></i>
                </Link>
                Create Task
            </h1>
           <Vueform
                :endpoint="false"
                @submit="handleCreate"
                :columns="{container:12, wrapper:12}"
                ref="form$">
                <TextElement
                    name="name"
                    :columns="{contaienr:12, wrapper:12}"
                    before="Name">
                    <template #description>
                        <p 
                            v-if="formState.errors.name"
                            class="text-red-500">
                            {{ formState.errors.name }}
                        </p>
                    </template>
                </TextElement>
                <button
                    class="bg-green-500 text-white rounded-md py-1 px-2">
                    Create
                </button>
           </Vueform>
        </div>
    </AuthenticatedLayout>
</template>