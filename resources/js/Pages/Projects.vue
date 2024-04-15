<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '../Components/TableComponent.vue';
    
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
                <TableComponent type="projects" :items="projects"/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

    </MainLayout>
</template>
<style>

</style>