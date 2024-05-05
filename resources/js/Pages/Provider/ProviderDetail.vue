<template>
    <AuthenticatedLayout>

        <template #title>
            {{ provider.name }}
        </template>
        <template #subtitle>
            {{ provider.contact_name }}
        </template>

        <template #main> 
           
        </template>

        
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({ provider:Object });

   

</script> 