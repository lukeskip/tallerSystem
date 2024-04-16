<script setup>
    import { Head, Link } from '@inertiajs/vue3';
</script> 
<template>
    <div class="bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <div class="text-white text-lg font-bold"><Link :href="route('proyectos.index')">Taller 1100</Link></div>
        
        <!-- Menú de navegación -->
        <nav class="space-x-4">
            <Link  :href="route('proyectos.index')" class="text-white hover:text-gray-300">
                Projectos
            </Link>
            <Link  :href="route('clientes.index')" class="text-white hover:text-gray-300">
                Clientes
            </Link>
            <Link  :href="route('cotizaciones.index')" class="text-white hover:text-gray-300">
                Cotizaciones
            </Link>
            <a href="#" class="text-white hover:text-gray-300">Proveedores</a>
        </nav>
        </div>
    </div>
    </div>
    <div class="container mx-auto px-4 max-w-screen-xl">
        
        <header class="py-4 mb-4">
            <slot name="header" />

        </header>
        <slot name="main" />
    </div>
</template>