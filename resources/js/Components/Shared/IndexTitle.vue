<script setup>
    import { Link } from '@inertiajs/vue3';
    import { PlusOutlined } from '@ant-design/icons-vue';
    defineProps({
        title: {
            type: String,
            required: true,
        },

        addLink: {
            type: String,
            default: null
        },

        ownLink: {
            type: String
        },
    });
</script>
<template>
    <div class="flex">
        <Link 
            :href="ownLink">
            <h1 class="text-5xl text-black no-underline font-black">
                {{ title }}
            </h1>
        </Link>
        <Link 
            v-if="addLink"
            :href="addLink"
            class="text-white bg-green-300 rounded-full text-4xl px-2 w-12 h-12 ml-3 flex justify-center items-center">
            <PlusOutlined/>
        </Link>
    </div>
</template>