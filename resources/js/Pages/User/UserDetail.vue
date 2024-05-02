<template>
    <AuthenticatedLayout>

        <template #title>
            {{ user.name }}
        </template>
        

        <template #main> 
            <div v-if="user.projects">
                <TableComponent :items="user.projects" :root="'proyectos'" :actions="['edit','delete']" parentId='client_id'/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

        
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({ user:Object });

   

</script> 