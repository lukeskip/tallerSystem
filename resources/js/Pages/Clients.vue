<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentClients from '../Components/TableComponentClients.vue';
    
    const props = defineProps({
        clients: {
            type: Object,
        },   
    });
</script>

<template>
    <MainLayout>
        <template #header>
            <h1 class="text-xl font-bold">Clientes</h1>
        </template>
        <template #main>
            <div v-if="clients">
                <TableComponentClients :items="clients"/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>
        </template>

    </MainLayout>
</template>
<style>

</style>