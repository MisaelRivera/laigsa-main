<template>
  <AuthenticatedLayout>
    <div class="w-6/12 mx-auto">
      <Vueform :schema="schema" :tabs="tabs" @submit="handleSubmit" :endpoint="false"/>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const handleSubmit = () => {};
// Define the schema
const schema = reactive({
  name: {
    type: 'text',
    label: 'Name',
    placeholder: 'Enter your name',
    columns: { container: 4, wrapper: 12 }
  },

  email: {
    type: 'text',
    label: 'Email',
    inputType: 'email',
    placeholder: 'Enter your email',
    columns: { container: 4, wrapper: 12 }
  },

  password: {
    type: 'text',
    label: 'Password',
    inputType: 'password',
    placeholder: 'Enter your password',
    columns: { container: 4, wrapper: 12 }
  },

  bio: {
    type: 'textarea',
    label: 'Bio',
    placeholder: 'Tell us about yourself',
  },

  submit: {
    type: 'submit',
    content: 'Submit Form',
    class: 'mt-6 btn btn-primary', // Optional styling
  }
});

// Define the tabs layout
const tabs = reactive({
  account: {
    label: 'Account Details',
    elements: ['name', 'email', 'password'],
  },

  profile: {
    label: 'Profile Information',
    elements: ['bio'],
  },
})
</script>