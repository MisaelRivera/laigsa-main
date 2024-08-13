<script setup>
    import { ref, computed } from 'vue';
    import axios from 'axios';
    const props = defineProps({
        value: Number,
        orderId: Number,
        url: String
    });
    const localValue = ref(props.value);
    const toggleState = async () => {
        const res = await axios.post(props.url, {
            value: localValue.value,
            order_id: props.orderId,
        });
        console.log(res.data);
        localValue.value = res.data ? 1:0;
    };

    const bgRed = computed(() => {
        return localValue.value === 0;
    });

    const bgGreen = computed(() => {
        return localValue.value === 1;
    });

</script>
<template>
    <div 
        class="w-6 h-6 rounded-full mx-auto"
        :class="{'bg-red-500':bgRed, 'bg-green-500': bgGreen }" 
        @click="toggleState">

    </div>
</template>