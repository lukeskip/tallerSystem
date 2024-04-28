<template>
    <Head title="Dashboarda" />

    <AuthenticatedLayout>
        <template #title>
            Dashboard
        </template>
        

        <template #main>
            <div class="flex flex-wrap">
                <!-- Incomes -->
                <div class="w-full md:w-1/3 p-4">
                    <div class="bg-main-color shadow-md rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">
                            Ingresos
                            <i class="fa-solid fa-arrow-right"></i>
                        </h2>
                        <div class="text-3xl text-white">{{ incomesTotal }}</div>
                    </div>
                </div>
                
                <!-- Outcomes -->
                <div class="w-full md:w-1/3 p-4">
                    <div class="bg-main-color shadow-md rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">
                            Egresos
                            <i class="fa-solid fa-arrow-left"></i>
                        </h2>
                        <div class="text-3xl text-white">{{ outcomesTotal }}</div>
                    </div>
                </div>
                
                <!-- Balance -->
                <div class="w-full md:w-1/3 p-4">
                    <div class="bg-main-color shadow-md rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-white mb-4">
                            Balance
                            <i class="fa-solid fa-scale-balanced"></i>
                        </h2>
                        <div class="text-3xl text-white">{{balance}}</div>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <h3 class="text-lg font-semibold">Últimos Ingresos</h3>
                <TableComponent :items="incomes" />
            </div>
            <div class="mt-10">
                <h3 class="text-lg font-semibold">Últimos Egresos</h3>
                <TableComponent :items="outcomes" />
            </div>

        </template>
 
    </AuthenticatedLayout>
</template>
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import TableComponent from '@/Components/TableComponent.vue';

    const props = defineProps({
        incomes:{
            type:Object,
            required:true,
        },
        incomesTotal:{
            type:Number,
            required:true,
        },
        outcomes:{
            type:Object,
            required:true,
        },
        outcomesTotal:{
            type:Number,
            required:true,
        },
        balance:{
            type:Number,
            required:true,
        },
    })
</script>
