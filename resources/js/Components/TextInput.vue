<template>
   <div class="relative">
        <input
            class="border-gray-300 w-full focus:border-main-color rounded-md shadow-sm"
            v-model="model"
            ref="input"
            :name="name"
            :type="type"
            @input="handleChange"
        />
        <div v-if="autocomplete && model !== '' && autocompleteOptions.length > 0" class="absolute z-10 w-full bg-white shadow-lg rounded-md">
            <ul class="py-1">
                <li v-for="item in autocompleteOptions" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" @click="handleAutocomplete(item)">
                    {{ item }}
                </li>
            </ul>
        </div>
   </div>

</template>
<script setup>
import { onMounted, ref } from 'vue';

    const model = defineModel({
        type: String,
        required: true,
    });
    const props = defineProps({
        name: {
        type: String,
        },
        autocomplete: {
            type: [Array,String],
        },
        type:{
            type:String
        }
        
    });

    const input = ref(null);
    const autocompleteOptions = ref([]);

    onMounted(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });

    defineExpose({ focus: () => input.value.focus() });

    const handleAutocomplete = (item)=>{
        autocompleteOptions.value = [];
        model.value = item;
    }
    const handleChange = ()=>{
        
        if(autocompleteOptions.value && model.value !== ''){
            autocompleteOptions.value = props.autocomplete.filter((item)=>{
                return item.toLowerCase().includes(model.value.toLowerCase())
            });
        }
    }
</script>

