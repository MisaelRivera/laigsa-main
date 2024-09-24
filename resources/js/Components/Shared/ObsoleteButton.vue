<script setup>
    defineProps({
        funct: Function,
        args: Array
    })
</script>

<template>
    <button 
        class="ml-2 flex justify-center items-center w-8 h-8 rounded-full bg-yellow-500 text-white"
        @click="funct(...args)">
        <i class="fas fa-ban"></i>
    </button>
</template>
