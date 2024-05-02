<template>
    <Head title="Usuarios" />
    <AuthenticatedLayout>
        <template #title>
          Usuarios
        </template>

        <template #submenu>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-plus"></i>
                    Usuario
            </a>
        </template>

        <template #main>
            <div v-if="users">
                <TableComponent :items="users" :root="'usuarios'" :actions="['edit','delete']" />
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>

            <Modal :show="showModal" @close="showModal = false" >
                <Form route="usuarios" @close="toggleModal()" />
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
        users: {
            type: Object,
        },   
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
</script>