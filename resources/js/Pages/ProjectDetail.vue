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
            <h3>Cotizaciones</h3>
            <TableComponent :items="{data:project.invoices}" :root="'cotizaciones'" :actions="[]"/>
        </template>
    </MainLayout>
</template>