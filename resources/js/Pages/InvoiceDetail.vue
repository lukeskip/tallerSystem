<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentInvoicesItems from '../Components/TableComponentInvoicesItems.vue';

    const props = defineProps({ invoice:Object });
    console.log(props.invoice);
</script> 
<template>
    <MainLayout>
        <template #header> 
            <h1 class="text-xl font-bold">Cotización({{ invoice.amount }})</h1>
            <h2>{{ invoice.client }}</h2>
            <h3>{{ invoice.format_date }}</h3>
        </template>
        <template #main> 
            <TableComponentInvoicesItems :items="invoice.invoiceItems" />
            <h3>Total: {{ invoice.amount }}</h3>
        </template>
    </MainLayout>
</template>