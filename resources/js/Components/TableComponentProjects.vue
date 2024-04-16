
<template>
    <div>
      <form @submit.prevent="submitSearch" class="flex items-center space-x-4">
        <input v-model="searchTerm" type="text" placeholder="Buscar..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Buscar</button>
      </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr >
                    <template v-for="(value, key) in items.data[0]" :key="key">
                      <th v-if="key !== 'id'"  class="px-6 py-3">
                        {{ showLabel(key) }}
                      </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items.data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                  <template v-for="(value, key) in item" :key="key">
                      <td v-if="key !== 'id'" class="border px-4 py-2">
                        <template v-if="key=== 'name'">
                          <Link :href="route('proyectos.show',item.id)">{{ show(value) }}</Link>
                        </template>
                        <template v-else-if="key !== 'id'">{{ show(value) }}</template>
                      </td>
                    </template>
                </tr>
            </tbody>
        </table>
        <Pagination :pagination="items.links"/>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import showLabel from '@/helpers/showLabel';
import { Head, Link,router } from '@inertiajs/vue3';
import { reactive } from 'vue'
import Pagination from '@/Components/Pagination.vue'


defineProps({
  items: {
      type: Object,
      required: true,
    }
})

const show = (value)=>{
  if(typeof value !== "object"){
    return value
  }else{
    return value.name
  }
}

const searchTerm = ref('');

const submitSearch = () => {
  // Aquí puedes realizar alguna acción con el término de búsqueda (searchTerm.value)
  router.get('/proyectos', { search: searchTerm.value })
};

</script>