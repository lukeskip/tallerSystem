<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';
    
    const props = defineProps({
        providers: {
            type: Object,
        },   
    });
</script>

<template>
    <Head title="Proveedores" />
    <AuthenticatedLayout>
        <template #title>
            Proveedores
        </template>
        <template #main>
            <div v-if="providers">
                <TableComponent :items="providers" :root="'proveedores'"/>
            </div>
            <div v-else>
                <p>No hay proveedores disponibles.</p>
            </div>
        </template>

    </AuthenticatedLayout>
</template>
<style>

</style>