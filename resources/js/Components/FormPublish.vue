<template>
    <div class="bg-white px-8 pt-6 pb-8 rounded">
        <h2 class="text-xl mb-4 font-bold">Publicar Cotización</h2>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Título para la cotización</label>
            <TextInput v-model="publishForm.title" />
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Moneda</label>
            <Select v-model="publishForm.currency" :options="currencyOptions" :default="'MXN'" />
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de cambio</label>
            <NumberInput v-model="publishForm.exchange_rate" step="0.01" />
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Idioma</label>
            <Select v-model="publishForm.language" :options="languageOptions" :default="'es'" />
        </div>
        
        <div class="mt-5 flex justify-end">
            <SecondaryButton @click="$emit('close')" class="mr-2">Cancelar</SecondaryButton>
            <a 
                :href="publishUrl" 
                target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-main-color border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                @click="$emit('close')"
            >
                Publicar
            </a>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";
import Select from "@/Components/Select.vue";
import NumberInput from "@/Components/NumberInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    invoiceId: {
        type: [String, Number],
        required: true,
    }
});

defineEmits(['close']);

const publishForm = ref({
    title: '',
    currency: 'MXN',
    exchange_rate: 1,
    language: 'es'
});

const currencyOptions = [
    { id: 'MXN', name: 'MXN' },
    { id: 'USD', name: 'USD' },
    { id: 'EUR', name: 'EUR' }
];

watch(() => publishForm.value.currency, async (newCurrency) => {
    if (newCurrency === 'USD' || newCurrency === 'EUR') {
        try {
            const res = await fetch(`https://open.er-api.com/v6/latest/${newCurrency}`);
            const data = await res.json();
            if (data && data.rates && data.rates.MXN) {
                // Redondeamos a 2 decimales para que sea amigable en el input
                publishForm.value.exchange_rate = Math.round(data.rates.MXN * 100) / 100;
            }
        } catch (e) {
            console.error("Error obteniendo el tipo de cambio:", e);
        }
    } else {
        publishForm.value.exchange_rate = 1;
    }
});

const languageOptions = [
    { id: 'es', name: 'Español' },
    { id: 'en', name: 'English' }
];

const publishUrl = computed(() => {
    // Generamos la ruta nativa y le adjuntamos los parametros
    const baseUrl = route('publish', props.invoiceId);
    const params = new URLSearchParams({
        title: publishForm.value.title,
        currency: publishForm.value.currency,
        exchange_rate: publishForm.value.exchange_rate,
        language: publishForm.value.language,
    });
    return `${baseUrl}?${params.toString()}`;
});
</script>
