<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '../Components/TableComponent.vue';

    const props = defineProps({ project:Object });

</script> 
<template>
    <MainLayout>
        <template #header> 
            <h1 class="text-xl font-bold">{{ project.name }}</h1>
            <h2>{{ project.client.name }}</h2>
        </template>
        <template #main> 
            <TableComponent type="projects" :items="project.invoices" />
        </template>
    </MainLayout>
</template>