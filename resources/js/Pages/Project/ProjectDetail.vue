<template>
    <MainLayout>

        <template #title>
            {{ project.name }}
        </template>
        <template #subtitle>
            {{ project.client.name }}
        </template>

        <template #main> 
            <h3>Cotizaciones</h3>
            <TableComponent :items="{data:project.invoices}" :root="'cotizaciones'" :actions="[]"/>
        </template>

        
    </MainLayout>
</template>
<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';

    const props = defineProps({ project:Object });

   

</script> 