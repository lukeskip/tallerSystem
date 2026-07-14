<script setup>
const props = defineProps({
    checked: [Boolean, Number, String]
});
const emit = defineEmits(['update:checked']);

const toggle = () => {
    emit('update:checked', !props.checked);
};
</script>

<template>
    <div 
        @click="toggle"
        :class="checked ? 'bg-main-color' : 'bg-gray-300'"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
        role="switch" 
        :aria-checked="checked"
    >
        <span class="sr-only">Toggle switch</span>
        <span 
            :class="checked ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
        ></span>
    </div>
</template>
