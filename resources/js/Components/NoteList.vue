<template>
    <div class="grid grid-cols-1 gap-4 mt-5 md:grid-cols-1">
        <NoteItem v-if="notes" v-for="note in notes" :item="note" :key="note.id"/>
    </div>
</template>
<script setup>
import NoteItem from '@/Components/NoteItem.vue'
const props = defineProps({
    notes:{
        type:Object,
        required:true
    }
})
</script>
<style>

</style>