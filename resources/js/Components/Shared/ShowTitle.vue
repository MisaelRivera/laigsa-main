<script setup>
    import { Link } from '@inertiajs/vue3';
    const props = defineProps({
        title: String,
        backUrl: String,
    });
    console.log(props.backUrl);
</script>
<template>
    <div class="flex">
        <Link
            :href="backUrl"
            class="text-slate-700 text-2xl px-2 w-12 h-12 ml-3 flex justify-center items-center"
            replace>
            <i class="fas fa-arrow-left"></i>
        </Link>
        <h1 class="text-5xl text-black no-underline font-black">
            {{ title }}
        </h1>
    </div>
</template>