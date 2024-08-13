<script setup>
    const props = defineProps({
        bgColor: {
            type: String,
            default: 'bg-white'
        },
        
        borderColor: {
            type: String,
            default: 'royal-blue',
        },

        bordersThick: {
            type: String,
            default: '4'
        },

        hasBorders: {
            type: Boolean,
            default: true,
        },

        bordersProp: {
            type: String,
            default: 'all',
        }
    });
    let bordersColors = '',
        bordersThick = '';
    switch (props.bordersProp) {
        case 'all':
            bordersColors = `border-${props.borderColor}`;
            bordersThick = `border-${props.bordersThick}`;
        break;
        case 'y': 
            bordersColors = `border-y-${props.borderColor}`;
            bordersThick = `border-y-${props.bordersThick}`;
        break;
        case 'x': 
            bordersColors = `border-x-${props.borderColor}`;
            bordersThick = `border-x-${props.bordersThick}`;
        break;
        case 'top': 
            bordersColors = `border-t-${props.borderColor}`;
            bordersThick = `border-t-${props.bordersThick}`;
        break;
        case 'bottom': 
            bordersColors = `border-b-${props.borderColor}`;
            bordersThick = `border-b-${props.bordersThick}`;
        break;
        case 'left': 
            bordersColors = `border-s-${props.borderColor}`;
            bordersThick = `border-s-${props.bordersThick}`;
        break;
        case 'right': 
            bordersColors = `border-e-${props.borderColor}`;
            bordersThick = `border-e-${props.bordersThick}`;
        break;
    }
    const classes = [props.bgColor];
    if (props.hasBorders) {
        classes.push(bordersColors);
        classes.push(bordersThick);
    }
</script>
<template>
    <div class="rounded py-4 pb-10 px-12"
         :class="classes">
        <slot />
    </div>
</template>