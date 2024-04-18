<template>
    <form @submit.prevent="handleSubmit()" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="mt-2" v-for="field in fields">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                {{ field.label }}
            </label>
            
            <TextInput v-if="field.type === 'varchar' || field.type === 'longtext'" v-model="formData[field.slug]"/>
            
            <NumberInput v-else-if="field.type === 'decimal'"  v-model="formData[field.slug]"/>
            
            <Select v-else-if="field.type === 'select'"  v-model="formData[field.slug]" :options="field.options"/>
        </div>
        
       <div class="mt-5">
            <PrimaryButton class="mx-2">
                Guardar
            </PrimaryButton>
            <SecondaryButton class="mx-2" @click="handleSubmit(true)">
                Guardar y agregar otro
            </SecondaryButton>
            <SecondaryButton @click="emit('close')">
                Cancelar
            </SecondaryButton>
       </div>
    </form>
</template>
<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import NumberInput from '@/Components/NumberInput.vue';
    import Select from '@/Components/Select.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { router } from '@inertiajs/vue3';
    import {ref,onMounted,defineEmits} from 'vue';
    import axios from 'axios';
    
    const emit  = defineEmits(['close']);

    const props = defineProps({
        invoiceId:{
            type:Number,
            required:true
        },
        providers:{
            type:Array,
            required:true
        },
        toggleModal:{
            type:Function
        },
        fieldsRoute :{type:String,required:true}
    })

    const _token = window.csrf_token;
    const app_url = window.app_url;

    const fields = ref([]);
    const formData = ref({
        invoice_id: props.invoiceId,
        _token
    });
    

    onMounted(async()=>{
        try {
            const response = await axios(`${app_url}/${props.fieldsRoute}`);
            fields.value = response.data;
            clearFormData();
    
        } catch (error) {
            console.log(error);
        }
    })

    const clearFormData = ()=>{
        fields.value.map(field => {      
            if(field.type === "varchar" || field.type === "longtext" || field.type === 'select'){
                formData.value[field.slug]= ref('');
            }

            if(field.type === "decimal"){
                formData.value[field.slug]= ref(0);
            }
        });
    }



    const handleSubmit = (stay = false)=>{
        router.post('/conceptos', formData.value)
        if(stay){
            clearFormData()
        }else{
            emit('close');
        }
    }


</script>