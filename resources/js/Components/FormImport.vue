<template>
    <Loader v-if="loader" />
    <form @submit.prevent="handleSubmit()" v-if="!loader">
        <div class="">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Archivo:
            </label>
            <FileInput v-on:file-selected="handleFileSelected" />
        </div>

        <div class="mt-5">
            <PrimaryButton class="mx-2"> Guardar </PrimaryButton>
            <SecondaryButton :onclick="cancelHandle">
                Cancelar
            </SecondaryButton>
        </div>
    </form>
    <div v-if="message" class="bg-green-100 p-5 my-5">
        {{ message }}
    </div>
    <div v-if="errors.length" class="mt-5 bg-gray-100 p-5">
        <h2 class="font-bold my-5">
            Errores al importar archivo, las siguientes celdas no fueron
            importadas:
        </h2>
        <ul>
            <li v-for="error in errors">
                Celda {{ error.cell }}: {{ error.label }} -
                {{ error.errors.label.join(",") }}
            </li>
        </ul>
    </div>
    <div v-if="updated.length" class="mt-5 bg-gray-100 p-5">
        <h2 class="font-bold my-5">
            Las siguientes celdas fueron actualizadas ({{ updated.length }}):
        </h2>
        <ul>
            <li v-for="record in updated">
                Celda {{ record.cell }}: {{ record.label }}
            </li>
        </ul>
    </div>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Loader from "@/Components/Loader.vue";

const props = defineProps({
    invoiceId: { type: String },
});

const formData = ref({});
const message = ref("");
const errors = ref([]);
const updated = ref([]);
const loader = ref(false);

const handleSubmit = async () => {
    try {
        const newFormData = new FormData();
        loader.value = true;

        for (const key in formData.value) {
            newFormData.append(key, formData.value[key] || "");
        }

        const response = await axios.post(
            `/import-csv/${props.invoiceId}`,
            newFormData
        );

        message.value = response.data.message;
        loader.value = false;
        updated.value = response.data?.updated;
        router.reload();
    } catch (error) {
        if (error.response.data.errors) {
            errors.value = error.response.data.errors;
        }
        message.value = error.response.data.message;
        loader.value = false;
        router.reload();
    }
};

const handleFileSelected = (file) => {
    formData.value["file"] = file;
};
const cancelHandle = (file) => {
    errors.value = [];
    formData.value["file"] = "";
};
</script>
