<template>
    <div 
        class="relative w-full border-2 border-dashed rounded-md p-6 flex flex-col items-center justify-center cursor-pointer transition-colors"
        :class="isDragging ? 'border-main-color bg-blue-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop"
        @click="triggerInput"
    >
        <input
            class="hidden"
            ref="input"
            :name="name"
            type="file"
            @change="handleChange"
        />
        <div class="text-center">
            <svg v-if="!fileName" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <i v-else class="fa-solid fa-file text-3xl text-main-color mb-2"></i>
            
            <p class="mt-1 text-sm text-gray-600">
                <span v-if="fileName" class="font-medium text-main-color font-bold">{{ fileName }}</span>
                <span v-else>Arrastra un archivo aquí o haz clic para seleccionar</span>
            </p>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, defineEmits } from 'vue';

const props = defineProps({
    name: {
        type: String,
    }
});

const emit = defineEmits(['file-selected']);

const input = ref(null);
const isDragging = ref(false);
const fileName = ref("");

onMounted(() => {
    if (input.value && input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

const triggerInput = () => {
    if (input.value) {
        input.value.click();
    }
};

const handleFile = (file) => {
    if (file) {
        fileName.value = file.name;
        emit('file-selected', file);
    }
};

const handleChange = (event) => {
    const file = event.target.files[0];
    handleFile(file);
};

const handleDrop = (event) => {
    isDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFile(file);
    
    if (input.value && event.dataTransfer.files.length > 0) {
        input.value.files = event.dataTransfer.files;
    }
};
</script>