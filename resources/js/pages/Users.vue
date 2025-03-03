<script setup lang="js">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';
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
    
    useForm({
        name: userToEdit.value.name,
        is_admin: userToEdit.value.is_admin,
    }).put(route('users.update', userToEdit.value.id), {
        onSuccess: () => {
            userToEdit.value = null;
        }
    });
}

const cancelEdit = () => {
    userToEdit.value = null;
}
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <table class="whitespace-no-wrap w-full">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">
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
                    <tr v-for="user in users.data" :key="user.id" class="text-gray-700">
                        <td v-if="userToEdit && user.id === userToEdit.id" class="px-4 py-3 text-sm text-center">
                            <input v-model="userToEdit.name" class="w-full p-2 border border-gray-300 rounded-sm" type="text"/>
                        </td>
                        <td v-else class="px-4 py-3 text-sm text-center">
                            {{ user.name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            {{ user.email }}
                        </td>
                        <td v-if="userToEdit && user.id === userToEdit.id" class="px-4 py-3 text-sm text-center">
                            <select v-model="userToEdit.is_admin" class="w-full p-2 border border-gray-300 rounded-sm">
                                <option :value="false">No</option>
                                <option :value="true">Sí</option>
                            </select>
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
        <Modal :show="showDeleteUserModal" @close="showDeleteUserModal = false" >
            <div v-if="user" class="p-6">
                <span>¿Estas seguro que deseas eliminar al usuario <b>{{user.name}}</b>?</span>
                <div class="flex justify-end gap-4 mt-4">
                    <button @click="showDeleteUserModal = false" class="bg-gray-600 text-white p-2 rounded-sm">Cancelar</button>
                    <button @click="deleteUser(user.id)" class="bg-red-600 text-white p-2 rounded-sm">Eliminar</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>