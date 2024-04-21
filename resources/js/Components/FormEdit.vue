<template>
    <form @submit.prevent="handleSubmit()" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="mt-2" v-for="field in fields">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                {{ field.label }}
            </label>
            
            <TextInput v-if="field.type === 'varchar' || field.type === 'longtext'" v-model="formData[field.slug]"/>
            
            <NumberInput v-else-if="field.type === 'decimal'"  v-model="formData[field.slug]"/>
            
            <Select v-else-if="field.type === 'select'"  v-model="formData[field.slug]" :options="field.options" :default="formData[field.slug]" />
            <div class="error" v-if="errors[field.slug]">{{strings.required}}</div>
        </div>

       <div class="mt-5">
            <PrimaryButton class="mx-2">
                Guardar
            </PrimaryButton>
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
    import strings from '@/utils/strings.js'
    
    const emit  = defineEmits(['close']);

    const props = defineProps({
        parentId:{
            type:Array,
        },
        toggleModal:{
            type:Function
        },
        route :{type:String,required:true},
        editId:{type:Number},
        default:{type:Object}
    })

    const _token = window.csrf_token;
    const app_url = window.app_url;
    const errors = ref([]);
   

    const fields = ref([]);
    const formData = ref({
        ...props.default,
        _token
    });
    

    onMounted(async()=>{
        try {
            
            const response = await axios(`${app_url}/${props.route}/${props.editId}/edit`);
            fields.value = response.data.fields;
            console.log(response.data);
            clearFormData();
            fillInfo(response.data.item);
    
        } catch (error) {
            console.log(error);
        }

        
    });

    const fillInfo = (fieldsEdit)=>{
        console.log(fieldsEdit);
        for (let key in fieldsEdit) {  
            if(fieldsEdit[key]['type'] === 'number' || fieldsEdit[key]['type']==='select'){
                formData.value[key]= Number(fieldsEdit[key]['value']);  
            }else{
                formData.value[key]= fieldsEdit[key]['value'];
            }           
        }        
    }

    const clearFormData = ()=>{
        fields.value.map(field => {      
            if(field.type === "varchar" || field.type === "longtext"  || field.type === "text"){
                formData.value[field.slug]= ref('');
            }

            if(field.type === "decimal" || field.type === 'select'){
                formData.value[field.slug]= ref(0);
            }
        });
    }


    const handleSubmit = async (stay = false)=>{
        try {  
            const response = await axios.put(`/${props.route}/${props.editId}`,formData.value);
            if(stay){
                clearFormData()
            }else{
                console.log(`/${response.data.redirect}`);
                emit('close');
            }
            router.reload();
        } catch (error) {
            errors.value = error.response.data.errors;
            console.log(error);
        }
    }


</script>