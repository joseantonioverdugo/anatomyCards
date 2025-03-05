<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Trash2, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';

const breadcrumbs = [
    {
        title: 'Categorías',
        href: '/categories',
    },
];

defineProps({
    categories: Object,
});
 
const category = ref(null);
const categoryToEdit = ref(null);
const showDeleteCategoryModal = ref(false);
const showCreateCategoryModal = ref(false);

const categoryForm = useForm({
    name: '',
})

const editCategory = (selectedCategorie) => {
    categoryToEdit.value = {...selectedCategorie};
}

const deleteModal = (selectedCategorie) => {
    showDeleteCategoryModal.value = true;
    category.value = selectedCategorie;
}

const cancelEdit = () => {
    categoryToEdit.value = null;
}

const saveCategory = () => {
    if(!categoryToEdit.value) return; 

    useForm({
        name: categoryToEdit.value.name,
    }).put(route('categories.update', categoryToEdit.value.id), {
        onSuccess: () => {
            categoryToEdit.value = null;
        }
    })
}

const createCategory = () => {
    useForm({
        name: categoryForm.name,
    }).post(route('categories.store'), {
        onSuccess: () => {
            showCreateCategoryModal.value = false;
            categoryForm.name = '';
        }
    });
}

const deleteCategory = (id) => {
    useForm().delete(route('categories.destroy', id), {
        onSuccess: () => {
            showDeleteCategoryModal.value = false;
            category.value = null;
        }
    });
}

const closeCreateModal = () => {
    showCreateCategoryModal.value = false;
    categoryForm.reset();
}
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <button @click="showCreateCategoryModal = true" class="bg-gray-500 text-white p-2 rounded-sm">
                    <Plus class="w-4 h-4"/>
                </button>
            </div>
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
                        <tr v-for="category in categories.data" :key="category.id" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td v-if="categoryToEdit && category.id === categoryToEdit.id" class="px-4 py-3 text-sm text-center">
                                <input v-model="categoryToEdit.name" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" type="text"/>
                            </td>
                            <td v-else class="px-4 py-3 text-sm">
                                {{ category.name }}
                            </td>
                            <td v-if="categoryToEdit && category.id === categoryToEdit.id" class="px-4 py-3 text-sm text-center">
                                <button @click="saveCategory" class="bg-green-600 text-white p-2 rounded-sm mr-2">Guardar</button>
                                <button @click="cancelEdit" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                            </td>
                            <td v-else class="px-4 py-3 text-sm text-center">
                                <span @click="editCategory(category)" class="text-green-600 hover:underline cursor-pointer">Editar</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button @click="deleteModal(category)" class="bg-red-600 text-white p-2 rounded-sm">
                                    <Trash2 class="w-4 h-4"/>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="showDeleteCategoryModal" @close="showDeleteCategoryModal = false">
            <div v-if="category" class="p-6">
                <span class="dark:text-gray-200">¿Estas seguro que deseas eliminar la categoría <b>{{category.name}}</b>?</span>
                <div class="flex justify-end gap-4 mt-4">
                    <button @click="showDeleteCategoryModal = false" class="bg-gray-600 dark:bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                    <button @click="deleteCategory(category.id)" class="bg-red-600 text-white p-2 rounded-sm">Eliminar</button>
                </div>
            </div>
        </Modal>
        <Modal :show="showCreateCategoryModal" @close="showCreateCategoryModal = false">
            <div class="p-6">
                <form @submit.prevent="createCategory">
                    <div class="mb-4">
                        <label for="name" class="block text-md font-medium text-gray-700 dark:text-gray-200">Nombre de la Categoría</label>
                        <input v-model="categoryForm.name" type="text" id="name" name="name" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" placeholder="Categoría" required>
                    </div>
                    <div class="flex justify-end gap-4 mt-4">
                        <button @click="closeCreateModal" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                        <button type="submit" class="bg-green-600 text-white p-2 rounded-sm">Guardar</button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>
