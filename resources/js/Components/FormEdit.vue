<template>
    <div class="formWrapper relative bg-white shadow-md rounded px-8 pt-6 pb-8">
        <Loader v-if="loader"/>
        <template v-if="fields.length">
            
            <form @submit.prevent="handleSubmit()">
                <div>{{ message }}</div>
                <div class="mt-2" v-for="field in fields">
                    <label v-if="field.type !== 'hidden'" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ showLabel(field.slug) }}
                    </label>
                    <TextInput v-if="field.type === 'varchar' || field.type === 'longtext' || field.type === 'text'" v-model="formData[field.slug]" :autocomplete="field.autocomplete"/>
    
                    <FileInput v-if="field.type === 'file' " v-on:file-selected="handleFileSelected"/>
                    
                    <NumberInput v-else-if="field.type === 'decimal' || field.type === 'int'"  v-model="formData[field.slug]"/>
                    
                    <Select v-else-if="field.type === 'select'"  v-model="formData[field.slug]" :options="field.options" :default="formData[field.slug]" />
                    <div class="error" v-if="errors[field.slug]">{{strings.required}}</div>
                    <div v-if="field.slug === 'comission' " class="mt-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Comission Amount
                        </label>
                        <NumberInput v-model="formData.comission_amount" />
                    </div>
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
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8" v-else-if="!loader">
            <p class="text-xl">No hay campos que mostrar</p>
        </div>
    </div>
</template>
<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import NumberInput from '@/Components/NumberInput.vue';
    import FileInput from '@/Components/FileInput.vue';
    import Select from '@/Components/Select.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { router } from '@inertiajs/vue3';
    import {ref,onBeforeMount,onMounted,defineEmits,watch} from 'vue';
    import axios from 'axios';
    import strings from '@/utils/strings.js'
    import showLabel from '@/helpers/showLabel';
    import errorHandler from '@/helpers/errorHandler';
    import Loader from '@/Components/Loader.vue'
    
    const emit  = defineEmits(['close']);

    const props = defineProps({
        parentId:{
            type:Array,
        },
        toggleModal:{
            type:Function
        },
        route :{type:String,required:true},
        editId:{type:[Number,String]},
        default:{type:Object}
    })

    const _token = window.csrf_token;
    const app_url = window.app_url;
    const errors = ref([]);
    const loader = ref(false);
   

    const fields = ref([]);
    const message = ref("");
    const formData = ref({
        ...props.default,
        comission_amount: props.default?.comission_amount ?? 0
    });
    
    onBeforeMount(()=>{
        loader.value = true;
    })

    onMounted(async()=>{
        try {
            loader.value = true;
            const response = await axios(`${app_url}/${props.route}/${props.editId}/edit`);
            loader.value = false;
            fields.value = response.data.fields;
            
            const _token = response.data.fields.find((item)=>{
                return item.slug === '_token'
            });

            if(_token){
                formData.value[_token.slug] = _token.value;
            }
            clearFormData();
            fillInfo(response.data.item);
    
        } catch (error) {
            console.log(error);
            errorHandler(error,emit);
        }

        
    });

    const fillInfo = (fieldsEdit)=>{
    
        for (let key in fieldsEdit) { 
            
            if(fieldsEdit[key]['type'] === 'number'){
                formData.value[key]= Number(fieldsEdit[key]['value'] || fieldsEdit[key]['value']);  
            }else{
                formData.value[key] = fieldsEdit[key]['value'];
            }     
            
        }        
    }

    const clearFormData = ()=>{
        fields.value.map(field => {      
            if(field.type === "varchar" || field.type === "longtext"  || field.type === "text"){
                formData.value[field.slug]= ref('');
            }

            if(field.type === "decimal" || field.type === 'select' || field.type === 'int'){
                formData.value[field.slug]= ref(0);
            }
        });
    }

    let debounceTimer = null;

    watch(() => [formData.value.unit_price, formData.value.units, formData.value.comission], () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            if (formData.value.comission !== undefined) {
                const unitPrice = parseFloat(formData.value.unit_price) || 0;
                const units = parseInt(formData.value.units) || 0;
                const comissionPercentage = parseFloat(formData.value.comission) / 100 || 0;

                formData.value.comission_amount = Number(((unitPrice * units) * comissionPercentage).toFixed(2));
            }
        }, 500);
    }, { deep: true });

 
    watch(() => formData.value.comission_amount, () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            if (formData.value.comission_amount !== undefined) {
                const unitPrice = parseFloat(formData.value.unit_price) || 0;
                const units = parseInt(formData.value.units) || 0;
                const total = unitPrice * units;

                if (total > 0) {
                    formData.value.comission = Number(((formData.value.comission_amount / total) * 100).toFixed(2));
                } else {
                    formData.value.comission = 0;
                }
            }
        }, 500);
    });

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