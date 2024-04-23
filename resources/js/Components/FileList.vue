<template>
    <div class="flex mt-5 gap-4">
        <CardFile v-if="files" v-for="file in files" :item="file" :key="file.id"/>
    </div>
</template>
<script setup>
import CardFile from '@/Components/CardFile.vue'
const props = defineProps({
    files:{
        type:Object,
        required:true
    }
})
</script>
<style>

</style>