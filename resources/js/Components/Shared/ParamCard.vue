<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  result: Object,
  sample: Object,
  user: Object
});

// Permission flags
const isAnalyst = computed(() => props.user.iniciales === props.result.iniciales_analistas && !props.result.esta_supervisado);
const isSupervisor = computed(() => props.user.iniciales === props.result.iniciales_supervisor && props.result.esta_supervisado && props.result.status !== 'quality_checked');
const isQualityManager = computed(() => props.user.role === 'quality_manager');

// Result logic
const results = ref(props.result.resultados ?? []);
const isComposed = computed(() => props.result.es_compuesto);
const composedCount = computed(() => props.sample.tipo_muestreo === 'compuesto_4' ? 4 : 6);
const showLimitField = ref(results.value.map(r => r !== null && r < props.result.lcp));

// Logic to handle "<" toggle and editing
const toggleLowerLimit = (index) => {
  showLimitField.value[index] = false;
};
</script>

<template>
  <div class="border rounded p-4">
    <h3 class="font-bold">{{ result.name }}</h3>

    <div class="flex flex-col space-y-2">
      <template v-for="(res, idx) in isComposed ? composedCount : 1" :key="idx">
        <div class="flex items-center space-x-2">
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

          <input
            v-else
            type="text"
            :value="result.lower_limit"
            readonly
            class="bg-gray-100 border p-1 rounded w-32"
          />
        </div>
      </template>
    </div>

    <!-- Metadata -->
    <div class="text-xs mt-2 text-gray-600">
      Analyst: <span>{{ result.analyst_initials }}</span> | 
      Date: <span>{{ result.date }}</span>
    </div>

    <!-- Actions -->
    <div class="mt-2 space-x-2" v-if="isAnalyst || isSupervisor || isQualityManager">
      <button
        v-if="isAnalyst"
        @click="saveResults"
        class="bg-blue-500 text-white text-sm px-2 py-1 rounded">
        Save
      </button>
      <button
        v-if="isSupervisor"
        @click="approveResult"
        class="bg-green-500 text-white text-sm px-2 py-1 rounded">
        Approve
      </button>
      <button
        v-if="isSupervisor || isQualityManager"
        @click="reopenForEdit"
        class="bg-yellow-400 text-black text-sm px-2 py-1 rounded">
        Reopen
      </button>
      <button
        v-if="isQualityManager"
        @click="finalApprove"
        class="bg-purple-600 text-white text-sm px-2 py-1 rounded">
        Final Approve
      </button>
    </div>
  </div>
</template>