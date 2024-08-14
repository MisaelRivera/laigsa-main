<script setup>
    import { ref, computed } from 'vue';
    import { Link } from '@inertiajs/vue3';
    const props = defineProps({
        paginationInfo: {
            type: Object
        }
    });
   
    const emit = defineEmits(['changePage']);

    function createRange (start, end) {
        let arr = [];
        for (let i = start; i <= end; i++) {
            arr.push(i);
        }

        return arr;
    }

    const renderingStart = ref(props.paginationInfo.current_page - 2),
          renderingEnd = ref(props.paginationInfo.current_page + 2);
    if(renderingStart.value < 1) renderingStart.value = 1;
    if(renderingEnd.value > props.paginationInfo.last_page) renderingEnd.value = props.paginationInfo.last_page;
    const hasStartElipsis = computed(() => {
        return renderingStart.value > 1;
    });

    const hasEndElipsis = computed(() => {
        return renderingEnd.value < paginationInfo.last_page;
    });


    
</script>
<template>
    <div class="px-2 py-2 bg-red-300 flex items-center">
        <Link :href="paginationInfo.first_page_url">
            <i class="fas fa-caret-left" 
            v-if="paginationInfo.current_page > 1"></i>
        </Link>
        <Link 
            class="bg-blue-400 py-1 px-2 mx-1 rounded text-white"
            :class="{ 'bg-red-400': paginationInfo.current_page === i}"
            v-for="i in createRange(renderingStart, renderingEnd)"
            :href="paginationInfo.links[i].url">
            {{ paginationInfo.links[i].label }}
        </Link>
        <Link 
            v-if="paginationInfo.current < paginationInfo.last_page"
            :href="paginationInfo.last_page_url">
            <i class="fas fa-caret-right"></i>
        </Link>
    </div>
</template>