<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const stats = [
    { title: 'Usuários', value: '0', icon: 'mdi-account-multiple', color: 'primary' },
    { title: 'Permissões', value: '8', icon: 'mdi-shield-key', color: 'success' },
    { title: 'Grupos', value: '3', icon: 'mdi-account-group', color: 'warning' },
    { title: 'Empresas', value: '1', icon: 'mdi-domain', color: 'info' },
];

const getStatIcon = (icon) => {
    const icons = {
        'mdi-account-multiple': '👥',
        'mdi-shield-key': '🔐',
        'mdi-account-group': '👫',
        'mdi-domain': '🏢',
    };
    return icons[icon] || '📊';
};
</script>

<template>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ user?.name?.charAt(0) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">
                        Olá, {{ user?.name }}! 👋
                    </h2>
                    <p class="text-gray-400 mb-2">
                        Bem-vindo ao LGPDGo
                    </p>
                    <div class="flex gap-2">
                        <span v-if="user?.is_super_user" class="inline-flex items-center gap-1 bg-red-600 text-white px-3 py-1 rounded-full text-sm">
                            👑 Superusuário
                        </span>
                        <span v-if="company" class="inline-flex items-center gap-1 bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                            🏢 {{ company.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div v-for="stat in stats" :key="stat.title" class="bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-sm mb-2">
                            {{ stat.title }}
                        </p>
                        <h3 class="text-3xl font-bold text-white">
                            {{ stat.value }}
                        </h3>
                    </div>
                    <div class="text-4xl">
                        {{ getStatIcon(stat.icon) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">ℹ️</span>
                    <h3 class="text-xl font-bold text-white">Sobre o Sistema</h3>
                </div>
                <p class="text-gray-300 mb-4">
                    O LGPDGo é um sistema completo de gerenciamento de dados com foco em conformidade com a LGPD.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="text-green-500 text-xl mt-1">✓</span>
                        <div>
                            <p class="text-white font-semibold">Multi-tenancy</p>
                            <p class="text-gray-400 text-sm">Isolamento completo de dados por empresa</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500 text-xl mt-1">✓</span>
                        <div>
                            <p class="text-white font-semibold">Sistema de Permissões</p>
                            <p class="text-gray-400 text-sm">Controle granular de acessos e funcionalidades</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500 text-xl mt-1">✓</span>
                        <div>
                            <p class="text-white font-semibold">Grupos de Permissões</p>
                            <p class="text-gray-400 text-sm">Organize permissões em grupos para facilitar o gerenciamento</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500 text-xl mt-1">✓</span>
                        <div>
                            <p class="text-white font-semibold">Autenticação Segura</p>
                            <p class="text-gray-400 text-sm">Laravel Sanctum com Inertia.js e Vue 3</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">👤</span>
                    <h3 class="text-xl font-bold text-white">Suas Informações</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm">Nome</p>
                        <p class="text-white font-semibold">{{ user?.name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">E-mail</p>
                        <p class="text-white font-semibold break-all">{{ user?.email }}</p>
                    </div>
                    <div v-if="company">
                        <p class="text-gray-400 text-sm">Empresa</p>
                        <p class="text-white font-semibold">{{ company.name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Tipo de Usuário</p>
                        <p class="text-white font-semibold">
                            {{ user?.is_super_user ? 'Superusuário' : 'Usuário Regular' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
