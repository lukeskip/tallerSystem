<template>
    <Head :title="`Cotización ${invoice.amount}`"/>
    <AuthenticatedLayout>
        
        <template #title>
            Cotización {{invoice.id}} ({{ invoice.amount }}) 
        </template>
        <template #subtitle>
            {{ invoice.client }}
        </template>
        <template #subtitle2>
            {{ invoice.format_date }}
        </template>

        
        <template #submenu>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-plus"></i>
                    Concepto
            </a>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModalIncome">
                <i class="fa-solid fa-plus"></i>
                    Ingreso
            </a>
            <a href="#" class="inline-block py-2 px-4 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="deleteHandle">
                <i class="fa-solid fa-trash"></i>
            </a>
            <a target="_blank" :href="route('publish',invoice.id)" class="inline-block py-2 px-4 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-700" as="button">
                <i class="fa-solid fa-download"></i>
            </a>
        </template>
        <template #main> 
            <TableComponentInvoiceItems :items="invoice.invoiceItems" :inner="true" :root="'conceptos'" :actions="['edit','delete']" parentId="invoice_id"/>
            
            <Modal :show="showModal" @close="showModal = false" >
                <Form :default="{invoice_id:invoice.id}" :route="'conceptos'" @close="toggleModal()" :parentId="invoice.id"/>
            </Modal>
            <Modal :show="showModalIncome" @close="showModalIncome = false" >
                <Form :default="{invoice_id:invoice.id}" :route="'ingresos'" @close="toggleModalIncome()"/>
            </Modal>

        </template>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentInvoiceItems from '@/Components/TableComponentInvoiceItems.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import { ref, onMounted }from 'vue';
    import Swal from 'sweetalert2'


    const props  = defineProps({
        invoice: { type: [Object, Array], required: true },
        providers: { type: Array, required: true },
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };

    const showModalIncome = ref(false);
    const toggleModalIncome = () => {
        showModalIncome.value = !showModalIncome.value;
    };

    
    const deleteHandle = ()=>{
        Swal.fire({
            title: "Do you want to save the changes?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Don't save`
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    }
</script> 