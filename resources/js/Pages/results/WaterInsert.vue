<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SampleTabs from '@/Components/Shared/SampleTabs.vue'; // custom tab component
import ParamCard from '@/Components/Shared/ParamCard.vue'; // one per param
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
  order: Object, // each with sampling_type, parameters
  user: Object // current logged-in user with role info
});

const currentTab = ref(0); // selected sample tab
</script>

<template>
  <AuthenticatedLayout>
    <SampleTabs :tabs="order.muestras_aguas.map(s => s.numero_muestra)" v-model="currentTab">
      <template #tab-content="{ tabIndex }">
        <template v-if="order.muestras_aguas[tabIndex].resultados_aguas">
          <div v-for="result in order.muestras_aguas[tabIndex].resultados_aguas" :key="result.id" class="mb-4">
            <div v-if="order.muestras_aguas[tabIndex]">
                <ParamCard
                  :result="result"
                  :sample="order.muestras_aguas[tabIndex]"
                  :user="user"
                />
            </div>
          </div>
        </template>
      </template>
    </SampleTabs>
  </AuthenticatedLayout>
</template>