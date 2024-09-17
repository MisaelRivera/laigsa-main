<script setup>
    import { onClickOutside } from '@vueuse/core';
    import { ref } from 'vue';
    const emit = defineEmits(['closeFrom']);
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

    const target = ref(null);
    if (props.closeOnClickOutside) {
        onClickOutside(target, event => emit('closeFrom'));
    }
</script>
<template>
    <Transition>
        <div 
            class="h-screen w-screen bg-black/40 flex absolute inset-0 z-10 justify-center items-center"
            v-if="modelValue">
            <div 
                class="bg-slate-100 rounded-lg h-[500px] w-[500px] relative"
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