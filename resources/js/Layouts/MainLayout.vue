<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import Menu from '@/Components/Menu.vue'
</script> 
<template>
    <div class="bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="text-white text-lg font-bold"><Link :href="route('proyectos.index')">Taller 1100</Link></div>
            <Menu />
            
        </div>
    </div>
    </div>
    <div class="container mx-auto px-4 max-w-screen-xl">
        
        <header class="py-4 mb-4 grid grid-cols-2 gap-4">
            <div class="text-left">
                <h1 class="text-xl font-bold"><slot name="title" /></h1>
                <h2><slot name="subtitle" /></h2>
                <h3><slot name="subtitle2" /></h3>
            </div>
            <div>
                <div class="flex space-x-1 justify-end"><slot name="submenu"/></div>
            </div>

        </header>
        <div class="mainContainer"><slot name="main" /></div>
    </div>
    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; 2024 Cheko García. Todos los derechos reservados.</p>
        </div>
    </footer>
</template>