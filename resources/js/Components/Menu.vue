<template>
    <!-- Logo -->
    <div class="shrink-0 flex items-center">
        <Link :href="route('dashboard')">
            <ApplicationLogo
                class="block h-9 w-auto fill-current text-main-color"
            />
        </Link>
    </div>
    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <template v-for="item in menu">
            <NavLink :href="route(item.slug)" :active="route().current(item.slug)">
                {{item.label}}
            </NavLink>
        </template>
    </div>
</template>
<script setup>
    import NavLink from '@/Components/NavLink.vue';
    import menu from '@/menu.js'
    import { Link } from '@inertiajs/vue3';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    
</script>