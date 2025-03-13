<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Modal from '@/components/Modal.vue';
import { Trash2, Plus, Eye } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'FlashCards',
        href: '/flashcards',
    },
];

defineProps({
    flashcards: Object,
    categories: Object,
    subcategories: Object,
});

const flashcard = ref(null);
const flashcardToEdit = ref(null);
const newOption = ref(null);
const showCreateFlashcardModal = ref(false);
const showDeleteFlashcardModal = ref(false);
const showFlashcardModal = ref(false);
const showOptionsModal = ref(false);

const flashcardForm = useForm({
    title: '',
    category_id: null,
    subcategory_id: null,
    image: null,
});

const optionForm = useForm({
    title: '',
    option_number: null,
    flashcard_id: null,
});

const $page = usePage();

const createFlashcard = () => {
    flashcardForm.post(route('flashcards.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeCreateFlashcard();
            flashcardForm.reset();
        },
    });
}

const editFlashcard = (selectedFlashcard) => {
    flashcardToEdit.value = {...selectedFlashcard};
}

const manageOptions = (selectedFlashcard) => {
    showOptionsModal.value = true;
    flashcard.value = selectedFlashcard;
}

const addOption = () => {
    newOption.value = true;
}

const createOption = () => {
    optionForm.flashcard_id = flashcard.value.id;
    optionForm.post(route('options.store', flashcard.value.id), {
        preserveScroll: true,
        onSuccess: (page) => {
            let updatedFlashcard = null;
            
            if (page.props && page.props.flash) {
                updatedFlashcard = page.props.flash.flashcard || page.props.flash.updatedFlashcard;
            }
            
            if (!updatedFlashcard && page.props) {
                updatedFlashcard = page.props.flashcard;
            }
            
            if (updatedFlashcard) {
                flashcard.value = JSON.parse(JSON.stringify(updatedFlashcard));
            } else {
                const newOptionData = {
                    flashcard_id: flashcard.value.id,
                    option_number: optionForm.option_number,
                    title: optionForm.title,
                    id: Date.now()
                };
                
                if (!flashcard.value.options) {
                    flashcard.value.options = [];
                }
                
                flashcard.value = {
                    ...flashcard.value,
                    options: [...flashcard.value.options, newOptionData]
                };
            }
            
            newOption.value = null;
            optionForm.reset();
        },
        onError: (errors) => {
            console.error('Errores al crear la opción:', errors);
        }
    });
}

const saveFlashcard = () => {
    if(!flashcardToEdit.value) return;

    const form = useForm({
        title: flashcardToEdit.value.title,
        category_id: flashcardToEdit.value.category_id,
        subcategory_id: flashcardToEdit.value.subcategory_id,
        image: flashcardToEdit.value.image,
    });
    form.post(route('flashcards.update', flashcardToEdit.value.id), {
        _method: 'put',
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            flashcardToEdit.value = null;
        },
    });
}

const cancelEditFlashcard = () => {
    flashcardToEdit.value = null;
}

const closeCreateFlashcard = () => {
    showCreateFlashcardModal.value = false;
    flashcardForm.reset();
}

const deleteModal = (selectedFlashcard) => {
    showDeleteFlashcardModal.value = true;
    flashcard.value = selectedFlashcard;
}

const deleteFlashcard = (flashcardId) => {
    flashcardForm.delete(route('flashcards.destroy', flashcardId), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteFlashcardModal.value = false;
        },
    });
}

const deleteOption = (optionNumber) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta opción?')) {
        const form = useForm({});
        form.delete(route('options.destroy', { flashcard: flashcard.value.id, optionNumber: optionNumber }), {
            preserveScroll: true,
            onSuccess: (page) => {
                let updatedFlashcard = null;
                
                if (page.props && page.props.flash) {
                    updatedFlashcard = page.props.flash.flashcard || page.props.flash.updatedFlashcard;
                }
                
                if (!updatedFlashcard && page.props) {
                    updatedFlashcard = page.props.flashcard;
                }
                
                if (updatedFlashcard) {
                    flashcard.value = JSON.parse(JSON.stringify(updatedFlashcard));
                } else {
                    flashcard.value = {
                        ...flashcard.value,
                        options: flashcard.value.options.filter(opt => opt.option_number !== optionNumber)
                    };
                }
            }
        });
    }
}

const showFlashcard = (selectedFlashcard) => {
    flashcard.value = selectedFlashcard;
    showFlashcardModal.value = true;
}

const openImageInNewTab = (url) => {
    window.open(url, '_blank');
}
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
                            <th class="px-4 py-3 text-center">Título</th>
                            <th class="text-center px-4 py-3">Categoría</th>
                            <th class="text-center px-4 py-3">Subcategoría</th>
                            <th class="text-center px-4 py-3">Imagen</th>
                            <th class="text-center px-4 py-3">Ver</th>
                            <th class="text-center px-4 py-3">Editar</th>
                            <th class="text-center px-4 py-3">Opciones</th>
                            <th class="text-center px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white">
                        <template v-if="flashcards && flashcards.data.length">
                            <tr v-for="flashcard in flashcards.data" :key="flashcard.id" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">

                                <td v-if="flashcardToEdit && flashcardToEdit.id === flashcard.id" class="px-4 py-3 text-sm text-center">
                                    <input v-model="flashcardToEdit.title" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.title}" type="text"/>
                                    <div v-if="$page.props.errors?.title" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.title }}
                                    </div>
                                </td>
                                <td v-else class="px-4 py-3 text-sm text-center">
                                    {{ flashcard.title }}
                                </td>
                                
                                <td v-if="flashcardToEdit && flashcardToEdit.id === flashcard.id" class="px-4 py-3 text-sm text-center">
                                    <select v-model="flashcardToEdit.category_id" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.category_id}">
                                        <option value="null" disabled>Selecciona una categoría</option>
                                        <option v-for="category in categories.data" :key="category.id" :value="category.id">{{ category.name }}</option>
                                    </select>
                                    <div v-if="$page.props.errors?.category_id" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.category_id }}
                                    </div>
                                </td>
                                <td v-else class="px-4 py-3 text-sm text-center">
                                    {{ flashcard.category.name}}
                                </td>
                                
                                <td v-if="flashcardToEdit && flashcardToEdit.id === flashcard.id" class="px-4 py-3 text-sm text-center">
                                    <select v-model="flashcardToEdit.subcategory_id" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.subcategory_id}">
                                        <option value="null" disabled>Selecciona una subcategoría</option>
                                        <option v-for="subcategory in subcategories.data" :key="subcategory.id" :value="subcategory.id">{{ subcategory.name }}</option>
                                    </select>
                                    <div v-if="$page.props.errors?.subcategory_id" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.subcategory_id }}
                                    </div>
                                </td>
                                <td v-else class="px-4 py-3 text-sm text-center">
                                    {{ flashcard.subcategory.name }}
                                </td>
                                
                                <td v-if="flashcardToEdit && flashcardToEdit.id === flashcard.id" class="px-4 py-3 text-sm text-center">
                                    <input type="file" @input="flashcardToEdit.image = $event.target.files[0]" accept=".jpg, .jpeg, .png, .webp" class="w-full p-2
                                    border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.image}">
                                    <div v-if="$page.props.errors?.image" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.image }}
                                    </div>
                                    <small class="text-gray-500 dark:text-gray-400 block mt-1">Selecciona una imagen nueva o deja en blanco para mantener la actual</small>
                                </td>
                                <td v-else class="px-4 py-3 text-sm text-center">
                                    <div v-if="flashcard.url">
                                        <img @click="openImageInNewTab(flashcard.url)" :src="flashcard.url" alt="Imagen" class="w-16 h-16 object-cover rounded-sm cursor-pointer">
                                    </div>
                                    <div v-else>
                                        No hay imagen
                                    </div>
                                </td>
                                
                                <td class="px-4 py-3 text-sm text-center">
                                    <button @click="showFlashcard(flashcard)" class="bg-blue-600 text-white p-2 rounded-sm">
                                        <Eye class="w-4 h-4"/>
                                    </button>
                                </td>
                                
                                <td class="px-4 py-3 text-sm text-center">
                                    <template v-if="flashcardToEdit && flashcardToEdit.id === flashcard.id">
                                        <button @click="saveFlashcard" class="bg-green-600 text-white p-2 rounded-sm mr-2">Guardar</button>
                                        <button @click="cancelEditFlashcard" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                                    </template>
                                    <template v-else>
                                        <span @click="editFlashcard(flashcard)" class="text-green-600 hover:underline cursor-pointer">Editar</span>
                                    </template>
                                </td>
                                
                                <td class="px-4 py-3 text-sm text-center">
                                    <span @click="manageOptions(flashcard)" class="text-blue-600 hover:underline cursor-pointer">Gestionar Opciones</span>
                                </td>
                                
                                <td class="px-4 py-3 text-sm text-center">
                                    <button @click="deleteModal(flashcard)" class="bg-red-600 text-white p-2 rounded-sm">
                                        <Trash2 class="w-4 h-4"/>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr v-else class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td colspan="8" class="px-4 py-3 text-sm text-center">No hay tarjetas disponibles</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <Modal :show="showCreateFlashcardModal" @close="showCreateFlashcardModal = false">
            <div class="p-6">
                <form @submit.prevent="createFlashcard">
                    <label for="title" class="block text-md font-medium text-gray-700 dark:text-gray-200">Título</label>
                    <input v-model="flashcardForm.title" type="text" id="title" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': flashcardForm.errors.title}" placeholder="Título" required>
                    <div v-if="flashcardForm.errors.title" class="text-red-500 text-sm mt-1">
                        {{ flashcardForm.errors.title }}
                    </div>
                    
                    <label for="category_id" class="block text-md font-medium text-gray-700 dark:text-gray-200">Categoría</label>
                    <select v-model="flashcardForm.category_id" id="category_id" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': flashcardForm.errors.category_id}">
                        <option value="null" disabled>Selecciona una categoría</option>
                        <option v-for="category in categories.data" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                    <div v-if="flashcardForm.errors.category_id" class="text-red-500 text-sm mt-1">
                        {{ flashcardForm.errors.category_id }}
                    </div>
                    
                    <label for="subcategory_id" class="block text-md font-medium text-gray-700 dark:text-gray-200">Subcategoría</label>
                    <select v-model="flashcardForm.subcategory_id" id="subcategory_id" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': flashcardForm.errors.subcategory_id}">
                        <option value="null" disabled>Selecciona una Subcategoría</option>
                        <option v-for="subcategory in subcategories.data" :key="subcategory.id" :value="subcategory.id">{{ subcategory.name }}</option>
                    </select>
                    <div v-if="flashcardForm.errors.subcategory_id" class="text-red-500 text-sm mt-1">
                        {{ flashcardForm.errors.subcategory_id }}
                    </div>
                    
                    <label for="image" class="block text-md font-medium text-gray-700 dark:text-gray-200">Imagen</label>
                    <input type="file" @input="flashcardForm.image = $event.target.files[0]" accept=".jpg, .jpeg, .png, .webp" id="image" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': flashcardForm.errors.image}" required>
                    <div v-if="flashcardForm.errors.image" class="text-red-500 text-sm mt-1">
                        {{ flashcardForm.errors.image }}
                    </div>
                    
                    <div class="flex justify-end gap-4 mt-4">
                        <button type="button" @click="closeCreateFlashcard" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                        <button type="submit" class="bg-green-600 text-white p-2 rounded-sm">Guardar</button>
                    </div>
                </form>
            </div>
        </Modal>
        
        <Modal :show="showDeleteFlashcardModal" @close="showDeleteFlashcardModal = false">
            <div v-if="flashcard" class="p-6">
                <span class="dark:text-gray-200">¿Estas seguro que deseas eliminar la tarjeta <b>{{flashcard.title}}</b>?</span>
                <div class="flex justify-end gap-4 mt-4">
                    <button @click="showDeleteFlashcardModal = false" class="bg-gray-600 dark:bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                    <button @click="deleteFlashcard(flashcard.id)" class="bg-red-600 text-white p-2 rounded-sm">
                        Eliminar
                    </button>
                </div>
            </div>
        </Modal>

        <Modal :show="showOptionsModal" @close="showOptionsModal = false">
            <div v-if="flashcard" class="p-6">
                <span class="dark:text-gray-200 text-xl">Opciones de la flashcard {{ flashcard.title }}</span>
                <div class="flex items-center justify-between mt-4">
                    <button @click="addOption" class="bg-gray-500 text-white p-2 rounded-sm">Agregar Opción</button>
                </div>
                <table class="whitespace-no-wrap w-full mt-4">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 dark:bg-gray-700">
                            <th class="px-4 py-3 text-center">Nº Opción</th>
                            <th class="px-4 py-3 text-center">Título</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white">
                        <tr v-if="newOption" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td class="px-4 py-3 text-center">
                                <input v-model="optionForm.option_number" type="number" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.option_number}" placeholder="Número de opción" required>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input v-model="optionForm.title" type="text" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.title}" placeholder="Título" required>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button @click="createOption" class="bg-green-600 text-white p-2 rounded-sm"><Plus class="w-4 h-4"/></button>
                            </td>
                        </tr>
                        <template v-if="flashcard.options && flashcard.options.length > 0">
                            <tr v-for="option in flashcard.options" :key="option.option_number" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                                <td class="px-4 py-3 text-center">{{ option.option_number }}</td>
                                <td class="px-4 py-3 text-center">{{ option.title }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="deleteOption(option.option_number)" class="bg-red-600 text-white p-1 rounded-sm"><Trash2 class="w-4 h-4"/></button>
                                </td>
                            </tr>
                        </template>
                        <tr v-if="!newOption && (!flashcard.options || flashcard.options.length === 0)" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td colspan="3" class="px-4 py-3 text-center">No hay opciones disponibles</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Modal>
        <Modal :show="showFlashcardModal" @close="showFlashcardModal = false" maxWidth="sm">
            <div v-if="flashcard">
                <div class="w-full flex-col items-center justify-center">
                    <div>
                        <div v-if="flashcard.url">
                            <img @click="openImageInNewTab(flashcard.url)" :src="flashcard.url" alt="Imagen" class="w-full h-64 object-cover cursor-pointer">
                        </div>
                        <div v-else>
                            No hay imagen
                        </div>
                    </div>
                    <div class="mt-4 ml-6">
                        <span class="dark:text-gray-200 text-2xl font-bold">{{ flashcard.title }}</span>
                    </div>
                    <div class="border-b my-2 mx-4"></div>
                    <div class="mt-2 ml-6">
                        <span class="dark:text-gray-400 font-bold">Categoría: </span>
                        <span>{{ flashcard.category.name }}</span>
                    </div>
                    <div class="ml-6">
                        <span class="dark:text-gray-400 font-bold">Subcategoría: </span>
                        <span>{{ flashcard.subcategory.name }}</span>
                    </div>
                    <div class="ml-6">
                        <span class="dark:text-gray-400 font-bold">Nº Opciones: </span>
                        <span>{{ flashcard.options.length }}</span>
                    </div>
                    <div class="flex justify-end gap-4 mt-4 mr-6 mb-2">
                        <button @click="showFlashcardModal = false" class="bg-gray-600 text-white p-2 rounded-sm mt-4">Cerrar</button>
                    </div>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
