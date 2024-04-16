<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentProjects from '../Components/TableComponentProjects.vue';
    
    const props = defineProps({
        projects: {
            type: Object,
        },   
    });

    const handleNextPage = (nextPageUrl) => {
        // Realiza alguna lógica para cargar los datos de la siguiente página
        // Puedes hacer una nueva solicitud HTTP aquí o actualizar los datos existentes
        console.log("Se ha solicitado la siguiente página:", nextPageUrl);
    };
</script>

<template>
    <MainLayout>
        <template #header>
            <h1 class="text-xl font-bold">Proyectos</h1>
        </template>
        <template #main>
            <div v-if="projects">
                <TableComponentProjects :items="projects" @next-page="handleNextPage"/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

    </MainLayout>
</template>
<style>

</style>