
<template>
    <div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr >
                    <template v-for="(value, key) in items[0]" :key="key">
                      <th v-if="key !== 'id'"  class="px-6 py-3">
                        {{ labeled(key) }}
                      </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
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
    </div>
</template>
<script>
import strings from '@/utils/strings';
import { Head, Link } from '@inertiajs/vue3';
export default {
  components: {
    Link,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  methods: {
    labeled(key) {
      if(strings[key]){
        return strings[key]
      }else{
        return key;
      }
    },
    show (value){
      if(typeof value !== "object"){
        return value
      }else{
        return value.name
      }
    }

  },
};

</script>