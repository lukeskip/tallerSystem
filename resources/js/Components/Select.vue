<template>
    <select class="border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="selectedOption" >
      <option value=0>Selecciona una opción</option>
      <option v-for="(option, index) in options" :key="index" :value="option.id">{{ option.name }}</option>
    </select>
  </template>
  
  <script setup>
  import { ref,onMounted } from 'vue';
  
  const props = defineProps({
    options: {
      type: Array,
      required: true
    },
    default:{
      type:Number
    }
  });

  onMounted(() => {
    selectedOption.value = props.default;
  });

  const selectedOption = ref(0);

  </script>
  