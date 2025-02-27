<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs = [
    {
        title: 'Categories',
        href: '/categories',
    },
];

defineProps({
    categories: Object,
});
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <table class="whitespace-no-wrap w-full">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Detalle</th>
                        <th class="px-4 py-3">Editar</th>
                        <th class="px-4 py-3">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white">
                    <tr v-for="categorie in categories.data" :key="categorie.id" class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            {{ categorie.name }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Detalle
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Editar
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Eliminar
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
