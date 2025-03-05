<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Trash2, Plus  } from 'lucide-vue-next';
import Modal from '@/components/Modal.vue';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'Usuarios',
        href: '/users',
    },
];

defineProps({
    name: String,
    users: Object
});
    
const user = ref(null);
const userToEdit = ref(null);
const showDeleteUserModal = ref(false);
const showCreateUserModal = ref(false);

const userForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    is_admin: false,
});

const deleteModal = (selectedUser) => {  
    showDeleteUserModal.value = true;
    user.value = selectedUser;
}

const deleteUser = () => {
    useForm().delete(route('users.destroy', user.value.id), {
        onSuccess: () => {
            showDeleteUserModal.value = false;
            user.value = null;
        }
    });
}

const editUser = (selectedUser) => {
    userToEdit.value = {...selectedUser};
}

const saveUser = () => {
    if (!userToEdit.value) return;
    
    const form = useForm({
        name: userToEdit.value.name,
        is_admin: userToEdit.value.is_admin,
    });
    
    form.put(route('users.update', userToEdit.value.id), {
        onSuccess: () => {
            userToEdit.value = null;
        },
        preserveScroll: true
    });
}

const createUser = () => {
    userForm.post(route('users.store'), {
        onSuccess: () => {
            showCreateUserModal.value = false;
            userForm.reset();
        }
    });
}

const cancelEdit = () => {
    userToEdit.value = null;
}

const closeCreateModal = () => {
  showCreateUserModal.value = false;
  userForm.reset();
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <button @click="showCreateUserModal = true" class="bg-gray-500 text-white p-2 rounded-sm">
                    <Plus class="w-4 h-4"/>
                </button>
            </div>
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="whitespace-no-wrap w-full">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 dark:bg-gray-700">
                            <th class="text-center px-4 py-3">Nombre</th>
                            <th class="text-center px-4 py-3">Email</th>
                            <th class="text-center px-4 py-3">Administrador</th>
                            <th class="text-center px-4 py-3">Mejor Puntuación</th>
                            <th class="text-center px-4 py-3">Fecha de Creación</th>
                            <th class="text-center px-4 py-3">Detalle</th>
                            <th class="text-center px-4 py-3">Editar</th>
                            <th class="text-center px-4 py-3">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white">
                        <tr v-for="user in users.data" :key="user.id" class="text-gray-700 dark:text-gray-200 dark:bg-gray-800">
                            <td v-if="userToEdit && user.id === userToEdit.id" class="px-4 py-3 text-sm text-center">
                                <input v-model="userToEdit.name" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.name}" type="text"/>
                                <div v-if="$page.props.errors?.name" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.name }}
                                </div>
                            </td>
                            <td v-else class="px-4 py-3 text-sm text-center">
                                {{ user.name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ user.email }}
                            </td>
                            <td v-if="userToEdit && user.id === userToEdit.id" class="px-4 py-3 text-sm text-center">
                                <select v-model="userToEdit.is_admin" class="w-full p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700" :class="{'border-red-500': $page.props.errors?.is_admin}">
                                    <option :value="false">No</option>
                                    <option :value="true">Sí</option>
                                </select>
                                <div v-if="$page.props.errors?.is_admin" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.is_admin }}
                                </div>
                            </td>
                            <td v-else class="px-4 py-3 text-sm text-center">
                                {{ user.is_admin ? 'Sí' : 'No' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ user.is_admin ? '-' : user.best_score }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ user.created_at.split('T')[0] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a :href="`/users/${user.id}`" class="text-blue-600 hover:underline">Ver detalles</a>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <template v-if="userToEdit && user.id === userToEdit.id">
                                    <button @click="saveUser" class="bg-green-600 text-white p-2 rounded-sm mr-2">Guardar</button>
                                    <button @click="cancelEdit" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                                </template>
                                <template v-else>
                                    <span @click="editUser(user)" class="text-green-600 hover:underline cursor-pointer">Editar</span>
                                </template>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <button @click="deleteModal(user)" class="bg-red-600 text-white p-2 rounded-sm">
                                    <Trash2 class="w-4 h-4"/>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="showDeleteUserModal" @close="showDeleteUserModal = false" >
            <div v-if="user" class="p-6">
                <span class="dark:text-gray-200">¿Estas seguro que deseas eliminar al usuario <b>{{user.name}}</b>?</span>
                <div class="flex justify-end gap-4 mt-4">
                    <button @click="showDeleteUserModal = false" class="bg-gray-600 dark:bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                    <button @click="deleteUser(user.id)" class="bg-red-600 text-white p-2 rounded-sm">Eliminar</button>
                </div>
            </div>
        </Modal>
        <Modal :show="showCreateUserModal" @close="showCreateUserModal = false" >
            <div class="p-6">
                <form @submit.prevent="createUser">
                    <label for="name" class="block text-md font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                    <input v-model="userForm.name" type="text" id="name" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': userForm.errors.name}" placeholder="Nombre" required>
                    <div v-if="userForm.errors.name" class="text-red-500 text-sm mt-1">
                        {{ userForm.errors.name }}
                    </div>
                    
                    <label for="email" class="block text-md font-medium text-gray-700 dark:text-gray-200 mt-3">Email</label>
                    <input v-model="userForm.email" type="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': userForm.errors.email}" placeholder="Email" required>
                    <div v-if="userForm.errors.email" class="text-red-500 text-sm mt-1">
                        {{ userForm.errors.email }}
                    </div>
                    
                    <label for="password" class="block text-md font-medium text-gray-700 dark:text-gray-200 mt-3">Contraseña</label>
                    <input v-model="userForm.password" type="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': userForm.errors.password}" placeholder="Contraseña" required>
                    <div v-if="userForm.errors.password" class="text-red-500 text-sm mt-1">
                        {{ userForm.errors.password }}
                    </div>
                    
                    <label for="password_confirmation" class="block text-md font-medium text-gray-700 dark:text-gray-200 mt-3">Confirmar Contraseña</label>
                    <input v-model="userForm.password_confirmation" type="password" id="password_confirmation" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" placeholder="Confirmar Contraseña" required>
                    
                    <label for="is_admin" class="block text-md font-medium text-gray-700 dark:text-gray-200 mt-3">Administrador</label>
                    <select v-model="userForm.is_admin" id="is_admin" class="mt-1 p-2 border border-gray-300 rounded-sm dark:text-gray-200 dark:bg-gray-700 w-full" :class="{'border-red-500': userForm.errors.is_admin}">
                        <option :value="false">No</option>
                        <option :value="true">Sí</option>
                    </select>
                    <div v-if="userForm.errors.is_admin" class="text-red-500 text-sm mt-1">
                        {{ userForm.errors.is_admin }}
                    </div>
                    
                    <div class="flex justify-end gap-4 mt-4">
                        <button type="button" @click="closeCreateModal" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                        <button type="submit" class="bg-green-600 text-white p-2 rounded-sm">Guardar</button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>