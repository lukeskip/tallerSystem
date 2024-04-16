<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentInvoices from '@/Components/TableComponentInvoices.vue';


    const props = defineProps({
        invoices: {
            type: Object,
        },   
    });

</script>

<template>
    <Head title="Cotizaciones" />
    <MainLayout >
        <template #header>
            <h1>Cotizaciones</h1>
        </template>
        <template #main>
            <TableComponentInvoices :items="invoices" />
        </template>


    </MainLayout>
</template>
<style>

</style>