
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

      <table v-if="itemsRef[firstProperty] && itemsRef[firstProperty].length" class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
       
        <thead class="sticky top-0 text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr >
                  <template v-for="(value, key) in  itemsRef[firstProperty][0]" :key="key">
                    <th v-if="key !== 'id'"  class="px-6 py-3">
                      {{ showLabel(key) }}
                    </th>
                  </template>
                  <template v-if="actions.length">
                      <th>Acciones</th>
                  </template>
              </tr>
        </thead>
        <tbody>
            <template v-for="(items, category) in itemsRef" :key="category">
               
                <tr class="w-full"> <!-- Ajusta el color de fondo según tu preferencia -->
                    <td class="px-6 py-3 text-center" :colspan="Object.keys(itemsRef[firstProperty][0]).length">
                    {{ category }}
                    </td>
                </tr>

                <tr v-for="item in items" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">  
                    <template v-for="(value, key, index) in item">
                        <td v-if="key!=='id'" class="border px-4 py-2">
                            <template v-if="index=== 1">
                                <Link :href="route(`${root}.show`,item.id)">{{ value }}</Link>
                            </template>
                            <template v-else>{{ value }}</template>
                        </td>
                    </template>
                    <td class="border px-4 py-2 text-center" v-if="actions.length">
                        <ActionButton v-for="(action,index) in actions" :key="index + action" :root="root" :action="action" :id="item.id" :parentId="[parentId,item[parentId]]"/>
                    </td>
                </tr>
                
            </template>
        </tbody>
    </table>
     
    </div>

    
</template>

<script setup>
import { ref,onUpdated,onMounted } from 'vue';
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



const itemsRef = ref(props.items);
const searchTerm = ref('');
const firstProperty = ref(Object.keys(props.items)[0]);


onUpdated(()=>{
  if(searchTerm.value === ''){
    itemsRef.value = props.items;
  }
})
onMounted(()=>{
  console.log(props.items[firstProperty.value]);
})



const submitSearch = (root) => {
  router.get(root, { search: searchTerm.value })
};
const submitSearchFilter = () => {
  itemsRef.value = filter(props.items,"label",searchTerm.value);
};

</script>