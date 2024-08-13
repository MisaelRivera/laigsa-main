<script setup>
    import { Button } from 'ant-design-vue';
    import { DeleteOutlined } from '@ant-design/icons-vue';
    defineProps({
        funct: Function,
        args: Array
    })
</script>

<template>
    <Button 
        class="ml-2 bg-red-500 text-white flex justify-center items-center w-8 h-8 rounded-full"
        @click="funct(...args)">
        <DeleteOutlined/>
    </Button>
</template>
