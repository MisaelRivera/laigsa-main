<template>
    <div class="custom-select">
      <div class="selected-value" @click="toggleDropdown">
        {{ selectedLabel }}
      </div>
      <div v-if="isOpen" class="options">
        <div v-for="option in options" :key="option.value" @click="selectOption(option)">
          {{ option.label }}
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
    
    const props = defineProps({
        options: {
            type: Array,
            default: [],
        },

        modelValue: {
            required: true
        }
    });
    
    const emit = defineEmits(['update:modelValue']);
    const isOpen = ref(false);
    const selected = ref(null);
      
  
      const selectedLabel = computed(() => {
        const selectedOption = props.options.find(option => option.value === selected.value);
        return selectedOption ? selectedOption.label : 'Select an option';
      });
  
      function toggleDropdown() {
        isOpen.value = !isOpen.value;
      }
  
      function selectOption(option) {
        selected.value = option.value;
        isOpen.value = false;
        emit('update:modelValue', selected.value);
      }
  
  </script>
  
  <style>
  .custom-select {
    position: relative;
    user-select: none;
  }
  .selected-value {
    padding: 10px;
    border: 1px solid #ccc;
    cursor: pointer;
  }
  .options {
    position: absolute;
    border: 1px solid #ccc;
    width: 100%;
    background: white;
  }
  .options div {
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  .options div:hover {
    background-color: #f0f0f0;
  }
  </style>