<template>
    <div v-if="comissions">
        <Head :title="`Cotización ${user.name}`"/>
        <AuthenticatedLayout>
            <template #headerCenter>
               
            </template>
            <template #headerLeft>
                <div>
                       
                </div>
            </template>
            <template #headerRight>
                <div class="">
                    <table class="w-full mt-5 text-right">
                        <tr class="bg-secondary-color border p-4">
                            <td class="p-2">
                                Agente: {{ user.name }}
                            </td>
                        </tr>
                        <tr class="bg-terciary-color border p-4">
                            <td class="p-2">
                                Total Comisiones: {{ total }}
                            </td>
                        </tr>
                    </table>
                </div>
            
            </template>
            

            <template #main> 
                <div>
                    <div>
                        <TableComponentInvoiceItems :inner="true" :items="comissions" :root="'conceptos'" />
                    </div>
                </div>
            </template>
        </AuthenticatedLayout>
    </div>
    <div v-else>

        <AuthenticatedLayout>
            <template #title>
                Contenido no encontrado
            </template>
        </AuthenticatedLayout>
    </div>

</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import TableComponentInvoiceItems from '@/Components/TableComponentInvoiceItems.vue';
    import { defineProps } from 'vue';

    const props  = defineProps({
        comissions: { type: Array, default: [] },
        total: { type: String },
        user: { type: Object, default: {} },
    });

    console.log(props.user);
    

</script> 