<script setup>
    import { ref } from 'vue';
    const emits = defineEmits(['update:modelValue', 'change-state']);
    const props = defineProps({
        modelValue: {
            required: true
        },

        name: {
            type: String,
            required: false,
        },

        id: {
            type: String,
        },

        checked: {
            type: Boolean,
            default:false,
        },

        disabled: {
            type: Boolean,
            default: false,
        },

        labelText: {
            type: String,
        },

        labelClasses: {
            type: Array,
        }
    });
    const handleChangeState = () => {
        const checkedValue = !props.checked;
        emits('update:modelValue', checkedValue);
        emits('change-state', checkedValue);
    };
</script>
<template>
    <input 
        type="checkbox"
        :name="name"
        :id="id"
        :disabled="disabled"
        :checked="checked">
    <label 
        :for="id"
        class="border rounded w-5 h-5 flex justify-center"
        :class="{'bg-[#07bf9b]':checked, 'bg-white':!checked}"
        @click="handleChangeState">
        <i class="fas fa-check text-white text-sm"></i>    
    </label>
    <span :class="labelClasses">{{ labelText }}</span>
</template>