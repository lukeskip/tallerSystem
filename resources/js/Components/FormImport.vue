<template>
    <form @submit.prevent="handleSubmit()">
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
            <SecondaryButton>
                Cancelar
            </SecondaryButton>
       </div>
    </form>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import FileInput from '@/Components/FileInput.vue';
import {ref,onBeforeMount,onMounted,defineEmits} from 'vue';
        

    const formData = ref({});
    
    const handleSubmit = async (stay = false)=>{
        try {  

            const newFormData = new FormData();
        
            for (const key in formData.value) {
                newFormData.append(key, formData.value[key] || "");
            }

            newFormData.append('_method', 'put');
            loader.value = true;
            const response = await axios.post(`/${props.route}/${props.editId}`,newFormData);
            loader.value = false;

            if(response.data.message){
                message.value = response.data.message;
            }

            if(stay){
                clearFormData()
            }else{
                emit('close');
            }
            router.reload();
        } catch (error) {
            if(error.response.data.errors){
                errors.value = error.response.data.errors;
            }
            
            loader.value = false;

            errorHandler(error,emit);
        }
    }

    const handleFileSelected = (file) => {
        formData.value['file'] = file; 
    };
</script>