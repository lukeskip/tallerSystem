<template>
    <Head :title="`${project.name}`" />
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
            <a href="#" class="inline-block py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600"  @click="toggleModalFile">
                <i class="fa-solid fa-plus"></i>
                    Archivo
            </a>
        </template>

        <template #main> 
            <h3>Cotizaciones</h3>
            
            <TableComponent :items="{data:project.invoices}" :root="'cotizaciones'" :actions="[]"/>

            <FileList v-if="project.files" :files="project.files"/>

            <Modal :show="showModalFile" @close="showModalFile = false" >
                <Form :default="{project_id:project.id}"  :route="'archivos'" @close="toggleModalFile()"/>
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
    import FileList from '@/Components/FileList.vue';
    import { ref, onMounted }from 'vue';

    const showModalFile = ref(false);
    const toggleModalFile = () => {
        showModalFile.value = !showModalFile.value;
    };

    const props = defineProps({ project:Object });

    onMounted(()=>{
        console.log(props.project);
    })

</script> 