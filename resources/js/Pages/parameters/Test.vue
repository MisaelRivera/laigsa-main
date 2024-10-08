<template>
    <div class="grid grid-cols-12">
        <Vueform 
            ref="form$"
            class="col-span-4 col-start-5">
            <TextElement 
                name="name"
                label="Name"
                @input="handleNameInput" />
            <TextElement 
                name="email"
                label="Email" />
        </Vueform>
        <button 
            class="py-1 px-2 rounded-md bg-blue-500 text-white col-start-5 mt-2"
            @click="handleUpdateName">
            Change
        </button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  
  const form$ = ref(null)
  
  const handleNameInput = () => {
    console.log(form$.value.data);
  }

  const handleUpdateName  = () => {
    form$.value.update({ name: 'Juan', email: 'juan@laigsa.com'});
  };

  onMounted(() => {
    console.log(form$.value.data)
  });
  </script>