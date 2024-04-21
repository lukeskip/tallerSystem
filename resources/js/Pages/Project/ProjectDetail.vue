<template>
    <AuthenticatedLayout>

        <template #title>
            {{ project.name }}
        </template>
        <template #subtitle>
            {{ project.client.name }}
        </template>

        <template #submenu>
            <Link method="post" href="/cotizaciones" :data="{ status: 'pending',project_id:project.id }" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600" as="button">
                <i class="fa-solid fa-plus"></i>
                    Cotizacion
            </Link>
        </template>

        <template #main> 
            <h3>Cotizaciones</h3>
            <TableComponent :items="{data:project.invoices}" :root="'cotizaciones'" :actions="[]"/>

            <Modal :show="showModal" @close="showModal = false" >
                <Form :default="{project_id:project.id}" :route="'cotizaciones'" @close="toggleModal()"/>
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
    import { ref, onMounted }from 'vue';

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };

    const props = defineProps({ project:Object });

    onMounted(()=>{
        console.log(props.project);
    })

</script> 