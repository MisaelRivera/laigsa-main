<script setup>
    import { onClickOutside } from '@vueuse/core';
    import { ref } from 'vue';
    const emit = defineEmits(['closeFrom', 'ok']);
    const props = defineProps({
        size: {
            type: String,
            default: 'md',
        },

        title: {
            type: String,
        },

        okButtonProps: {
            type: Object
        },

        cancelButtonProps: {
            type: Object
        },

        modelValue: {
            required: true,
        },

        closeOnClickOutside: {
            type: Boolean,
            default: true,
        }
    });

    const handleTimesClick = () => {
        emit('closeFrom');
    };

    const handleOkClick = () => {
        emit('ok');
    };

    const target = ref(null);
    if (props.closeOnClickOutside) {
        onClickOutside(target, event => emit('closeFrom'));
    }
</script>
<template>
    <Transition>
        <div 
            class="h-screen w-screen bg-black/40 flex fixed top-0 left-0 right-0 bottom-0 inset-0 z-10 justify-center items-center"
            v-if="modelValue">
            <div 
                class="bg-slate-100 rounded-lg h-fit w-full max-w-[500px]"
                ref="target">
                <div class="bg-blue-600 rounded-ss-lg rounded-se-lg py-3 px-4 text-white font-semibold">
                    <h2>{{ title }}</h2>
                </div>
                <span 
                    class="text-slate-400 absolute top-1 right-3 text-2xl cursor-pointer"
                    @click="handleTimesClick">
                    &times;
                </span>
                <div class="p-5">
                    <slot/>
                </div>
                <div class="flex justify-between p-2">
                    <button 
                        class="py-2 px-4 rounded-md"
                        @click="handleOkClick"
                        v-bind="okButtonProps">
                        {{ okButtonProps && okButtonProps.text ? okButtonProps.text:'Aceptar' }}
                    </button>
                    <button 
                        class="py-2 px-4 rounded-md"
                        v-bind="cancelButtonProps"
                        @click="handleTimesClick">
                        {{ cancelButtonProps && cancelButtonProps.text ? cancelButtonProps.text:'Cancelar' }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
<style scoped>
    .v-enter-active,
    .v-leave-active {
        transition: opacity 0.5s ease;
    }

    .v-enter-from,
    .v-leave-to {
        opacity: 0;
    }
</style>