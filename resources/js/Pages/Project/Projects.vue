<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>

        <template #title>
            Proyectos
        </template>

        <template #submenu>
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="toggleModal">
                <i class="fa-solid fa-plus"></i>
                    Proyecto
            </a>
        </template>
        
        <template #main>
            <div v-if="projects">
                <TableComponent :items="projects" :root="'proyectos'" :actions="['edit','delete']" parentId='client_id'/>
            </div>
            <div v-else>
                <p>No hay proyectos disponibles.</p>
            </div>

            <Modal :show="showModal" @close="showModal = false" >
                <Form route="proyectos" @close="toggleModal()"/>
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
        projects: {
            type: Object,
        },   
    });

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };
    

</script>