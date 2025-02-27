<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    created_at: string;
    updated_at: string;
}

interface Pagination {
    data: User[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
    links: any[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

const props = defineProps<{
    name?: string;
    users: Pagination;
}>();

onMounted(() => {
    console.log('Users', props.users);
});
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <table class="whitespace-no-wrap w-full">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Administrador</th>
                        <th class="px-4 py-3">Detalle</th>
                        <th class="px-4 py-3">Editar</th>
                        <th class="px-4 py-3">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white">
                    <tr v-for="user in users.data" :key="user.id" class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            {{ user.name }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ user.email }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ user.is_admin ? 'Sí' : 'No' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a :href="`/users/${user.id}`" class="text-blue-600 hover:underline">Ver detalles</a>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a :href="`/users/${user.id}/edit`" class="text-green-600 hover:underline">Editar</a>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <button class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
