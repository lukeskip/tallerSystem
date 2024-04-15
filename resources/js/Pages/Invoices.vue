<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    


    const props = defineProps({
        invoices: {
            type: Array,
        },   
    });

    console.log('Facturas:', props.invoices);
</script>

<template>
    <MainLayout >
       

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr >
                <th scope="col" class="px-6 py-3">
                    Proyecto
                </th>
                <th scope="col" class="px-6 py-3">
                    Cliente
                </th>
                <th scope="col" class="px-6 py-3">
                    Fecha
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ invoice.project.name }}
                </th>
                <td class="px-6 py-4">
                    {{invoice.cliente}}
                </td>
               
                <td class="px-6 py-4">
                    {{invoice.format_date}}
                </td>
            </tr>
        </tbody>
    </table>
</div>

    </MainLayout>
</template>
<style>

</style>