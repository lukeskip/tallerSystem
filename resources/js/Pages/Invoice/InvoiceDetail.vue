<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import { ref }from 'vue';

    const { props } = defineProps({
        invoice: { type: [Object, Array], required: true },
        providers: { type: Array, required: true },
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
</script> 
<template>
    <Head :title="`Cotización ${invoice.amount}`"/>
    <MainLayout>
        <template #header> 
            <h1 class="text-xl font-bold">Cotización({{ invoice.amount }})</h1>
            <h2>{{ invoice.client }}</h2>
            <h3>{{ invoice.format_date }}</h3>
        </template>
        <template #submenu>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-plus"></i>
                    Concepto
            </a>
            <a href="#" class="inline-block py-2 px-4 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-trash"></i>
                    Borrar Cotización
            </a>
        </template>
        <template #main> 
            <TableComponent :items="invoice.invoiceItems"/>
            
            <Modal :show="showModal" @close="showModal = false" >
                <Form :providers="providers" :invoiceId="invoice.id" :fieldsRoute="'conceptos/create'" @close="toggleModal()"/>
            </Modal>
            <h3>Total: {{ invoice.amount }}</h3>
        </template>
    </MainLayout>
</template>