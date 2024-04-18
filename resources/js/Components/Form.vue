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
            <SecondaryButton class="mx-2">
                Guardar y agregar otro
            </SecondaryButton>
            <SecondaryButton>
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
            console.log(response.data);
            fields.value.map(field => {
                
                if(field.type === "varchar" || field.type === "longtext" || field.type === 'select'){
                    formData.value[field.slug]= ref('');
                }

                if(field.type === "decimal"){
                    formData.value[field.slug]= ref(0);
                }
            });
    
        } catch (error) {
            console.log(error);
        }
    })


    const handleSubmit = ()=>{
        router.post('/conceptos', formData.value)
        emit('close');
    }
</script>