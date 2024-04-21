<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';


    const props = defineProps({
        invoices: {
            type: Object,
        },   
    });

</script>

<template>
    <Head title="Cotizaciones" />
    <AuthenticatedLayout >
        <template #title>
            Cotizaciones
        </template>
        <template #main>
            <TableComponent :items="invoices" :root="'cotizaciones'" :actions="[]"/>
        </template>


    </AuthenticatedLayout>
</template>
<style>

</style>