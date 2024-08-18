<template>
    <div v-if="invoiceItem">
        <Head :title="`Concepto ${invoiceItem?.label}`"/>
        <AuthenticatedLayout>
        
            <template #headerLeft>
            <div>
                    <h1 class="text-3xl font-bold text-main-color uppercase">Cotización {{invoiceItem.id}} </h1>
                    <h2 class="text-xl font-bold uppercase">
                        {{ invoiceItem.label }}
                    </h2>
                    
            </div>
                
            </template>
            <template #headerRight>
                <div class="flex space-x-1 justify-end mt-5">
                    <a href="#" class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600" @click="">
                        <i class="fa-solid fa-plus"></i>
                        Concepto
                    </a>
                    
                </div>
                
            
            </template>
            


            <template #main> 
                
                
                

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
    import { Head, Link, router } from '@inertiajs/vue3';
    import Container from '@/Components/Container.vue';
    import Modal from '@/Components/Modal.vue';
    
    import FormEdit from '@/Components/FormEdit.vue';
    
    import { ref, onMounted }from 'vue';
    import Swal from 'sweetalert2'
    import strings from '@/utils/strings';


    const props  = defineProps({
        invoiceItem: { type: [Object, Array]}
    }); 

    
    const deleteHandle = ()=>{
        Swal.fire({
            title: strings['delete_sure'],
            showCancelButton: true,
            confirmButtonText: strings['accept'],
            cancelButtonText: strings['cancel']
        }).then((result) => {
    
            if (result.isConfirmed) {
                router.delete(`/cotizaciones/${props.invoice.id}`)
            } 
        });
    }
</script> 