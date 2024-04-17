<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';

    const props = defineProps({ invoice:[Object,Array] });
</script> 
<template>
    <Head :title="`Cotización ${invoice.amount}`"/>
    <MainLayout>
        <template #header> 
            <h1 class="text-xl font-bold">Cotización({{ invoice.amount }})</h1>
            <h2>{{ invoice.client }}</h2>
            <h3>{{ invoice.format_date }}</h3>
        </template>
        <template #main> 
            <TableComponent :items="invoice.invoiceItems"/>
            <h3>Total: {{ invoice.amount }}</h3>
        </template>
    </MainLayout>
</template>