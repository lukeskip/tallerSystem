<template>
    <template v-if="action ==='delete'">
        <button class="mx-5" @click="handleDelete(id)">
            <i class="fa-solid fa-trash"></i>
        </button>
    </template>
    <template v-else-if="action ==='edit'">
        <button @click="handleEdit(id)">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </template>
</template>
<script setup>
    import { router } from '@inertiajs/vue3';
    import Swal from 'sweetalert2'
    import strings from '@/utils/strings.js'
  

    const props = defineProps({
        action:{
            type:String,
            required:true,
        },
        id:{
            type:Number,
            required:true,
        },
        root:{
            type:String,
            required:true
        }
    });

    const handleDelete = (id)=>{
        Swal.fire({
            title: "Confirma que quieres borrar este item",
            showCancelButton: true,
            confirmButtonText: strings.delete,
            cancelButtonText:strings.cancel
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                router.delete(route(`${props.root}.destroy`, id));
            }
        });
        
    }
    const handleEdit = (id)=>{
        router.get(`/proyectos/${id}/edit`);
    }

</script>