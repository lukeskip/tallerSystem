<template>
    <div class="bg-white px-8 pt-6 pb-8 rounded">
        <h2 class="text-xl mb-4 font-bold">Exportar Telas</h2>
        
        <div class="mb-4">
            <p class="text-gray-700 text-sm font-bold mb-2">Selecciona las telas a incluir:</p>
            
            <div v-if="fabrics.length === 0" class="text-sm text-gray-500 italic mb-2">
                No hay telas registradas para esta cotización.
            </div>

            <div v-if="fabrics.length > 0" class="flex justify-between items-center mb-3">
                <div class="flex-1 mr-4">
                    <input 
                        type="text" 
                        v-model="searchQuery" 
                        placeholder="Buscar telas (marca, dibujo, color)..." 
                        class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <div class="flex space-x-2 text-xs">
                    <button @click.prevent="selectAll" class="text-blue-600 hover:underline font-semibold">Seleccionar Todas</button>
                    <span class="text-gray-400">|</span>
                    <button @click.prevent="deselectAll" class="text-blue-600 hover:underline font-semibold">Deseleccionar</button>
                </div>
            </div>
            
            <div class="max-h-60 overflow-y-auto border border-gray-200 rounded p-2" v-if="fabrics.length > 0">
                <div v-if="filteredFabrics.length === 0" class="text-sm text-gray-500 text-center py-2">
                    No se encontraron telas con esa búsqueda.
                </div>
                <div v-for="fabric in filteredFabrics" :key="fabric.id" class="flex items-center mb-2">
                    <input 
                        type="checkbox" 
                        :id="'fabric_' + fabric.id" 
                        :value="fabric.id" 
                        v-model="selectedFabrics"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                    >
                    <label :for="'fabric_' + fabric.id" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">
                        {{ fabric.brand }} - {{ fabric.pattern }} - {{ fabric.color }}
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-5 flex justify-end">
            <SecondaryButton @click="$emit('close')" class="mr-2">Cancelar</SecondaryButton>
            <a 
                :href="publishUrl" 
                target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-main-color border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': selectedFabrics.length === 0 }"
                @click="$emit('close')"
            >
                Exportar
            </a>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    invoiceId: {
        type: [String, Number],
        required: true,
    },
    fabrics: {
        type: Array,
        default: () => [],
    }
});

defineEmits(['close']);

const selectedFabrics = ref([]);
const searchQuery = ref('');

const filteredFabrics = computed(() => {
    if (!searchQuery.value) return props.fabrics;
    
    const query = searchQuery.value.toLowerCase();
    return props.fabrics.filter(fabric => {
        const brand = (fabric.brand || '').toLowerCase();
        const pattern = (fabric.pattern || '').toLowerCase();
        const color = (fabric.color || '').toLowerCase();
        
        return brand.includes(query) || pattern.includes(query) || color.includes(query);
    });
});

const selectAll = () => {
    const idsToAdd = filteredFabrics.value.map(f => f.id);
    const newSelected = new Set([...selectedFabrics.value, ...idsToAdd]);
    selectedFabrics.value = Array.from(newSelected);
};

const deselectAll = () => {
    const idsToRemove = filteredFabrics.value.map(f => f.id);
    selectedFabrics.value = selectedFabrics.value.filter(id => !idsToRemove.includes(id));
};

onMounted(() => {
    // Select all fabrics by default
    selectedFabrics.value = props.fabrics.map(fabric => fabric.id);
});

const publishUrl = computed(() => {
    const baseUrl = route('publish-fabrics', props.invoiceId);
    if (selectedFabrics.value.length === 0) return '#';
    
    const params = new URLSearchParams({
        fabric_ids: selectedFabrics.value.join(','),
    });
    return `${baseUrl}?${params.toString()}`;
});
</script>
