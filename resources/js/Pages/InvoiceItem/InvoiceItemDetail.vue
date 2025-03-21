<template>
    <div v-if="invoiceItem">
        <Head :title="`Concepto ${invoiceItem?.label}`" />
        <AuthenticatedLayout>
            <template #headerLeft>
                <div>
                    <h1 class="text-3xl font-bold text-main-color uppercase">
                        Cotización {{ invoiceItem.invoice.id }}
                    </h1>
                    <h2 class="text-xl font-bold uppercase">
                        concepto "{{ invoiceItem.label }}"
                    </h2>
                </div>
            </template>
            <template #headerRight>
                <div class="flex space-x-1 justify-end mt-5">
                    <a
                        href="#"
                        class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                        @click="toggleModalNote"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Nota
                    </a>
                    <a
                        href="#"
                        class="inline-block py-2 px-4 bg-black text-white font-semibold rounded-md shadow-md hover:bg-blue-600"
                        @click="toggleModalFile"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Archivo
                    </a>
                </div>
            </template>

            <template #main>
                <div class="description">
                    {{ invoiceItem.description }}
                </div>

                <div class="notes my-5" v-if="invoiceItem.notes.length">
                    <h3 class="text-xl font-bold">{{ strings["notes"] }}</h3>
                    <NoteList
                        v-if="invoiceItem.notes"
                        :notes="invoiceItem.notes"
                    />
                </div>
                <div class="files my-5" v-if="invoiceItem.files.length">
                    <h3 class="text-xl font-bold">{{ strings["files"] }}</h3>
                    <FileList
                        v-if="invoiceItem.files"
                        :files="invoiceItem.files"
                    />
                </div>

                <Modal :show="showModalFile" @close="showModalFile = false">
                    <Form
                        :default="{ invoice_item_id: invoiceItem.id }"
                        :route="'archivos'"
                        @close="toggleModalFile()"
                    />
                </Modal>
                <Modal :show="showModalNote" @close="showModalNote = false">
                    <Form
                        :default="{ invoice_item_id: invoiceItem.id }"
                        :route="'notas'"
                        @close="toggleModalNote()"
                    />
                </Modal>
            </template>
        </AuthenticatedLayout>
    </div>
    <div v-else>
        <AuthenticatedLayout>
            <template #title> Contenido no encontrado </template>
        </AuthenticatedLayout>
    </div>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

import Form from "@/Components/Form.vue";
import FileList from "@/Components/FileList.vue";
import NoteList from "@/Components/NoteList.vue";
import { ref, onMounted } from "vue";
import Swal from "sweetalert2";
import strings from "@/utils/strings";

const props = defineProps({
    invoiceItem: { type: [Object, Array] },
    notes: { type: [Object, Array] },
    files: { type: [Object, Array] },
});

const showModalFile = ref(false);
const toggleModalFile = () => {
    showModalFile.value = !showModalFile.value;
};

const showModalNote = ref(false);
const toggleModalNote = () => {
    showModalNote.value = !showModalNote.value;
};

const deleteHandle = () => {
    Swal.fire({
        title: strings["delete_sure"],
        showCancelButton: true,
        confirmButtonText: strings["accept"],
        cancelButtonText: strings["cancel"],
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/cotizaciones/${props.invoice.id}`);
        }
    });
};
</script>
