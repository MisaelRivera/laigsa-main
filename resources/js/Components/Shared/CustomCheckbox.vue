<script setup>
    import { ref } from 'vue';
    const emits = defineEmits(['update:modelValue', 'change-state']);
    const props = defineProps({
        modelValue: {
            required: true
        },

        name: {
            type: String,
            required: true,
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
    const propsChecked = ref(props.checked);
    const handleChangeState = () => {
        propsChecked.value = !propsChecked.value;
        emits('update:modelValue', propsChecked.value);
        emits('change-state', propsChecked.value);
        console.log(propsChecked.value);
    };
</script>
<template>
    <input 
        type="checkbox"
        :name="name"
        :id="id"
        :disabled="disabled"
        :checked="propsChecked">
    <label 
        :for="id"
        class="border rounded w-5 h-5 flex justify-center"
        :class="{'bg-[#07bf9b]':propsChecked, 'bg-white':!propsChecked}"
        @click="handleChangeState">
        <i class="fas fa-check text-white text-sm"></i>    
    </label>
    <span :class="labelClasses">{{ labelText }}</span>
</template>