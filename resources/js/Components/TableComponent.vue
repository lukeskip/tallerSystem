<template>
    <div>
        <div class="flex justify-between space-x-4">
            <div>
                <template v-if="itemsRef.length && items.links" class="mb-7">
                    <Pagination :pagination="items.links" />
                </template>
            </div>

            <div v-if="root">
                <form @submit.prevent="inner ? submitSearchFilter() : submitSearch(root)">
                    <div class="flex mb-2 gap-1">
                        <TextInput v-model="searchTerm" :name="'search'" />
                        <PrimaryButton> Buscar </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <table v-if="itemsRef.length">
            <thead>
                <tr>
                    <template v-for="(value, key) in itemsRef[0]" :key="key">
                        <th
                            v-if="key !== 'id' && !columnsToHide.includes(key)"
                            class="px-6 py-3"
                        >
                            {{ showLabel(key) }}
                        </th>
                    </template>
                    <template v-if="actions.length">
                        <th>Acciones</th>
                    </template>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item, index) in itemsRef" :key="item.id">
                    <template v-for="(value, key, index) in item" :key="key">
                        <td v-if="key !== 'id' && !columnsToHide.includes(key)">
                            <template v-if="key === 'image'">
                                <div v-if="value" @click="lightboxImage = value" class="relative w-12 h-12 rounded-full overflow-hidden border border-gray-200 shadow-sm mx-auto cursor-pointer hover:opacity-80 transition-opacity">
                                    <img :src="value" class="w-full h-full object-cover" alt="Avatar" />
                                </div>
                                <div v-else class="relative w-12 h-12 rounded-full overflow-hidden border border-gray-200 flex items-center justify-center bg-gray-100 shadow-sm mx-auto">
                                    <i class="fa-solid fa-image text-gray-400"></i>
                                </div>
                            </template>
                            <template
                                v-else-if="index === 1 && showLink(root) && !ownerId"
                            >
                                <Link :href="route(`${root}.show`, item.id)">{{
                                    value
                                }}</Link>
                            </template>
                            <template
                                v-else-if="
                                    index === 1 && showLink(root) && ownerId
                                "
                            >
                                <Link
                                    :href="
                                        route(`${root}.show`, [
                                            ownerId,
                                            item.id,
                                        ])
                                    "
                                    >{{ value }}</Link
                                >
                            </template>
                            <template v-else-if="key !== 'id'"
                                ><span v-html="showLabel(value)"></span
                            ></template>
                        </td>
                    </template>
                    <td v-if="actions.length" class="whitespace-nowrap text-center">
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
            </tbody>
        </table>
        <div v-else>
            <NoInfo />
        </div>

        <div v-if="lightboxImage" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-75" @click="lightboxImage = null">
            <div class="relative max-w-4xl max-h-[90vh] p-4">
                <button class="absolute top-0 right-0 text-white text-3xl p-4 hover:text-gray-300" @click.stop="lightboxImage = null">
                    &times;
                </button>
                <img :src="lightboxImage" class="max-w-full max-h-[85vh] object-contain rounded shadow-lg" @click.stop />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onUpdated } from "vue";
import showLabel from "@/helpers/showLabel.js";
import { Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import ActionButton from "@/Components/ActionButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Form from "@/Components/Form.vue";
import NoInfo from "@/Components/NoInfo.vue";
import filter from "@/helpers/filter";

const lightboxImage = ref(null);

const props = defineProps({
    items: {
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
    ownerId: {
        type: String,
    },
    searchField: {
        type: [String, Array],
        default: "name",
    },
    columnsToHide: {
        type: Array,
        default: () => [],
    },
    inner: {
        type: Boolean,
        default: false,
    },
});

const itemsRef = ref(getData(props.items));
const searchTerm = ref("");

onUpdated(() => {
    if (searchTerm.value === "") {
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

const submitSearch = (root) => {
    router.get(root, { search: searchTerm.value });
};
const submitSearchFilter = () => {
    itemsRef.value = filter(props.items, props.searchField, searchTerm.value);
};

const showLink = (root) => {
    const excludeModel = ["ingresos", "egresos"];
    if (root !== "" && !excludeModel.includes(root)) {
        return true;
    } else {
        false;
    }
};
</script>
