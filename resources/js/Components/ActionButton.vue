<template>
    <template v-if="action ==='delete'">
        <button @click="confirmDelete(id)">Borrar</button>
    </template>
    <template v-else-if="action ==='edit'">
        <button @click="handleEdit(id)">Editar</button>
    </template>
</template>
<script setup>
    import { router } from '@inertiajs/vue3';
    import { defineProps } from 'vue';
  

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