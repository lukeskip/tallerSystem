<template>
    <Head :title="`Cotización ${invoice.amount}`"/>
    <AuthenticatedLayout>
        
        <template #title>
            Cotización {{invoice.id}} 
            <div>
                <span :class="{ 'line-through': invoice.balance }">({{ invoice.amount }})</span> 
                <span v-if="invoice.balance">
                    {{ invoice.balance }}
                </span>
            </div>
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
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModalOutcome">
                <i class="fa-solid fa-plus"></i>
                    Egreso
            </a>
            <a href="#" class="inline-block py-2 px-4 bg-red-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="deleteHandle">
                <i class="fa-solid fa-trash"></i>
            </a>
            <a target="_blank" :href="route('publish',invoice.id)" class="inline-block py-2 px-4 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-700" as="button">
                <i class="fa-solid fa-download"></i>
            </a>
        </template>
        <template #main> 


            <div>
                <!-- Tabs labels -->
                <div class="flex">
                    <button class="py-2 px-4 bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none" @click="toggleTab(1)">Conceptos</button>
                    <button class="py-2 px-4 bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none"  @click="toggleTab(2)">Ingresos</button>
                    <button class="py-2 px-4 bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none"  @click="toggleTab(3)">Egresos</button>
                </div>

                <!-- Tabs content -->
                <div class="p-4 border-t border-gray-200 rounded">
                    <!-- Tab 1 -->
                    <div v-if="activeTab === 1">
                        <TableComponentInvoiceItems :items="invoice.invoiceItems" :inner="true" :root="'conceptos'" :actions="['edit','delete']" parentId="invoice_id"/>
                    </div>
                    
                    <!-- Tab 2 -->
                    <div v-if="activeTab === 2">
                        <TableComponent :items="invoice.incomes" :inner="true" :root="'ingresos'" :actions="['edit','delete']" parentId="invoice_id"/>
                    </div>

                    <!-- Tab 3 -->
                    <div v-if="activeTab === 3">
                        <TableComponent :items="invoice.outcomes" :inner="true" :root="'egresos'" :actions="['edit','delete']" parentId="invoice_id"/>
                    </div>
                    
                    
                </div>
            </div>

            
            
            <Modal :show="showModal" @close="showModal = false" >
                <Form :default="{invoice_id:invoice.id}" :route="'conceptos'" @close="toggleModal()" :parentId="invoice.id"/>
            </Modal>
            <Modal :show="showModalIncome" @close="showModalIncome = false" >
                <Form :default="{invoice_id:invoice.id}" :route="'ingresos'" @close="toggleModalIncome()"/>
            </Modal>
            <Modal :show="showModalOutcome" @close="showModalOutcome = false" >
                <Form :default="{invoice_id:invoice.id}" :route="'egresos'" @close="toggleModalOutcome()"/>
            </Modal>

        </template>
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponentInvoiceItems from '@/Components/TableComponentInvoiceItems.vue';
    import TableComponent from '@/Components/TableComponent.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import { ref, onMounted }from 'vue';
    import Swal from 'sweetalert2'


    const props  = defineProps({
        invoice: { type: [Object, Array], required: true },
        providers: { type: Array, required: true },
    });

    onMounted(()=>{
        console.log(props.invoice);
    })

    const activeTab = ref(1);

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
    const toggleTab = (tab) => {
        activeTab.value = tab;
    };

    const showModalIncome = ref(false);
    const toggleModalIncome = () => {
        showModalIncome.value = !showModalIncome.value;
    };
    const showModalOutcome = ref(false);
    const toggleModalOutcome = () => {
        showModalOutcome.value = !showModalOutcome.value;
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