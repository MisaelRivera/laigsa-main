<script setup>
    const props = defineProps({
        type: String,
        size: String,
        text: String,
    });
    let bgColor = 'bg-green-500';
    let color = 'text-white';
    let rounded = `rounded-${props.size}`;
    if (size === 'sm')
        rounded = 'rounded';
    let pX = 'px-',
        pY = 'py-';
    switch (props.type) {
        case 'primary':
            bgColor = 'bg-blue-500';
        break;
        case 'danger':
            bgColor = 'bg-red-500';
        break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            color = 'text-black'
        break;
    }

    switch (props.size) {
        case 'sm':
            pX += 2;
            pY += 1;
        break;
        case 'md':
            pX += 4;
            pY += 2;
        break;
        case 'lg':
            pX += 5;
            pY += 3;
        break;
    }
</script>

<template>
    <button :class="[rounded, bgColor, pY, pX, color]">
        {{ text }}
    </button>
</template>