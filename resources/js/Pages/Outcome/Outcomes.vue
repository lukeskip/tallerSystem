<template>
    <Head title="Egresos" />
    <AuthenticatedLayout>
        <template #title>
          Egresos
        </template>

        <template #main>
            <div v-if="outcomes">
                <TableComponent :items="outcomes" :root="'egresos'" :actions="['edit','delete']" :searchField="'description'"/>
            </div>
            <div v-else>
                <p>No hay Egresos disponibles.</p>
            </div>

            <Modal :show="showModal" @close="showModal = false" >
                <Form route="egresos" @close="toggleModal()" />
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
        outcomes: {
            type: Object,
        },   
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
</script>