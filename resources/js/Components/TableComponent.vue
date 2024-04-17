
<template>
    
    <div>
      <form @submit.prevent="submitSearch(root)" class="flex items-center space-x-4">
        <input v-model="searchTerm" type="text" placeholder="Buscar..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Buscar</button>
      </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr >
                    <template v-for="(value, key) in  getData(items)[0]" :key="key">
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
                <tr v-for="item in getData(items)" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <template v-for="(value, key, index) in item" :key="key">
                      <td v-if="key !== 'id'" class="border px-4 py-2">
                        <template v-if="index=== 1 && root !== ''">
                          <Link :href="route(`${root}.show`,item.id)">{{ value }}</Link>
                        </template>
                        <template v-else-if="key !== 'id'">{{ value }}</template>
                      </td>
                    </template>
                    <td class="border px-4 py-2" v-if="actions.length">
      
                      <ActionButton v-for="(action,index) in actions" :key="index + action" :root="root" :action="action" :id="item.id"/>
                      
                    </td>
                </tr>
            </tbody>
        </table>
        <template v-if="getData(items).length && items.links">
          <Pagination :pagination="items.links"/>
        </template>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import showLabel from '@/helpers/showLabel';
import { Link,router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import ActionButton from '@/Components/ActionButton.vue'


defineProps({
    items: {
      type: Object,
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
})

const getData = (data)=>{
  if(Array.isArray(data)){
    return data
  }else{
    return data.data;
  }
}


const searchTerm = ref('');

const submitSearch = (root) => {
  // Aquí puedes realizar alguna acción con el término de búsqueda (searchTerm.value)
  router.get(root, { search: searchTerm.value })
};

</script>