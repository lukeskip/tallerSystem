<template>
    <div class="formWrapper relative bg-white shadow-md rounded px-8 pt-6 pb-8">
        <Loader v-if="loader" />
        <template v-if="fields.length">
            <form @submit.prevent="handleSubmit()">
                <div class="mt-2" v-for="field in fields">
                    <template v-if="field.type !== 'hidden'">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            {{ showLabel(field.slug) }}
                        </label>

                        <TextInput
                            v-if="field.type === 'varchar'"
                            v-model="formData[field.slug]"
                            :autocomplete="field.autocomplete"
                        />

                        <div v-if="field.type === 'file'">
                            <FileInput
                                v-on:file-selected="handleFileSelected"
                            />
                            
                            <div class="mt-2 flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <button type="button" @click="startMobileUpload(field.slug)" class="px-3 py-1.5 bg-slate-800 text-slate-100 text-xs font-semibold rounded-lg hover:bg-slate-700 transition flex items-center space-x-1.5">
                                        <i class="fa-solid fa-qrcode text-amber-400"></i>
                                        <span>Subir desde celular</span>
                                    </button>
                                    <span v-if="mobileUploadStatus === 'uploading'" class="text-xs text-amber-500 animate-pulse flex items-center space-x-1">
                                        <i class="fa-solid fa-spinner animate-spin"></i>
                                        <span>Esperando foto...</span>
                                    </span>
                                    <span v-else-if="mobileUploadStatus === 'completed'" class="text-xs text-green-600 flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Foto recibida</span>
                                    </span>
                                </div>

                                <!-- QR Modal / Section -->
                                <div v-if="showQrCode" class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex flex-col items-center text-center">
                                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(mobileUploadUrl)}`" alt="Código QR" class="w-40 h-40 border border-slate-300 rounded-lg p-1 bg-white mb-2 shadow-sm">
                                    <p class="text-xs text-slate-600 font-medium">Escanea con tu celular para tomar/subir foto</p>
                                    <button type="button" @click="cancelMobileUpload" class="mt-2 text-xs text-red-500 hover:text-red-600 font-semibold underline">Cancelar</button>
                                </div>

                                <!-- Mobile image preview -->
                                <div v-if="mobilePreviewUrl" class="mt-2 relative inline-block">
                                    <img :src="mobilePreviewUrl" class="h-24 w-auto rounded-lg border border-slate-300 object-cover">
                                    <button type="button" @click="clearMobileUpload(field.slug)" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white rounded-full p-1 text-xs hover:bg-red-600 transition shadow">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <TextArea
                            v-if="
                                field.type === 'text' ||
                                field.type === 'longtext'
                            "
                            v-model="formData[field.slug]"
                            :autocomplete="field.autocomplete"
                        />

                        <NumberInput
                            v-else-if="
                                field.type === 'decimal' || field.type === 'int'
                            "
                            v-model="formData[field.slug]"
                        />

                        <NumberInput
                            v-else-if="field.type === 'money'"
                            v-model="formData[field.slug]"
                            type="money"
                            step="0.01"
                            min="0"
                        />

                        <Select
                            v-else-if="field.type === 'select'"
                            v-model="formData[field.slug]"
                            :options="field.options"
                        />

                        <ToggleSwitch
                            v-else-if="field.type === 'boolean'"
                            v-model:checked="formData[field.slug]"
                        />

                        <div v-else-if="field.type === 'color'" class="flex items-center space-x-3">
                            <input
                                type="color"
                                v-model="formData[field.slug]"
                                class="h-10 w-12 p-1 rounded border border-gray-300 cursor-pointer bg-white"
                            />
                            <TextInput
                                v-model="formData[field.slug]"
                                placeholder="#FFFFFF"
                                class="!w-32"
                            />
                            <div class="flex space-x-1 items-center">
                                <button
                                    v-for="preset in ['#fef08a', '#bbf7d0', '#bfdbfe', '#fbcfe8', '#fed7aa', '#e9d5ff', '#ffffff']"
                                    :key="preset"
                                    type="button"
                                    :style="{ backgroundColor: preset }"
                                    class="w-6 h-6 rounded-full border border-gray-300 hover:scale-110 transition-transform"
                                    @click="formData[field.slug] = preset"
                                ></button>
                            </div>
                        </div>

                        <div v-else-if="field.type === 'categories'">
                            <VueMultiselect
                                v-model="formData[field.slug]"
                                :options="field.options"
                                :multiple="true"
                                :taggable="true"
                                @tag="addTag($event, field.slug)"
                                tag-placeholder="Presiona Enter para crear nueva etiqueta"
                                placeholder="Busca o añade una categoría"
                                label="name"
                                track-by="id"
                            />
                        </div>

                        <div
                            class="error text-red-500"
                            v-if="errors[field.slug]"
                        >
                            {{ strings.required }}
                        </div>
                    </template>
                </div>

                <div class="mt-5">
                    <PrimaryButton class="mx-2"> Guardar </PrimaryButton>
                    <SecondaryButton class="mx-2" @click="handleSubmit(true)">
                        Guardar y agregar otro
                    </SecondaryButton>
                    <SecondaryButton @click="emit('close')">
                        Cancelar
                    </SecondaryButton>
                </div>
            </form>
        </template>

        <div
            class="bg-white shadow-md rounded px-8 pt-6 pb-8"
            v-else-if="!loader"
        >
            <p class="text-xl">No hay campos que mostrar</p>
        </div>
    </div>
</template>
<script setup>
import TextInput from "@/Components/TextInput.vue";
import FileInput from "@/Components/FileInput.vue";
import TextArea from "@/Components/TextArea.vue";
import NumberInput from "@/Components/NumberInput.vue";
import Select from "@/Components/Select.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import VueMultiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import { router } from "@inertiajs/vue3";
import { ref, onBeforeMount, onMounted, defineEmits, watch, onBeforeUnmount } from "vue";
import axios from "axios";
import strings from "@/utils/strings.js";
import showLabel from "@/helpers/showLabel.js";
import Loader from "@/Components/Loader.vue";
import errorHandler from "@/helpers/errorHandler";

const emit = defineEmits(["close"]);

const props = defineProps({
    parentId: {
        type: [String, Number],
    },
    toggleModal: {
        type: Function,
    },
    route: { type: String, required: true },
    default: { type: Object },
});

const app_url = window.app_url;
const errors = ref([]);

const fields = ref([]);
const loader = ref(true);
const formData = ref({});

onBeforeMount(() => {
    loader.value = true;
});
onMounted(async () => {
    try {
        let url = `${app_url}/${props.route}/create`;

        if (props.parentId !== undefined) {
            url += `?parentId=${props.parentId}`;
        }
        const response = await axios(url);

        fields.value = response.data;

        const _token = response.data.find((item) => {
            return item.slug === "_token";
        });

        if (_token) {
            formData.value[_token.slug] = _token.value;
        }

        clearFormData();
        loader.value = false;
    } catch (error) {
        errorHandler(error);
    }
});

const handleFileSelected = (file) => {
    formData.value["file"] = file;
};

const addTag = (newTag, fieldSlug) => {
    const tag = {
        name: newTag,
        id: newTag
    };
    const field = fields.value.find(f => f.slug === fieldSlug);
    if (field) {
        field.options.push(tag);
    }
    if (!formData.value[fieldSlug]) {
        formData.value[fieldSlug] = [];
    }
    formData.value[fieldSlug].push(tag);
};

const clearFormData = () => {
    fields.value.map((field) => {
        if (
            field.type === "varchar" ||
            field.type === "longtext" ||
            field.type === "text"
        ) {
            formData.value[field.slug] = ref(field.default || "");
        }

        if (
            field.type === "decimal" ||
            field.type === "int" ||
            field.type === "money"
        ) {
            formData.value[field.slug] = ref(field.default || 0);
        }
        if (field.type === "select") {
            formData.value[field.slug] = ref(field.default || null);
        }
        if (field.type === "boolean") {
            formData.value[field.slug] = ref(field.default || false);
        }
    });

    formData.value = {
        ...formData.value,
        ...props.default,
    };
};

const handleSubmit = async (stay = false) => {
    try {
        loader.value = true;
        const newFormData = new FormData();

        for (const key in formData.value) {
            let val = formData.value[key];
            const fieldDef = fields.value.find(f => f.slug === key);
            
            if (fieldDef && fieldDef.type === 'boolean') {
                if (val && typeof val === 'object' && val.value !== undefined) {
                    val = val.value;
                }
                val = (val === true || val === 'true' || val === 1) ? 1 : 0;
            }
            if (Array.isArray(val)) {
                val.forEach((item, index) => {
                    if (typeof item === 'object' && item !== null && !(item instanceof File)) {
                        for (const subKey in item) {
                            newFormData.append(`${key}[${index}][${subKey}]`, item[subKey]);
                        }
                    } else {
                        newFormData.append(`${key}[]`, item);
                    }
                });
                continue;
            }
            newFormData.append(key, val ?? "");
        }

        const response = await axios.post(`/${props.route}`, newFormData);
        loader.value = false;
        if (stay) {
            clearFormData();
        } else {
            emit("close");
        }
        router.reload({ preserveState: false });
    } catch (error) {
        if (error.response.data.errors) {
            errors.value = error.response.data.errors;
        }

        errorHandler(error, emit);

    }
};

const showQrCode = ref(false);
const mobileUploadUrl = ref("");
const mobileUploadToken = ref("");
const mobileUploadStatus = ref(""); // "uploading", "completed"
const mobilePreviewUrl = ref("");
let pollingInterval = null;

const startMobileUpload = async (fieldSlug) => {
    try {
        const response = await axios.post('/mobile-upload/init', {
            invoice_item_id: props.parentId && props.route === 'conceptos' ? props.parentId : null
        });
        mobileUploadUrl.value = response.data.url;
        mobileUploadToken.value = response.data.token;
        showQrCode.value = true;
        mobileUploadStatus.value = "uploading";
        
        // Start polling
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => checkMobileUploadStatus(fieldSlug), 3000);
    } catch (error) {
        console.error("Error initiating mobile upload:", error);
    }
};

const checkMobileUploadStatus = async (fieldSlug) => {
    if (!mobileUploadToken.value) return;
    try {
        const response = await axios.get(`/mobile-upload/status/${mobileUploadToken.value}`);
        if (response.data.status === 'completed' && response.data.file) {
            clearInterval(pollingInterval);
            pollingInterval = null;
            showQrCode.value = false;
            mobileUploadStatus.value = "completed";
            mobilePreviewUrl.value = response.data.file.url;
            
            // Set file data in form
            formData.value['mobile_file_id'] = response.data.file.id;
        }
    } catch (error) {
        console.error("Error checking mobile upload status:", error);
    }
};

const cancelMobileUpload = () => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
    showQrCode.value = false;
    mobileUploadStatus.value = "";
    mobileUploadToken.value = "";
};

const clearMobileUpload = (fieldSlug) => {
    cancelMobileUpload();
    mobilePreviewUrl.value = "";
    delete formData.value['mobile_file_id'];
};

onBeforeUnmount(() => {
    if (pollingInterval) clearInterval(pollingInterval);
});
</script>
<style>
.formWrapper {
    min-height: 300px;
}
</style>
