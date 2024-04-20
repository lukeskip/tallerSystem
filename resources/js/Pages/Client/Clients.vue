<template>
    <Head title="Clientes" />
    <MainLayout>
        <template #title>
          Clientes
        </template>

        <template #submenu>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-plus"></i>
                    Cliente
            </a>
        </template>

        <template #main>
            <div v-if="clients">
                <TableComponent :items="clients" :root="'clientes'" :actions="['edit','delete']" />
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>

            <Modal :show="showModal" @close="showModal = false" >
                <Form route="clientes" @close="toggleModal()" />
            </Modal>
        </template>

    </MainLayout>
</template>
<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';
    import Modal from '@/Components/Modal.vue';
    import Form from '@/Components/Form.vue';
    import {ref} from 'vue';
    
    const props = defineProps({
        clients: {
            type: Object,
        },   
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
</script>