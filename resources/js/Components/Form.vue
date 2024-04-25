<template>
    <Loader v-if="loader"/>
    <template v-if="fields.length">
        <form @submit.prevent="handleSubmit()" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <div class="mt-2"  v-for="field in fields">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ showLabel(field.slug) }}
                </label>
                
                <TextInput v-if="field.type === 'varchar' " v-model="formData[field.slug]" :autocomplete="field.autocomplete"/>
                
                <FileInput v-if="field.type === 'file' " v-on:file-selected="handleFileSelected"/>

                <TextArea v-if="field.type === 'text' || field.type === 'longtext'" v-model="formData[field.slug]" :autocomplete="field.autocomplete"/>
                
                <NumberInput v-else-if="field.type === 'decimal'"  v-model="formData[field.slug]"/>
                
                <Select v-else-if="field.type === 'select'"  v-model="formData[field.slug]" :options="field.options"/>
                <div class="error" v-if="errors[field.slug]">{{strings.required}}</div>
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
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8" v-else-if="!loader">
        <p class="text-xl">No hay campos que mostrar</p>
    </div>
</template>
<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import FileInput from '@/Components/FileInput.vue';
    import TextArea from '@/Components/TextArea.vue';
    import NumberInput from '@/Components/NumberInput.vue';
    import Select from '@/Components/Select.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { router } from '@inertiajs/vue3';
    import {ref,onMounted,defineEmits} from 'vue';
    import axios from 'axios';
    import strings from '@/utils/strings.js';
    import showLabel from '@/helpers/showLabel.js';
    import Loader from '@/Components/Loader.vue'
    
    const emit  = defineEmits(['close']);

    const props = defineProps({
        parentId:{
            type:[String,Number],
        },
        toggleModal:{
            type:Function
        },
        route :{type:String,required:true},
        default:{type:Object}
    })

    const _token = window.csrf_token;
    const app_url = window.app_url;
    const errors = ref([]);

    const fields = ref([]);
    const loader = ref(true);
    const formData = ref({
        ...props.default,
        _token
    });
    

    onMounted(async ()=>{
        try {
            loader.value = true;
            let url = `${app_url}/${props.route}/create`;

            if (props.parentId !== undefined) {
                url += `?parentId=${props.parentId}`;
            }
            const response = await axios(url);
            loader.value = false;
            fields.value = response.data;
            

            clearFormData();
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.message,
            });
        }
    });

    const handleFileSelected = (file) => {
        formData.value['file'] = file; 
    };

    const clearFormData = ()=>{
        fields.value.map(field => {      
            if(field.type === "varchar" || field.type === "longtext" || field.type === "text"){
                formData.value[field.slug]= ref('');
            }

            if(field.type === "decimal" || field.type === 'select'){
                formData.value[field.slug]= ref(0);
            }
        });
    }


    const handleSubmit = async (stay = false)=>{
        try {  

            const newFormData = new FormData();
        
            for (const key in formData.value) {
                newFormData.append(key, formData.value[key]);
            }

            const response = await axios.post(`/${props.route}`,newFormData);

            if(stay){
                clearFormData()
            }else{
                emit('close');
            }
            router.reload({preserveState:false});
        } catch (error) {
            errors.value = error.response.data.errors;
            console.log(error);
        }
    }


</script>
<style>
    .formWrapper{
        min-height: 300px;
    }
</style>