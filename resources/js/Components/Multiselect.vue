<template>
    <multiselect
        v-model="value"
        :options="options"
        :multiple="true"
        :close-on-select="true"
        label="name"
        track-by="id"
        placeholder=""
    >
        <template #tag="{ option, remove }">
            <span class="custom-tag bg-primary">
                {{ option.name }}
                <span class="remove-tag" @click="remove(option)">x</span>
            </span>
        </template>
    </multiselect>
</template>

<script setup>
import { ref } from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

const value = ref([]);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});
</script>
<style>
.multiselect__tags-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    justify-items: center;
    align-items: center;
}
.custom-tag {
    color: white; /* Color del texto */
    padding: 0px 5px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
}

.remove-tag {
    cursor: pointer;
    font-weight: bold;
    margin-left: 5px;
    color: white;
}
</style>
