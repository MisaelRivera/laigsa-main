<script setup>
</script>
<template>
    <span v-if="showLimitField[idx]" class="cursor-pointer" @click="toggleLowerLimit(idx)">
        &lt;
    </span>

    <input
        v-if="!showLimitField[idx]"
        type="number"
        :step="1 / (10 ** result.decimals)"
        v-model.number="results[idx]"
        :readonly="!isAnalyst && !isSupervisor && !isQualityManager"
        class="border p-1 rounded w-32"
    />
</template>