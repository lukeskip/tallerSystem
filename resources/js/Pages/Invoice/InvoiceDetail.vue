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
        <template #main> 
            <TableComponent :items="invoice.invoiceItems"/>
            
            <button @click="toggleModal">
                abrir
            </button>
            <Modal :show="showModal" @close="showModal = false" >
                <Form :providers="providers" :invoiceId="invoice.id" :fieldsRoute="'conceptos/create'" @close="toggleModal()"/>
            </Modal>
            <h3>Total: {{ invoice.amount }}</h3>
        </template>
    </MainLayout>
</template>