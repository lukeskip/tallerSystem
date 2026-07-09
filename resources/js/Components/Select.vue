<template>
    <select class="border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="model" >
      <option value="">Selecciona una opción</option>
      <option v-for="(option, index) in options" :key="index" :value="option.id">{{ option.name }}</option>
    </select>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  
  const model = defineModel();

  const props = defineProps({
    options: {
      type: Array,
      required: true
    },
    default:{
      type:[Number,String]
    }
  });

  onMounted(() => {
    if (!model.value && props.default) {
        model.value = props.default;
    }
  });

  </script>
  