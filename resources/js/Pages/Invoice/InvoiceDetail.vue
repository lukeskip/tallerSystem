<template>
    <Head :title="`Cotización ${invoice.total}`"/>
    <AuthenticatedLayout>
        
        <template #headerLeft>
           <div>
                <h1 class="text-3xl font-bold text-main-color uppercase">Cotización {{invoice.id}} </h1>
                <h2 class="text-xl font-bold uppercase">
                    {{ invoice.project.name }} / {{ invoice.client }}
                </h2>
                <h3>
                    {{ invoice.format_date }} / Estatus: {{ showLabel(invoice.status) }}
                </h3>
           </div>
            
        </template>
        <template #headerRight>
            <div class="flex space-x-1 justify-end mt-5">
                <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                    <i class="fa-solid fa-plus"></i>
                    Concepto
                 </a>
                <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModalIncome">
                    <i class="fa-solid fa-plus"></i>
                        Ingreso
                </a>
                <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModalOutcome">
                    <i class="fa-solid fa-plus"></i>
                        Egreso
                </a>
                <a href="#" class="inline-block py-2 px-4 bg-red-800 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="deleteHandle">
                    <i class="fa-solid fa-trash"></i>
                </a>
                <a target="_blank" :href="route('publish',invoice.id)" class="inline-block py-2 px-4 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-800" as="button">
                    <i class="fa-solid fa-download"></i>
                </a>
            </div>
            <div class="">
                <table class="w-full mt-5 text-right">
                    <tr class="bg-secondary-color border p-4">
                        <td class="p-2">
                            Subtotal:
                        </td>
                        <td class="p-2">
                            {{ invoice.subtotal }}
                        </td>
                    </tr>
                    <tr class="bg-terciary-color border p-4">
                        <td class="p-2">
                            Fee ({{ invoice.fee }}):
                        </td>
                        <td class="p-2">
                            {{ invoice.fee_amount }}
                        </td>
                    </tr>
                    <tr class="bg-secondary-color border p-4">
                        <td class="p-2">
                            Subtotal:
                        </td>
                        <td class="p-2">
                            {{ invoice.subtotal_fee }}
                        </td>
                    </tr>
                    <tr class="bg-terciary-color border p-4">
                        <td class="p-2">
                            IVA ({{ invoice.iva }}): 
                        </td>
                        <td class="p-2">
                            {{ invoice.iva_amount }}
                        </td>
                    </tr>
                    <tr class="bg-secondary-color ">
                        <td class="p-2">
                            Total: 
                        </td>
                        <td class="p-2">
                            {{ invoice.total }}
                        </td>
                    </tr>
                    <tr class="bg-terciary-color border p-4">
                        <td class="p-2">
                            Pagado:
                        </td>
                        <td class="p-2">
                            {{ invoice.amount_paid }}
                        </td>
                    </tr>
                    <tr class="bg-secondary-color p-4">
                        <td class="p-2">
                            Balance:
                        </td>
                        <td class="p-2">
                            {{ invoice.balance }}
                        </td>
                    </tr>
                </table>
            </div>
           
        </template>
        

        
        
        <template #main> 
            <div>
                <!-- Tabs labels -->
                <div class="flex">
                    <button
                        :class="{ 'bg-main-color text-white': activeTab === 1, 'bg-gray-100 text-gray-800 hover:bg-gray-200': activeTab !== 1 }"
                        class="py-2 px-4 focus:outline-none"
                        @click="toggleTab(1)"
                    >
                        Conceptos
                    </button>
                    <button
                        :class="{ 'bg-main-color text-white': activeTab === 2, 'bg-gray-100 text-gray-800 hover:bg-gray-200': activeTab !== 2 }"
                        class="py-2 px-4 focus:outline-none"
                        @click="toggleTab(2)"
                    >
                        Ingresos
                    </button>
                    <button
                        :class="{ 'bg-main-color text-white': activeTab === 3, 'bg-gray-100 text-gray-800 hover:bg-gray-200': activeTab !== 3 }"
                        class="py-2 px-4 focus:outline-none"
                        @click="toggleTab(3)"
                    >
                        Egresos
                    </button>
                    <button
                        :class="{ 'bg-main-color text-white': activeTab === 4, 'bg-gray-100 text-gray-800 hover:bg-gray-200': activeTab !== 4 }"
                        class="py-2 px-4 focus:outline-none"
                        @click="toggleTab(4)"
                    >
                        Deuda
                    </button>
                    <button
                        :class="{ 'bg-main-color text-white': activeTab === 5, 'bg-gray-100 text-gray-800 hover:bg-gray-200': activeTab !== 5 }"
                        class="py-2 px-4 focus:outline-none"
                        @click="toggleTab(5)"
                    >
                        Configuración
                    </button>
                </div>

                <!-- Tabs content -->
                <div class="border-t border-gray-200 rounded">
                    <!-- Tab 1 -->
                    <div v-if="activeTab === 1">
                        <TableComponentInvoiceItems :items="invoice.invoiceItems" :inner="true" :root="'conceptos'" :actions="['edit','delete']" :searchField="'label'" parentId="invoice_id"/>
                    </div>
                    
                    <!-- Tab 2 -->
                    <div v-if="activeTab === 2">
                        <TableComponent :items="invoice.incomes" :inner="true" :root="'ingresos'" :searchField="'description'" :actions="['edit','delete']" parentId="invoice_id"/>
                    </div>

                    <!-- Tab 3 -->
                    <div v-if="activeTab === 3">
                        <TableComponent :items="invoice.outcomes" :inner="true" :root="'egresos'" :actions="['edit','delete']" parentId="invoice_id"
                        :searchField="'description'"/>
                    </div>

                    <!-- Tab 4 -->
                    <div v-if="activeTab === 4">
                        <TableComponent :items="invoice.debts" :inner="true" />
                    </div>
                    <!-- Tab 5 -->
                    <div v-if="activeTab ===5">
                        <FormEdit :default="{project_id:invoice.project.id}"  :route="'cotizaciones'"  :editId="invoice.id"/>
                    </div>
                    
                    
                </div>
            </div>

            
            <Modal :show="showModal" @close="showModal = false" >
                <Form :default="{invoice_id:invoice.id,comission:Number(invoice.comission)}" :route="'conceptos'" @close="toggleModal()" :parentId="invoice.id"/>
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
    import { Head, Link, router } from '@inertiajs/vue3';
    import TableComponentInvoiceItems from '@/Components/TableComponentInvoiceItems.vue';
    import TableComponent from '@/Components/TableComponent.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import FormEdit from '@/Components/FormEdit.vue';
    import { ref, onMounted }from 'vue';
    import Swal from 'sweetalert2'
    import showLabel from '@/helpers/showLabel'


    const props  = defineProps({
        invoice: { type: [Object, Array], required: true },
        providers: { type: Array, required: true },
    });

    onMounted(()=>{
        console.log(props.invoice);
    });


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
                router.delete(`/cotizaciones/${props.invoice.id}`)
            } 
        });
    }
</script> 