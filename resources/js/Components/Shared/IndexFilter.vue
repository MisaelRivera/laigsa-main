<script setup>
    import { ref } from 'vue';
    import CustomInput from '@/Components/Shared/CustomInput.vue';
    const emit = defineEmits(['filter']);
    const props = defineProps({
        typeProp: {
            type: String,
        },

        valueProp: {
            type: String,
            default: ''
        },

        text: {
            type: String,
        }
    });
    const value = ref(props.valueProp);
    const type = ref(props.typeProp);
    const filter = () => {
        emit('filter', type.value, value.value );
    }
</script>
<template>
    <CustomInput 
        v-model="value"
        :label="text"
        @custom="filter"/>
</template>