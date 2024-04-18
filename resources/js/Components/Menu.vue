<template>
    <!-- Menú de navegación -->
    <nav class="space-x-4">
            <Link  :href="route('proyectos.index')" class="text-white hover:text-gray-300">
                <i class="fa-solid fa-folder"></i> Projectos
            </Link>
            <Link  :href="route('clientes.index')" class="text-white hover:text-gray-300">
                <i class="fa-solid fa-person"></i> Clientes
            </Link>
            <Link  :href="route('cotizaciones.index')" class="text-white hover:text-gray-300">
                <i class="fa-solid fa-file-invoice"></i> Cotizaciones
            </Link>
            <Link :href="route('proveedores.index')" class="text-white hover:text-gray-300">
                <i class="fa-solid fa-toolbox"></i> Proveedores
            </Link>
            
        </nav>
</template>
<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    
</script>