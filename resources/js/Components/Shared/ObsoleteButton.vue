<script setup>
    import { Button } from 'ant-design-vue';
    import { DislikeOutlined } from '@ant-design/icons-vue';
    defineProps({
        funct: Function,
        args: Array
    })
</script>

<template>
    <Button 
        class="ml-2 flex justify-center items-center w-8 h-8 rounded-full bg-yellow-500 text-white"
        @click="funct(...args)">
        <DislikeOutlined/>
    </Button>
</template>
