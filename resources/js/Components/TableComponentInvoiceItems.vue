
<template>
    
    <div>

      <form v-if="root && !inner" @submit.prevent="submitSearch(root)" class="flex justify-end space-x-4">
        <div class="flex mb-2 gap-1">
          <TextInput v-model="searchTerm" :name="'search'" />
          <PrimaryButton>
            Buscar
          </PrimaryButton>
        </div>
      </form>

      <form v-if="inner" @submit.prevent="submitSearchFilter" class="flex justify-end space-x-4">
        <div class="flex mb-2 gap-1">
          <TextInput v-model="searchTerm" :name="'search'"/>
          <PrimaryButton>
            Buscar en esta cotización
          </PrimaryButton>
        </div>
      </form>

      <table v-if="itemsRef.length" class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="sticky top-0 text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr >
                  <template v-for="(value, key) in  itemsRef[0]" :key="key">
                    <th v-if="key !== 'id' && key !== 'category'"  class="px-6 py-3">
                      {{ showLabel(key) }}
                    </th>
                  </template>
                  <template v-if="actions.length">
                      <th>Acciones</th>
                  </template>
              </tr>
          </thead>
          <tbody>
            <template v-for="item in itemsRef" :key="item.id">
              <tr class="w-full" v-if="labelCategory(item.category) && lastCategory !== ''">
                    <td class="px-6 py-3 text-center w-full capitalize" :colspan="Object.keys(itemsRef[1]).length">
                      {{ lastCategory }}
                    </td>
              </tr>
              <tr  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <template v-for="(value, key, index) in item" :key="key">
                    <td v-if="key !== 'id' && key !== 'category'" class="border px-4 py-2">
                      <template v-if="index=== 1 && root !== '' && !inner">
                        <Link :href="route(`${root}.show`,item.id)">{{ value }}</Link>
                      </template>
                      <template v-else-if="key !== 'id'">{{ value }}</template>
                    </td>
                  </template>
                  <td class="border px-4 py-2 text-center" v-if="actions.length">

                    <ActionButton v-for="(action,index) in actions" :key="index + action" :root="root" :action="action" :id="item.id" :parentId="[parentId,item[parentId]]"/>
                    
                  </td>
              </tr>
            </template>
          </tbody>
      </table>
      <div v-else>
        <p class="text-xl">No hay información que mostrar</p>
      </div>
      <template v-if="itemsRef.length && items.links">
        <Pagination :pagination="items.links"/>
      </template>
    </div>

    
</template>

<script setup>
import { ref,onUpdated,watch } from 'vue';
import showLabel from '@/helpers/showLabel';
import { Link,router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import ActionButton from '@/Components/ActionButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Form from '@/Components/Form.vue';
import filter from '@/helpers/filter'


const props = defineProps({
    items: {
      type: [Array,Object],
      required: true,
    },
    root: {
      type: String,
      default:""
    },
    actions: {
      type: Array,
      default: []
    },
    parentId:{
      type:String
    },
    inner:{
      type:Boolean,
      default:false,
    }
});



const itemsRef = ref(getData(props.items));
const searchTerm = ref('');
let lastCategory = '';

onUpdated(()=>{
  if(searchTerm.value === ''){
    itemsRef.value = getData(props.items);
    lastCategory = '';
  }
})


function getData (data){
  if(Array.isArray(data)){
    return data
  }else{
    return data.data;
  }
}

function labelCategory(category) {
  if (category !== lastCategory) {
    lastCategory = category;
    return true;
  }
  return false;
}



const submitSearch = (root) => {
  router.get(root, { search: searchTerm.value })
};
const submitSearchFilter = () => {
  itemsRef.value = filter(props.items,"label",searchTerm.value);
};

</script>