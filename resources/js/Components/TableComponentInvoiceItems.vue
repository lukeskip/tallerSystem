
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

      <table v-if="itemsRef.length">
          <thead>
              <tr>
                  <template v-for="(value, key) in  itemsRef[0]" :key="key">
                    <th v-if="!columnsToHide.includes(key)" >
                      {{ showLabel(key) }}
                    </th>
                  </template>
                  <template v-if="actions.length">
                      <th>Acciones</th>
                  </template>
              </tr>
          </thead>
          <tbody>
            <template v-for="(item,index) in itemsRef" :key="item.id">
              <tr class="category" v-if="itemsRef[1] && labelCategory(item.category) && lastCategory !== ''">
                    <td :colspan="Object.keys(itemsRef[1]).length">
                      {{ lastCategory }} {{ getCategoryTotal(lastCategory) }}
                    </td>
              </tr>
              <tr>
                <template v-for="(value, key) in item" :key="key">
                    <td v-if="!columnsToHide.includes(key)">
                 
                      <template v-if="key === 'label'">
                        <div class="relative">
                          <div>
                            <Link :href="route(`${root}.show`,item.id)">{{ value }}</Link>
                          </div>
                         
                        </div>
                      </template>
                      <template v-else-if="key === 'total_comission'">
                        <div class="relative">
                          <div class="hasToolTip">
                            {{publishMoney(value)}}
                            <div class="toolTip !top-[-30px] !left-[50px]">
                              {{itemsRef[index]['comission']}}
                            </div>
                          </div>
                         
                        </div>
                      </template>
                      <template v-else-if="key === 'unit_comission'">
                        <div class="relative">
                          <div class="hasToolTip">
                            {{value}}
                            <div class="toolTip !top-[-30px] !left-[50px]">
                              {{itemsRef[index]['comission']}}
                            </div>
                          </div>
                         
                        </div>
                      </template>
                      <template v-else>
                          <div class="relative">
                            {{ value }}
                          </div>
                      </template>


                    </td>
                  </template>
                  <td v-if="actions.length">

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
import showLabel from '@/helpers/showLabel.js';
import { Link,router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import ActionButton from '@/Components/ActionButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Form from '@/Components/Form.vue';
import filter from '@/helpers/filter';
import publishMoney from '@/helpers/publishMoney';

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
const columnsToHide = ['id','category','description','comission'];


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

function getCategoryTotal(category) {
  const total = itemsRef.value
    .filter(item => item.category === category)
    .reduce((acc, item) => acc + item.total_comission, 0);

  return publishMoney(total);
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
<style>
  .hasToolTip{
    position:relative;
    cursor: pointer;
  }
  .hasToolTip:hover .toolTip{
    display: block;
  }
  .toolTip{
    display: none;
    position: absolute;
    max-width:200px;
    top: 0;
    left:10px;
    font-size: .8em;
    background: white;
    padding: 3px 5px;
    z-index: 10;


  }
</style>