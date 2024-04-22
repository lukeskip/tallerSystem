<template>
    <Head title="Ingresos" />
    <AuthenticatedLayout>
        <template #title>
          Ingresos
        </template>

        <template #main>
            <div v-if="incomes">
                <TableComponent :items="incomes" :root="'ingresos'" :actions="['edit','delete']" :searchField="'description'"/>
            </div>
            <div v-else>
                <p>No hay Ingresos disponibles.</p>
            </div>

            <Modal :show="showModal" @close="showModal = false" >
                <Form route="ingresos" @close="toggleModal()" />
            </Modal>
        </template>

    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import {ref} from 'vue';
    
    const props = defineProps({
        incomes: {
            type: Object,
        },   
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
</script>