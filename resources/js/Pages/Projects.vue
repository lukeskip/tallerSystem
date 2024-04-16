<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '../Components/TableComponent.vue';
    
    const props = defineProps({
        projects: {
            type: Object,
        },   
    });

</script>

<template>
    <Head title="Proyectos" />
    <MainLayout>
        <template #header>
            <h1 class="text-xl font-bold">Proyectos</h1>
        </template>
        <template #main>
            <div v-if="projects">
                <TableComponent :items="projects" :root="'proyectos'" :actions="['hola']"/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

    </MainLayout>
</template>
<style>

</style>