<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const drawer = ref(true);
const rail = ref(false);

const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const menuItems = [
    { title: 'Dashboard', icon: 'mdi-view-dashboard', route: 'dashboard' },
    // { title: 'Usuários', icon: 'mdi-account-multiple', route: 'users.index', permission: 'view-users' },
    // { title: 'Empresas', icon: 'mdi-domain', route: 'companies.index', permission: 'view-companies' },
    // { title: 'Permissões', icon: 'mdi-shield-key', route: 'permissions.index', permission: 'view-permissions' },
    // { title: 'Grupos', icon: 'mdi-account-group', route: 'permission-groups.index', permission: 'view-permission-groups' },
];

const logout = () => {
    router.post(route('logout'));
};

const getMenuIcon = (icon) => {
    const icons = {
        'mdi-view-dashboard': '📊',
        'mdi-account-multiple': '👥',
        'mdi-domain': '🏢',
        'mdi-shield-key': '🔐',
        'mdi-account-group': '👫',
    };
    return icons[icon] || '📋';
};
</script>

<template>
    <div class="flex h-screen bg-gray-900 text-white">
        <Head title="LGPDGo" />
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🛡️</span>
                    <h1 class="text-xl font-bold">LGPDGo</h1>
                </div>
            </div>

            <!-- Company Info -->
            <div v-if="company" class="px-6 py-4 border-b border-gray-700">
                <p class="text-xs text-gray-400 mb-1">Empresa</p>
                <p class="text-sm font-semibold text-white truncate">{{ company.name }}</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a
                    v-for="item in menuItems"
                    :key="item.title"
                    :href="item.route ? route(item.route) : '#'"
                    :class="{
                        'bg-blue-600 text-white': item.route && route().current(item.route),
                        'text-gray-300 hover:bg-gray-700': !(item.route && route().current(item.route)),
                    }"
                    class="block px-4 py-2 rounded transition-colors"
                >
                    <span class="text-lg mr-2">{{ getMenuIcon(item.icon) }}</span>
                    <span>{{ item.title }}</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-gray-700 space-y-3">
                <button
                    @click="logout"
                    class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded transition-colors text-sm font-semibold"
                >
                    Sair
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-gray-800 shadow border-b border-gray-700 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">LGPDGo</h2>
                
                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ user?.name }}</p>
                        <p class="text-xs text-gray-400">{{ user?.email }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">
                        {{ user?.name?.charAt(0) }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
