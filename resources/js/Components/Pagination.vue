<template >
    
     <div class="flex justify-center" v-if="checkNumberPages(pagination)">
        <ul class="flex space-x-2">
            <li v-for="link in pagination" :key="link.id">
                <template v-if="link.url">
                    <Link :href="link.url" class="px-2 py-1">
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
        let activePages = pagination.filter((item)=>item.active);
        if(activePages > 1) return true;
        else return false;
    }
    
</script>