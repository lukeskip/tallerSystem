<template>
    <div class="relative w-44">
      <a @click="handleDelete(item.id)" class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 rounded-lg">
        <i class="fa-solid fa-trash"></i>
      </a>
      <a target="_blank" :href="item.url" class="  bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Encabezado de la tarjeta -->
        <div class="bg-gray-100 h-32 px-4 py-2 text-center flex items-center justify-center">
          <div class="text-6xl" v-if="['jpg','jpeg','png'].includes(item.extension)">
            <i class="fa-solid fa-image"></i>
          </div>
          <div class="text-6xl" v-else-if="['pdf'].includes(item.extension)">
            <i class="fa-solid fa-file-pdf"></i>
          </div>
          <div class="text-6xl" v-else>
            <i class="fa-solid fa-file"></i>
          </div>
        </div>
        
        <!-- Contenido de la tarjeta -->
        <div class="h-32  p-4 flex items-center justify-center">
          {{ item.name }}
        </div>
      </a>
    </div>
</template>
<script setup>
import axios  from 'axios';
import Swal from 'sweetalert2';
import strings from '@/utils/strings';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    item:{
        type:Object,
        required:true,
    }
})

const handleDelete = (id)=>{
  Swal.fire({
      title: "Confirma que quieres borrar este item",
      showCancelButton: true,
      confirmButtonText: strings.delete,
      cancelButtonText:strings.cancel
  }).then(async(result) => {
      if (result.isConfirmed) {
        try {
          const response =  await axios.delete(`/archivos/${id}`);
          router.reload({preserveState:false});
        } catch (error) {
          console.log(error);
        }

      }
  });
  
}
</script>
<style>
  
</style>