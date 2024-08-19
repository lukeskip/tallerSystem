<template>
    <div class="bg-gray-100 p-5 flex justify-between">
       <div class="p-5" :class="item.status === 'completed' ? 'opacity-30 line-through' :null"> {{item.content}}</div>
       <div class="flex gap-2">
          <div>
            <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="handleStatus(item.id)">

              <i v-if="item.status === 'pending'" class="fa-solid fa-check"></i>
              <i v-else class="fa-regular fa-circle-xmark"></i>
    
            </a>
          </div>
         <div>
            <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="handleDelete(item.id)">
              <i class="fa-solid fa-trash"></i>
            </a>
         </div>
       </div>
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
          const response =  await axios.delete(`/notas/${id}`);
          router.reload({preserveState:false});
        } catch (error) {
          console.log(error);
        }

      }
  }); 
}

const handleStatus = async(id)=>{
  try {
    const response =  await axios.post(`/notas-status/${id}/`);
    router.reload({preserveState:false});
  } catch (error) {
    console.log(error);
  }
}
</script>
<style>
  
</style>