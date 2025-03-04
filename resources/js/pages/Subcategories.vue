<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';
import { Trash2 } from 'lucide-vue-next';

const breadcrumbs = [
    {
        title: 'Subcategorías',
        href: '/subcategories',
    },
];

defineProps({
    subcategories: Object,
});

const subcategory = ref(null);
const subcategoryToEdit = ref(null);
const showDeleteSubcategoryModal = ref(false);

const editSubcategory = (selectedSubcategory) => {
    subcategoryToEdit.value = {...selectedSubcategory};
}

const cancelEdit = () => {
    subcategoryToEdit.value = null;
}

const deleteModal = (selectedSubcategory) => {
    showDeleteSubcategoryModal.value = true;
    subcategory.value = selectedSubcategory;
}

const saveSubcategory = () => {
    if(!subcategoryToEdit.value) return;

    useForm({
        name: subcategoryToEdit.value.name,
    }).put(route('subcategories.update', subcategoryToEdit.value.id), {
        onSuccess: () => {
            subcategoryToEdit.value = null;
        }
    })
}

const deleteSubcategory = (id) => {
    useForm().delete(route('subcategories.destroy', id), {
        onSuccess: () => {
            showDeleteSubcategoryModal.value = false;
            subcategory.value = null;
        }
    })
}
</script>

<template>
    <Head title="Subcategories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="whitespace-no-wrap w-full">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 dark:bg-gray-700">
                            <th class="px-4 py-3">Name</th>
                            <th class="text-center px-4 py-3">Editar</th>
                            <th class="text-center px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white">
                        <tr v-for="subcategory in subcategories.data" :key="subcategory.id" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td class="px-4 py-3 text-sm">
                                <template v-if="subcategoryToEdit && subcategoryToEdit.id === subcategory.id">
                                    <input v-model="subcategoryToEdit.name" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" />
                                </template>
                                <template v-else>
                                    {{ subcategory.name }}
                                </template>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <template v-if="subcategoryToEdit && subcategoryToEdit.id === subcategory.id">
                                    <button @click="saveSubcategory" class="bg-green-600 text-white p-2 rounded-sm mr-2">Guardar</button>
                                    <button @click="cancelEdit" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                                </template>
                                <template v-else>
                                    <span @click="editSubcategory(subcategory)" class="text-green-600 hover:underline cursor-pointer">Editar</span>
                                </template>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button @click="deleteModal(subcategory)" class="bg-red-600 text-white p-2 rounded-sm">
                                    <Trash2 />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="showDeleteSubcategoryModal" @close="showDeleteSubcategoryModal = false">
            <div v-if="subcategory" class="p-6">
                <span class="dark:text-gray-200">¿Estas seguro que deseas eliminar la subcategoría <b>{{subcategory.name}}</b>?</span>
                <div class="flex justify-end gap-4 mt-4">
                    <button @click="showDeleteSubcategoryModal = false" class="bg-gray-600 dark:bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                    <button @click="deleteSubcategory(subcategory.id); showDeleteSubcategoryModal = false" class="bg-red-600 text-white p-2 rounded-sm">Eliminar</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
