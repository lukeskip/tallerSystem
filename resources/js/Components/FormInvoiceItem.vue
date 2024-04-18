<template>
    <form @submit.prevent="handleSubmit()" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Etiqueta:
            </label>
            <TextInput v-model="label"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Descripción:
            </label>
            <TextInput v-model="description"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Precio unitario:
            </label>
            <NumberInput v-model="unit_price"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Cantidad de Unidades:
            </label>
            <NumberInput v-model="units"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Tipo de unidad:
            </label>
            <TextInput v-model="unit_type"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Comissión:
            </label>
            <NumberInput v-model="comission"/>
        </div>
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Proveedor:
            </label>
            <Select v-model="provider" :options="providers"/>
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
    import {ref} from 'vue';
    import { router } from '@inertiajs/vue3';
    import axios from 'vue-axios';

    const props = defineProps({
        invoiceId:{
            type:Number,
            required:true
        },
        providers:{
            type:Array,
            required:true
        }
    })
    const label = ref("");
    const description = ref("");
    const comission = ref(0);
    const provider = ref("");
    const units = ref(0);
    const unit_price = ref(0);
    const unit_type= ref("");
    const _token = window.csrf_token;


    const handleSubmit = ()=>{
        router.post('/conceptos', { 
            label:label.value,
            description:description.value,
            comission:comission.value,
            provider_id:provider.value,
            unit_type:unit_type.value,
            unit_price:unit_price.value,
            units:units.value,
            provider_id:provider.value,
            invoice_id: props.invoiceId,
            _token})
        
        emit('close');
    }
</script>