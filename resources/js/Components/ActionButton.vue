<template>
    <template v-if="action ==='delete'">
        <button class="mx-5" @click="handleDelete(id)">
            <i class="fa-solid fa-trash"></i>
        </button>
    </template>
    <template v-else-if="action ==='edit'">
        <button @click="handleEdit(id)">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </template>

    <Modal :show="showModal" @close="showModal = false" >
        <FormEdit :route="root" @close="toggleModal()" :editId="id" :parentId="parentId"/>
    </Modal>
</template>
<script setup>
    import { router } from '@inertiajs/vue3';
    import Swal from 'sweetalert2'
    import strings from '@/utils/strings.js';
    import Modal from '@/Components/Modal.vue';
    import FormEdit from '@/Components/FormEdit.vue';
    import { ref } from 'vue'
  

    const props = defineProps({
        action:{
            type:String,
            required:true,
        },
        id:{
            type:Number,
            required:true,
        },
        root:{
            type:String,
            required:true
        },
        parentId:{
            type:Array,
        }
    });


    const handleDelete = (id)=>{
        Swal.fire({
            title: "Confirma que quieres borrar este item",
            showCancelButton: true,
            confirmButtonText: strings.delete,
            confirmButtonColor: "#9e915f",
            cancelButtonColor: "black",
            cancelButtonText:strings.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(route(`${props.root}.destroy`, id));
            }
        });
        
    }

    const showModal = ref(false);
    const toggleModal = () => {
        showModal.value = !showModal.value;
    };

    const handleEdit = ()=>{
        toggleModal();
    }


</script>