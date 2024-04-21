<template>
    <div class="pt-2 pb-3 space-y-1">
        <template v-for="item in menu">
            <ResponsiveNavLink :href="route(item.slug)" :active="route().current(item.slug)">
                {{item.label}}
            </ResponsiveNavLink>
        </template>
        
    </div>
</template>
<script setup>
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
    import menu from '@/menu.js'
</script>