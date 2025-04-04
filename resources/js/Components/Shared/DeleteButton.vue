<script setup>
    defineProps({
        funct: Function,
        args: Array
    })
</script>

<template>
    <div class="inline-block">
        <button 
            class="ml-2 bg-red-500 text-white flex justify-center items-center w-8 h-8 rounded-full"
            @click="funct(...args)">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</template>
