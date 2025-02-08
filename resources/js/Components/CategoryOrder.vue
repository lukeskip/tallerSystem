<template>
    <div class="p-4">
        <h3>Orden de categorías</h3>
        <p>Arrastra las categorías para que esten en el orden que lo necesitas</p>
        <draggable 
            v-if="localCategories.length"
            v-model="localCategories" 
            tag="ul" 
            item-key="id"
            @end="emitSortedCategories"
            
        >
            <template #item="{ element }">
                <li class="category-item">
                    {{ element.name }}
                </li>
            </template>
        </draggable>
    </div>
</template>

<script setup>
import axios from 'axios';
import { defineProps, defineEmits, ref, watch, nextTick } from 'vue';
import draggable from 'vuedraggable';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    categories: { type: Array, default: () => [] },
});

const emit = defineEmits(["update:categories"]);

const localCategories = ref([...props.categories]);

watch(() => props.categories, (newVal) => {
    localCategories.value = [...newVal];
}, { deep: true });

const emitSortedCategories = async () => {
    await nextTick();
    
    submit();
};


const submit = () => {
    const categories = localCategories.value.map((category, index) => {
        return {
            id: category.id,
            order: index + 1
        };
    });
    try {
        axios.put('/categorias-order', {
            categories
        });
        router.reload({preserveState:false});
    } catch (error) {
        console.error(error);
    }
    console.log(localCategories.value);
};
</script>

<style scoped>
ul {
    list-style: none;
    padding: 0;
}
.category-item {
    padding: 10px;
    margin: 5px 0;
    background: #f4f4f4;
    border: 1px solid #ddd;
    cursor: grab;
}
</style>