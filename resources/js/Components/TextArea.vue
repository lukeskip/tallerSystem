<template>
    <div class="relative">
         <textarea
             class="border-gray-300 w-full focus:border-main-color rounded-md shadow-sm"
             v-model="model"
             ref="input"
             :name="name"
         ></textarea>
    </div>
 </template>
 
 <script setup>
 import { onMounted, ref } from 'vue';
 
 const model = defineModel({
     type: String,
     required: true,
 });
 const props = defineProps({
     name: {
         type: String,
     },
 });
 
 const input = ref(null);
 
 onMounted(() => {
     if (input.value.hasAttribute('autofocus')) {
         input.value.focus();
     }
 });
 
 defineExpose({ focus: () => input.value.focus() });
 

 </script>
 