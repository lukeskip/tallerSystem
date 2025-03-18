<template>
    <div v-if="invoice">
        <Head :title="`Cotización ${invoice?.total}`" />
        <AuthenticatedLayout>
            <template #headerCenter>
                <div ref="menu" class="flex space-x-1 justify-end mt-5 menu">
                    <div>
                        <a
                            href="#"
                            class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                            @click="toggleModal"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Concepto
                        </a>
                    </div>
                    <div>
                        <a
                            href="#"
                            class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                            @click="toggleModalIncome"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Ingreso
                        </a>
                    </div>
                    <div>
                        <a
                            href="#"
                            class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                            @click="toggleModalOutcome"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Egreso
                        </a>
                    </div>
                    <div>
                        <a
                            href="#"
                            class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                            @click="toggleModalCategories"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Categorías
                        </a>
                    </div>
                    <div>
                        <a
                            href="#"
                            class="inline-block py-2 px-4 bg-red-800 text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                            @click="deleteHandle"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                    <div>
                        <a
                            target="_blank"
                            :href="route('publish', invoice.id)"
                            class="inline-block py-2 px-4 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-800"
                            as="button"
                        >
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                </div>
            </template>
            <template #headerLeft>
                <div>
                    <h1 class="text-3xl font-bold text-main-color uppercase">
                        Cotización {{ invoice.id }}
                    </h1>
                    <h2 class="text-xl font-bold uppercase">
                        {{ invoice.project.name }} / {{ invoice.client }}
                    </h2>
                    <h3>
                        {{ invoice.format_date }} / Estatus:
                        {{ showLabel(invoice.status) }}
                    </h3>
                </div>
            </template>
            <template #headerRight>
                <div class="">
                    <table class="w-full mt-5 text-right">
                        <tr class="bg-secondary-color border p-4">
                            <td class="p-2">Subtotal:</td>
                            <td class="p-2">
                                {{ invoice.subtotal }}
                            </td>
                        </tr>
                        <tr class="bg-terciary-color border p-4">
                            <td class="p-2">Fee ({{ invoice.fee }}):</td>
                            <td class="p-2">
                                {{ invoice.fee_amount }}
                            </td>
                        </tr>
                        <tr class="bg-secondary-color border p-4">
                            <td class="p-2">Subtotal:</td>
                            <td class="p-2">
                                {{ invoice.subtotal_fee }}
                            </td>
                        </tr>
                        <tr class="bg-terciary-color border p-4">
                            <td class="p-2">IVA ({{ invoice.iva }}):</td>
                            <td class="p-2">
                                {{ invoice.iva_amount }}
                            </td>
                        </tr>
                        <tr class="bg-secondary-color">
                            <td class="p-2">Total:</td>
                            <td class="p-2">
                                {{ invoice.total }}
                            </td>
                        </tr>
                        <tr class="bg-terciary-color border p-4">
                            <td class="p-2">Pagado por el cliente:</td>
                            <td class="p-2">
                                {{ invoice.amount_paid }}
                            </td>
                        </tr>
                        <tr class="bg-secondary-color p-4">
                            <td class="p-2">Balance:</td>
                            <td class="p-2">
                                {{ invoice.balance }}
                            </td>
                        </tr>
                        <tr class="bg-terciary-color border p-4">
                            <td class="p-2">Egresos:</td>
                            <td class="p-2">
                                {{ invoice.outcomes_total }}
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
                            :class="{
                                'bg-main-color text-white': activeTab === 1,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 1,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(1)"
                        >
                            Conceptos
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 2,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 2,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(2)"
                        >
                            Ingresos
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 3,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 3,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(3)"
                        >
                            Egresos
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 4,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 4,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(4)"
                        >
                            Deuda
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 5,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 5,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(5)"
                        >
                            Importar CSV
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 6,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 6,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(6)"
                        >
                            Configuración
                        </button>
                        <button
                            :class="{
                                'bg-main-color text-white': activeTab === 7,
                                'bg-gray-100 text-gray-800 hover:bg-gray-200':
                                    activeTab !== 7,
                            }"
                            class="py-2 px-4 focus:outline-none"
                            @click="toggleTab(7)"
                        >
                            Comisiones
                        </button>
                    </div>

                    <!-- Tabs content -->
                    <div class="border-t border-gray-200 rounded">
                        <!-- Tab 1 -->
                        <div v-if="activeTab === 1">
                            <TableComponentInvoiceItems
                                :items="invoice.invoiceItems"
                                :inner="true"
                                :root="'conceptos'"
                                :actions="['edit', 'delete']"
                                :searchField="'label'"
                                parent="invoice_id"
                                :categories="categories"
                            />
                        </div>

                        <!-- Tab 2 -->
                        <Container v-if="activeTab === 2">
                            <TableComponent
                                :items="invoice.incomes"
                                :inner="true"
                                :root="'ingresos'"
                                :searchField="'description'"
                                :actions="['edit', 'delete']"
                                parentId="invoice_id"
                            />
                        </Container>

                        <!-- Tab 3 -->
                        <Container v-if="activeTab === 3">
                            <TableComponent
                                :items="invoice.outcomes"
                                :inner="true"
                                :root="'egresos'"
                                :actions="['edit', 'delete']"
                                parentId="invoice_id"
                                :searchField="'description'"
                                :columnsToHide="['amount_raw']"
                            />
                        </Container>

                        <!-- Tab 4 -->
                        <Container v-if="activeTab === 4">
                            <TableComponent
                                :items="invoice.debts"
                                :inner="true"
                            />
                        </Container>
                        <!-- Tab 5 -->
                        <Container v-if="activeTab === 5">
                            <FormImport :invoiceId="invoice.id" />
                        </Container>
                        <!-- Tab 6 -->
                        <Container v-if="activeTab === 6">
                            <FormEdit
                                :default="{ project_id: invoice.project.id }"
                                :route="'cotizaciones'"
                                :editId="invoice.id"
                            />
                        </Container>
                        <!-- Tab 7 -->
                        <Container v-if="activeTab === 7">
                            <TableComponent
                                :items="invoice.comissions"
                                :inner="true"
                                :ownerId="invoice.id"
                                :searchField="'description'"
                                :root="`cotizaciones.comissionsByUser`"
                            />
                        </Container>
                    </div>
                </div>

                <Modal :show="showModal" @close="showModal = false">
                    <Form
                        :default="{
                            invoice_id: invoice.id,
                            comission: Number(invoice.comission),
                        }"
                        :route="'conceptos'"
                        @close="toggleModal()"
                        :parentId="invoice.id"
                    />
                </Modal>
                <Modal :show="showModalIncome" @close="showModalIncome = false">
                    <Form
                        :default="{ invoice_id: invoice.id }"
                        :route="'ingresos'"
                        @close="toggleModalIncome()"
                    />
                </Modal>
                <Modal
                    :show="showModalOutcome"
                    @close="showModalOutcome = false"
                >
                    <Form
                        :default="{ invoice_id: invoice.id }"
                        :route="'egresos'"
                        @close="toggleModalOutcome()"
                    />
                </Modal>
                <Modal
                    :show="showModalCategories"
                    @close="showModalCategories = false"
                >
                    <CategoryOrder :categories="invoice.categories" />
                </Modal>
            </template>
        </AuthenticatedLayout>
    </div>
    <div v-else>
        <AuthenticatedLayout>
            <template #title> Contenido no encontrado </template>
        </AuthenticatedLayout>
    </div>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import TableComponentInvoiceItems from "@/Components/TableComponentInvoiceItems.vue";
import TableComponent from "@/Components/TableComponent.vue";
import Container from "@/Components/Container.vue";
import Modal from "@/Components/Modal.vue";
import Form from "@/Components/Form.vue";
import FormEdit from "@/Components/FormEdit.vue";
import FormImport from "@/Components/FormImport.vue";
import { ref, onMounted, onUnmounted } from "vue";
import Swal from "sweetalert2";
import showLabel from "@/helpers/showLabel";
import strings from "@/utils/strings";
import CategoryOrder from "@/Components/CategoryOrder.vue";

const props = defineProps({
    invoice: { type: [Object, Array], default: {} },
    providers: { type: Array, default: [] },
    categories: { type: Array, default: [] },
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
const showModalCategories = ref(false);
const toggleModalCategories = () => {
    showModalCategories.value = !showModalCategories.value;
};

const deleteHandle = () => {
    Swal.fire({
        title: strings["delete_sure"],
        showCancelButton: true,
        confirmButtonText: strings["accept"],
        cancelButtonText: strings["cancel"],
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            router.delete(`/cotizaciones/${props.invoice.id}`);
        }
    });
};

const handleScroll = () => {
    const menu = document.querySelector(".menu");
    if (menu) {
        if (window.scrollY > 100) {
            menu.classList.add(
                "p-5",
                "fixed",
                "bottom-5",
                "right-5",
                "z-50",
                "shadow-lg",
                "bg-secondary-color"
            );
        } else {
            menu.classList.remove(
                "p-5",
                "fixed",
                "bottom-5",
                "right-5",
                "shadow-lg",
                "bg-secondary-color"
            );
        }
    }
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
</script>
