<script setup>

const props = defineProps({
  tabs: {
    type: Array,
    required: true,
  },
  modelValue: {
    type: Number,
    required: true,
  }
});

const emit = defineEmits(['update:modelValue']);

const isActive = (index) => props.modelValue === index;

const selectTab = (index) => {
  emit('update:modelValue', index);
};
</script>

<template>
  <div class="mb-4">
    <!-- Tab Headers -->
    <div class="flex space-x-2 border-b mb-2">
      <button
        v-for="(tab, index) in tabs"
        :key="index"
        @click="selectTab(index)"
        :class="[
          'px-4 py-2 rounded-t text-sm font-medium',
          isActive(index) ? 'bg-white border border-b-0' : 'bg-gray-200'
        ]"
      >
        Sample {{ tab }}
      </button>
    </div>

    <!-- Tab Content Slot -->
    <div class="bg-white p-4 border rounded-b">
      <slot name="tab-content" :tabIndex="modelValue"></slot>
    </div>
  </div>
</template>