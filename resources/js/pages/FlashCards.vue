<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Trash2, Plus } from 'lucide-vue-next';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'FlashCards',
        href: '/flashcards',
    },
];

defineProps({
    flashcards: Object,
});
</script>

<template>
    <Head title="FlashCards" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <button @click="showCreateFlashcardModal = true" class="bg-gray-500 text-white p-2 rounded-sm">
                    <Plus class="w-4 h-4"/>
                </button>
            </div>
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="whitespace-no-wrap w-full">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 dark:bg-gray-700">
                            <th class="px-4 py-3">Título</th>
                            <th class="text-center px-4 py-3">Categoría</th>
                            <th class="text-center px-4 py-3">Subcategoría</th>
                            <th class="text-center px-4 py-3">Ver</th>
                            <th class="text-center px-4 py-3">Editar</th>
                            <th class="text-center px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white">
                        <template v-if="flashcards && flashcards.data.length">
                            <tr v-for="flashcard in flashcards.data" :key="flashcard.id" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                                <td class="px-4 py-3 text-sm">
                                    {{ flashcard.name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ flashcard.category.name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ flashcard.subcategory.name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    Ver
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    Editar
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    Eliminar
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="6" class="px-4 py-3 text-sm text-center">No hay tarjetas disponibles</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
