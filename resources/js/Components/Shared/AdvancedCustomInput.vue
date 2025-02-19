<script setup>
    const props = defineProps({
        type: {
            type: String,
            default: 'text'
        },

        name: {
            type: String,
            default: ''
        },

        columns: {
            type: Number,
            default: 12,
        },

        labelText: {
            type: String,
            required: false,
        },

        error: {
            type: String
        }
    });

    const size = `col-span-${props.columns}`;
    const id = props.name.replace('_', '-');
</script>
<template>
    <div :class="[size]">
        <label 
            :for="id"
            v-if="labelText">{{ labelText }}</label>
        <input 
            :type="type"
            :name="name"
            :id="id"
            class="py-1 px-2 rounded border-2 border-gray-200 focus:border-teal-300 w-full">
        <p 
            class="text-sm text-red-400"
            v-if="error">{{ error }}</p>
    </div>
</template>