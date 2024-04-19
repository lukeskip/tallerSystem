<template >
     <div class="flex justify-center mt-5" v-if="checkNumberPages(pagination)">
        <ul class="flex list-none rounded-md divide-x divide-gray-300">
            <li v-for="link in pagination" :key="link.id">
                <template v-if="link.url">
                    <Link :href="link.url" class="px-3 py-1 text-sm font-medium cursor-pointer focus:outline-none focus:ring focus:ring-indigo-200">
                        {{ showLabel(link.label) }}
                    </Link>
                </template>
            </li>
        </ul>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3'
import showLabel from '@/helpers/showLabel'
    defineProps({
        pagination:{
            type:Array,
            required:true
        }
    });

    const checkNumberPages = (pagination)=>{
        let activePages = pagination.filter((item)=>item.url);
        if(activePages.length > 1) {
            return true
        }else {
            return false
        };
    }
    
</script>