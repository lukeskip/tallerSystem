<template>
    <MainLayout>

        <template #title>
            {{ client.name }}
        </template>
        <template #subtitle>
            {{ client.contact_name }}
        </template>

        <template #main> 
            
        </template>

        
    </MainLayout>
</template>
<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({ client:Object });

   

</script> 