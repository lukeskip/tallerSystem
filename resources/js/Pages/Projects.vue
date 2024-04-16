<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentProjects from '../Components/TableComponentProjects.vue';
    
    const props = defineProps({
        projects: {
            type: Array,
        },   
    });


</script>

<template>
    <MainLayout>
        <template #header>
            <h1 class="text-xl font-bold">Proyectos</h1>
        </template>
        <template #main>
            <div v-if="projects && projects.length">
                <TableComponentProjects type="proyectos" :items="projects"/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

    </MainLayout>
</template>
<style>

</style>