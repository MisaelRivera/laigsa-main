<script setup>
    import { ref, computed } from 'vue';
    const props = defineProps({
        totalItems: {
            type: Number
        },

        itemsPerPageProp: {
            type: Number,
        },

        pagesPerChunkProp: {
            type: Number,
        },

        currentPageProp: {
            type: Number,
        }
    });
    console.log(props.totalItems);
    const emit = defineEmits(['changePage']);

    function createRange (start, end) {
        let arr = [];
        for (let i = start; i <= end; i++) {
            arr.push(i);
        }

        return arr;
    }

    const itemsPerPage = ref(props.itemsPerPageProp);
    const pagesPerChunk = ref(props.pagesPerChunkProp);
    const currentPage = ref(props.currentPageProp);

    const start = ref(1);
    const totalPages = computed(() => {
        return Math.floor(props.totalItems / itemsPerPage.value); 
    });
    const end = ref(pagesPerChunk.value);
    if (end.value > totalPages.value) end.value = totalPages.value;
    console.log(totalPages.value);
    const hasNext = computed(() => {
        return end.value < totalPages.value;
    });

    const hasPrev = computed(() => {
        return start.value > pagesPerChunk.value;
    });

    function advance () {
        start.value += pagesPerChunk.value;
        end.value += pagesPerChunk.value;
        if (end.value > totalPages.value) end.value = totalPages.value;
        emit('changePage', start.value);
    }

    function back () {
        if (end.value < start.value + pagesPerChunk.value - 1) {
            end.value -= end.value % pagesPerChunk.value; 
        } else {
            end.value -= pagesPerChunk.value;
        }
        start.value -= pagesPerChunk.value;
        emit('changePage', start.value);
    }

    function handleChangePage (ev)
    {
        const target = ev.target;
        emit('changePage', parseInt(target.innerText));
    }

</script>
<template>
    <div class="px-2 py-2 bg-red-300 flex items-center">
        <i 
            class="fas fa-caret-left" 
            v-if="hasPrev"
            @click="back"></i>
        <div 
            class="bg-blue-400 py-1 px-2 mx-1 rounded text-white"
            :class="{ 'bg-red-400': currentPage === i}"
            v-for="i in createRange(start, end)"
            @click="handleChangePage">
            {{ i }}
        </div>
        <i 
            class="fas fa-caret-right" 
            v-if="hasNext"
            @click="advance"></i>
    </div>
</template>