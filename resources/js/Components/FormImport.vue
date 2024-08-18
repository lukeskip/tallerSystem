<template>
    <div v-if="message" class="bg-green-100 p-5 mb-5">
        {{ message }}
    </div>
    <form  @submit.prevent="handleSubmit()" v-if="!message">
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Archivo:
            </label>
            <FileInput v-on:file-selected="handleFileSelected"/>
        </div>
        
       <div class="mt-5">
            <PrimaryButton class="mx-2">
                Guardar
            </PrimaryButton>
            <SecondaryButton onclick="cancelHandle">
                Cancelar
            </SecondaryButton>
       </div>
    </form>
    <div v-if=errors.length class="mt-5 bg-gray-100 p-5">
        <h2 class="font-bold my-5">Errores al importar archivo, las siguientes celdas no fueron importadas:</h2>
        <ul>
            <li v-for="error in errors">
               Celda {{ error.cell }}:  {{ error.errors.label.join(",") }}
            </li>
        </ul>
    </div>
</template>
<script setup>

    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    import FileInput from '@/Components/FileInput.vue';
    import { router } from '@inertiajs/vue3';
    import {ref,onBeforeMount,onMounted,defineEmits} from 'vue';

    const props = defineProps({
        invoiceId:{type:String},
    });

    const formData = ref({});
    const message = ref("");
    const errors = ref([]);
    
    const handleSubmit = async ()=>{
        try {  

            const newFormData = new FormData();
            
            for (const key in formData.value) {
                newFormData.append(key, formData.value[key] || "");
            }
        
            const response = await axios.post(`/import-csv/${props.invoiceId}`,newFormData);

            message.value = response.data.message;

            router.reload();
        } catch (error) {
         

            if(error.response.data.errors){
                errors.value = error.response.data.errors;
            }
            
            router.reload();
        
        }
    }

    const handleFileSelected = (file) => {
        formData.value['file'] = file; 
    };
    const cancelHandle = (file) => {
        error.value = [];
        formData.value['file'] = "";

    };
</script>