<template>
    <div class="relative">
         <input
             class="border-gray-300 w-full focus:border-main-color rounded-md"
             ref="input"
             :name="name"
             type="file"
             @change="handleChange"
         />
    </div>
 </template>
 
 <script setup>
 import { onMounted,ref,defineEmits } from 'vue';
 
 const props = defineProps({
     name: {
         type: String,
     }
 });

 const emit = defineEmits(['file-selected']); 
 
 const input = ref(null);
 
 onMounted(() => {
     if (input.value.hasAttribute('autofocus')) {
         input.value.focus();
     }
 });
 
 const handleChange = (event) => {
    const file = event.target.files[0];
    emit('file-selected', file);
 }
 </script>
 