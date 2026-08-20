<template>
    <div>
        <form
            v-if="root && !inner"
            @submit.prevent="submitSearch(root)"
            class="flex justify-end space-x-4"
        >
            <div class="flex mb-2 gap-1">
                <TextInput v-model="searchTerm" :name="'search'" />
                <PrimaryButton> Buscar </PrimaryButton>
            </div>
        </form>

        <form
            v-if="inner"
            @submit.prevent="submitSearchFilter"
            class="flex justify-end space-x-4 mt-4"
        >
            <multiselect
                v-model="selected"
                :options="categories"
                placeholder=""
            />
            <div class="flex mb-2 gap-1">
                <TextInput v-model="searchTerm" :name="'search'" />
                <PrimaryButton> Buscar </PrimaryButton>
            </div>
        </form>

        <table v-if="itemsRef.length">
            <thead>
                <tr>
                    <th class="w-10"></th>
                    <template v-for="(value, key) in itemsRef[0]" :key="key">
                        <th v-if="!columnsToHide.includes(key)">
                            {{ showLabel(key) }}
                        </th>
                    </template>
                    <template v-if="actions.length">
                        <th>Acciones</th>
                    </template>
                </tr>
            </thead>
            <template v-for="group in groupedItems" :key="group.name">
                <tbody>
                    <tr class="category">
                        <td :colspan="colspanCount">
                            {{ group.name }}
                            {{ getCategoryTotal(group.name) }}
                        </td>
                    </tr>
                </tbody>
                <draggable
                    v-model="group.items"
                    tag="tbody"
                    item-key="id"
                    handle=".drag-handle"
                    :group="'invoice-items'"
                    @end="onDragEnd"
                >
                    <template #item="{ element: item, index }">
                        <tr :style="item.label_color ? { backgroundColor: item.label_color } : {}">
                            <td class="drag-handle cursor-grab text-center w-10 text-gray-400 hover:text-black">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </td>
                            <template v-for="(value, key) in item" :key="key">
                                <td v-if="!columnsToHide.includes(key)">
                                    <template v-if="key === 'label'">
                                        <div class="relative">
                                            <div>
                                                <Link
                                                    :href="
                                                        route(
                                                            `${root}.show`,
                                                            item.id
                                                        )
                                                    "
                                                    >{{ value }}</Link
                                                >
                                                <div v-if="item.label_comment" class="text-xs italic text-gray-700 mt-0.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-tag text-gray-500"></i>
                                                    <span>{{ item.label_comment }}</span>
                                                </div>
                                                <div v-if="item.discount" class="text-xs font-semibold text-emerald-700 mt-0.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-percent text-emerald-600"></i>
                                                    <span>Desc: {{ item.discount }} (-{{ item.discount_amount }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="key === 'total_comission'">
                                        <div class="relative">
                                            <div class="hasToolTip">
                                                {{ publishMoney(value) }}
                                                <div
                                                    class="toolTip !top-[-30px] !left-[50px]"
                                                >
                                                    {{
                                                        group.items[index]["comission"]
                                                    }}
                                                    {{ group.items[index]["user"] }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="key === 'unit_comission'">
                                        <div class="relative">
                                            <div class="hasToolTip">
                                                {{ value }}
                                                <div
                                                    class="toolTip !top-[-30px] !left-[50px]"
                                                >
                                                    {{
                                                        group.items[index]["comission"]
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else-if="key === 'image'">
                                        <div v-if="value" @click="lightboxImage = value" class="relative w-12 h-12 rounded-full overflow-hidden border border-gray-200 shadow-sm mx-auto cursor-pointer hover:opacity-80 transition-opacity">
                                            <img :src="value" class="w-full h-full object-cover" alt="Avatar" />
                                        </div>
                                        <div v-else class="relative w-12 h-12 rounded-full overflow-hidden border border-gray-200 flex items-center justify-center bg-gray-100 shadow-sm mx-auto">
                                            <i class="fa-solid fa-image text-gray-400"></i>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="relative">
                                            {{ value }}
                                        </div>
                                    </template>
                                </td>
                            </template>
                            <td v-if="actions.length">
                                <ActionButton
                                    v-for="(action, index) in actions"
                                    :key="index + action"
                                    :root="root"
                                    :action="action"
                                    :id="item.id"
                                    :parentId="[parentId, item[parentId]]"
                                />
                            </td>
                        </tr>
                    </template>
                </draggable>
            </template>
        </table>
        <div v-else>
            <p class="text-xl">No hay información que mostrar</p>
        </div>
        <template v-if="itemsRef.length && items.links">
            <Pagination :pagination="items.links" />
        </template>
        
        <div v-if="lightboxImage" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-75" @click="lightboxImage = null">
            <div class="relative max-w-4xl max-h-[90vh] p-4">
                <button class="absolute top-0 right-0 text-white text-3xl p-4 hover:text-gray-300" @click.stop="lightboxImage = null">
                    <i class="fa-solid fa-times"></i>
                </button>
                <img :src="lightboxImage" class="max-w-full max-h-[85vh] object-contain rounded shadow-lg" @click.stop />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onUpdated, watch, computed } from "vue";
import showLabel from "@/helpers/showLabel.js";
import { Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import ActionButton from "@/Components/ActionButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Multiselect from "@/Components/Multiselect.vue";
import filter from "@/helpers/filter";
import publishMoney from "@/helpers/publishMoney";
import draggable from "vuedraggable";
import axios from "axios";

const props = defineProps({
    items: {
        type: [Array, Object],
        required: true,
    },
    categories: {
        type: [Array, Object],
        required: true,
    },
    root: {
        type: String,
        default: "",
    },
    actions: {
        type: Array,
        default: [],
    },
    parentId: {
        type: String,
    },
    inner: {
        type: Boolean,
        default: false,
    },
});

const selected = ref([]);
const itemsRef = ref(getData(props.items));
const searchTerm = ref("");
const lightboxImage = ref(null);
const columnsToHide = [
    "id",
    "category",
    "description",
    "comission",
    "total_raw",
    "total_profit_raw",
    "agent_comission_raw",
    "label_color",
    "label_comment",
    "show_label_in_pdf",
    "item_label_id",
    "discount",
    "discount_raw",
    "discount_type",
    "discount_amount",
];

const groupedItems = ref([]);

const updateGroupedItems = () => {
    const itemsList = itemsRef.value;
    const groups = [];
    
    props.categories.forEach(cat => {
        groups.push({
            id: cat.id,
            name: cat.name,
            items: itemsList.filter(item => item.category === cat.name)
        });
    });

    // Agregar items que no tengan categoría en la lista
    const uncategorizedItems = itemsList.filter(item => {
        return !props.categories.some(cat => cat.name === item.category);
    });
    if (uncategorizedItems.length) {
        groups.push({
            id: null,
            name: 'Sin Categoría',
            items: uncategorizedItems
        });
    }
    
    groupedItems.value = groups;
};

watch(itemsRef, () => {
    updateGroupedItems();
}, { deep: true, immediate: true });

const colspanCount = computed(() => {
    if (!itemsRef.value.length) return 0;
    const visibleKeys = Object.keys(itemsRef.value[0]).filter(key => !columnsToHide.includes(key)).length;
    let count = visibleKeys + 1; // +1 para el drag handle
    if (props.actions.length) count++;
    return count;
});

onUpdated(() => {
    if (searchTerm.value === "" && selected.value.length === 0) {
        itemsRef.value = getData(props.items);
    }
});

function getData(data) {
    if (Array.isArray(data)) {
        return data;
    } else {
        return data.data;
    }
}

function getCategoryTotal(category) {
    const total = itemsRef.value
        .filter((item) => item.category === category)
        .reduce((acc, item) => acc + item.total_raw, 0);

    return publishMoney(total);
}

const submitSearch = (root) => {
    router.get(root, { search: searchTerm.value });
};
const submitSearchFilter = () => {
    itemsRef.value = filter(props.items, "label", searchTerm.value);
};

watch(selected, () => {
    if (selected.value.length === 0) {
        itemsRef.value = getData(props.items);
    } else {
        const filteredData = getData(props.items).filter((item) => {
            return selected.value.some((category) => {
                return category.name === item.category;
            });
        });

        itemsRef.value = [...filteredData];
    }
});

const onDragEnd = () => {
    const payload = [];
    groupedItems.value.forEach(group => {
        group.items.forEach((item, idx) => {
            payload.push({
                id: item.id,
                category_id: group.id,
                order: idx + 1
            });
        });
    });
    
    axios.put("/invoice-items-order", { items: payload })
        .then(() => {
            router.reload({ preserveState: true });
        })
        .catch(err => {
            console.error(err);
        });
};
</script>

<style>
.hasToolTip {
    position: relative;
    cursor: pointer;
}
.hasToolTip:hover .toolTip {
    display: block;
}
.toolTip {
    display: none;
    position: absolute;
    max-width: 200px;
    top: 0;
    left: 10px;
    font-size: 0.8em;
    background: white;
    padding: 3px 5px;
    z-index: 10;
}
</style>
