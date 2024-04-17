<template>
    <template v-if="action ==='delete'">
        <button @click="confirmDelete(id)">
            <img class="icon" src="@/assets/img/trash.svg" alt="">
        </button>
    </template>
    <template v-else-if="action ==='edit'">
        <button @click="handleEdit(id)">
            <img class="icon" src="@/assets/img/edit.svg" alt="">
        </button>
    </template>
</template>
<script setup>
    import { router } from '@inertiajs/vue3';
  

    defineProps({
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
        router.delete(route('proyectos.destroy', id));
    }
    const handleEdit = (id)=>{
        router.get(`/proyectos/${id}/edit`);
    }

    const confirmDelete = (id) => {
        if (confirm("¿Estás seguro de que quieres borrar este elemento?")) {
            handleDelete(id);
        }
    };

</script>