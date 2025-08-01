<script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import { useMessages } from '@/composables/messages';
  const props = defineProps({
    result: Object,
    sample: Object,
    user: Object
  });
  console.log(props.result);
  const { getError } = useMessages();
  // Permission flags
  const isAnalyst = computed(() => props.result.analistas_habilitados.includes(props.user.id) && !props.result.esta_supervisado);
  const isSupervisor = computed(() => props.result.supervisores_habilitados.includes(props.user.id) && !props.result.esta_supervisado && props.result.status !== 'quality_checked');
  const isQualityManager = computed(() => props.user.role === 'quality_manager');

  // Result logic
  const results = ref(props.result.resultados ?? []);
  const isComposed = computed(() => props.result.es_compuesto);
  const composedCount = computed(() => props.sample.tipo_muestreo === 'Compuesto 4' ? 4 : 6);
  const showLimitField = ref(results.value.map(r => r !== null && r < props.result.lcp));
  let analistAvailability = ref(false);
  let supervisorAvailability = ref(false);
  let qualityAvailability = ref(false);

  switch (props.result.status) {
    case 'pending':
      analistAvailability = isAnalyst;
    break;
    case 'supervised':
      qualityAvailability = isQualityManager;
    break;
    case 'reopened_by_supervisor':
      analistAvailability = isAnalyst && props.result.results.length > 0;
    break;
    case 'reopened_by_qm':
      supervisorAvailability = isSupervisor;
    break;
    case 'pending_supervision':
      analistAvailability = isSupervisor;
    break;
  }
  // Logic to handle "<" toggle and editing
  const toggleLowerLimit = (index) => {
    showLimitField.value[index] = false;
  };

  const filledResults = ref(
    Array.from({ length: isComposed ? composedCount : 1 }, (_, i) => {
      return props.result.resultados[i] ?? null
    })
  );

  const form = useForm({
    resultados: filledResults.value,
    fecha_resultado: props.result.fecha_resultado,
  });

  const updateResult = (index, value) => {
    form.resultados[index] = value;
  }


  const handleSubmit = () => {
    console.log(form);
    form.post('/water_samples_results/store');
  };
</script>

<template>
  <div class="border rounded p-4 w-6/12">
    <h3 class="font-bold">{{ result.name }}</h3>

    <form @submit.prevent="handleSubmit">
      <div class="flex space-x-4">
        <div>
          <p class="text-sm">{{ result.parametro }}</p>
          <p class="text-sm">{{ result.metodo }}</p>
          <p class="text-sm">{{ result.unidades }}</p>
        </div>
        <div class="flex flex-col w-full">
          <div :class="{'grid': isComposed, 'grid-cols-2': isComposed}" class="w-5/12 gap-y-2">
            <template v-for="(res, idx) in isComposed ? composedCount : 1" :key="idx">
              <div :class="{'col-span-1': isComposed}">
                <span 
                  v-if="showLimitField[idx]" 
                  class="cursor-pointer" 
                  @click="toggleLowerLimit(idx)">
                  &lt;
                </span>
                
                <input
                  v-if="!showLimitField[idx] && analistAvailability"
                  type="number"
                  :step="1 / (10 ** result.decimales)"
                  v-model.number="form.resultados[idx]"
                  class="border p-1 rounded w-32"
                />
                <div v-else-if="!showLimitField[idx] && !analistAvailability">
                  {{ result.resultados ? result.resultados[idx]:"" }}
                </div>
                <div v-else-if="showLimitField[idx]">
                  {{ result.lcp }}
                </div>
              </div>
              
            </template>
          </div>
          <p v-if="form.errors['resultados']">
            {{ form.errors['resultados'] }}
          </p>
          <div>
            <input
            v-if="analistAvailability"
            type="date"
            v-model="fechaResultados"
            class="border p-1 rounded w-32 mt-2">
            <p v-else>{{ fechaResultados }}</p>
          </div>
          <p v-if="form.errors['fecha_resultado']">
            {{ form.errors['fecha_resultado'] }}
          </p>
        </div>
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
    </form>

  </div>
</template>