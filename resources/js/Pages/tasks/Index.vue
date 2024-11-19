<script setup>
    import { Link, useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Notivue, Notification, push } from 'notivue';
    import { useMessages } from '@/composables/messages';
    const props = defineProps({
        tasks: Object,
        filters: Object, 
    });

    const deletedTask = useForm({
        name: null,
    });
    
    const { getMessage } = useMessages();

    if (getMessage()) {
        push.success(getMessage());
    }

    const handleDeleteTask = (task) => {
        deletedTask.name = task.name;
        deletedTask.delete()
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-7/12 mx-auto my-16">
            <h1>
                Create task 
                <Link :href="route('tasks.create')">
                    <i class="fas fa-plus py-2 px-2 bg-green-500 text-white rounded-full"></i>
                </Link>
            </h1>
            <table class="border">
                <thead>
                    <tr>
                        <th class="border py-1 px-2">Name</th>
                        <th class="border py-1 px-2">Completed</th>
                        <th class="border py-1 px-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(task, index) in tasks.data">
                        <td class="border py-1 px-2">{{ task.name }}</td>
                        <td class="border py-1 px-2">{{ task.is_completed ? 'Yes':'No' }}</td>
                        <td class="border py-1 px-2">
                            <Link :href="route('tasks.edit', task.id)">
                                <i class="fas fa-edit py-2 px-2 rounded-full bg-blue-500 text-white"></i>
                            </Link>
                            <i 
                                class="cursor-pointer fas fa-trash py-2 px-2 rounded-full bg-red-500 text-white ml-4"
                                @click="() => handleDeleteTask(task)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Notivue v-slot="item">
            <Notification :item="item"/>
        </Notivue>
    </AuthenticatedLayout>
</template>
