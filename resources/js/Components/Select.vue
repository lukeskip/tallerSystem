<template>
    <select class="border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="selectedOption">
      <option value="" disabled>Selecciona una opción</option>
      <option v-for="(option, index) in options" :key="index" :value="option.id">{{ option.name }}</option>
    </select>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    options: {
      type: Array,
      required: true
    },
  });

  const selectedOption = ref('');

  </script>
  