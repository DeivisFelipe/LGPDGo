<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const sidebarOpen = ref(true);

const menuSections = [
    {
        title: 'Principal',
        items: [
            { title: 'Dashboard', icon: 'chart-line', route: 'dashboard', permission: null },
        ]
    },
    {
        title: 'LGPD Core',
        items: [
            { title: 'Inventário de Dados (ROPA)', icon: 'database', route: 'data-inventories.index', permission: null },
            { title: 'Solicitações (DSAR)', icon: 'file-text', route: 'requests.index', permission: 'view-requests', badge: 'dsar' },
            { title: 'Matriz de Riscos', icon: 'alert-triangle', route: 'risks.index', permission: 'view-risks' },
            { title: 'Titulares de Dados', icon: 'users', route: 'data-subjects.index', permission: 'view-data-inventory' },
        ]
    },
    {
        title: 'Gestão',
        items: [
            { title: 'Departamentos', icon: 'building', route: 'departments.index', permission: null },
            { title: 'Treinamentos', icon: 'graduation-cap', route: 'trainings.index', permission: 'view-trainings' },
            { title: 'Cookies', icon: 'cookie', route: 'cookies.index', permission: null },
        ]
    },
    {
        title: 'Documentos',
        items: [
            { title: 'Documentos LGPD', icon: 'file-check', route: 'documents.index', permission: null },
            { title: 'Selo de Conformidade', icon: 'award', route: 'compliance-badge.index', permission: null },
        ]
    },
    {
        title: 'Sistema',
        items: [
            { title: 'Usuários', icon: 'user', route: 'users.index', permission: 'view-users' },
            { title: 'Empresas', icon: 'briefcase', route: 'companies.index', permission: 'view-companies' },
            { title: 'Configurações', icon: 'settings', route: 'settings.index', permission: null },
        ]
    }
];

const icons = {
    'chart-line': `<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>`,
    'database': `<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/>`,
    'file-text': `<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/>`,
    'alert-triangle': `<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>`,
    'users': `<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>`,
    'building': `<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/>`,
    'graduation-cap': `<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>`,
    'cookie': `<circle cx="12" cy="12" r="10"/><circle cx="10" cy="10" r="1"/><circle cx="15" cy="10" r="1"/><circle cx="12" cy="15" r="1"/><circle cx="8" cy="14" r="1"/><circle cx="16" cy="14" r="1"/>`,
    'file-check': `<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/>`,
    'award': `<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>`,
    'user': `<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>`,
    'briefcase': `<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>`,
    'settings': `<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>`
};

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const hasPermission = (permission) => {
    if (!permission || user.value?.is_super_user) return true;
    return user.value?.permissions?.includes(permission);
};

const getBadgeCount = (type) => {
    const badges = page.props.badges || {};
    return badges[type] || 0;
};
</script>

<template>
    <aside 
        :class="[
            'bg-white border-r border-slate-200 flex flex-col transition-all duration-300 z-50',
            sidebarOpen ? 'w-72' : 'w-20'
        ]"
    >
        <!-- Header da Sidebar -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
            <Link 
                v-if="sidebarOpen"
                :href="route('dashboard')" 
                class="flex items-center gap-3 group"
            >
                <div class="p-2 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl shadow-lg shadow-indigo-200 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900">
                        LGPD<span class="text-indigo-600">Go</span>
                    </h1>
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Proteção Ativa</p>
                    </div>
                </div>
            </Link>
            
            <button 
                @click="toggleSidebar"
                class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
                :class="!sidebarOpen && 'mx-auto'"
            >
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2" 
                        :d="sidebarOpen ? 'M11 19l-7-7 7-7m8 14l-7-7 7-7' : 'M13 5l7 7-7 7M5 5l7 7-7 7'"
                    />
                </svg>
            </button>
        </div>

        <!-- Navegação com Scroll -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-6">
            <div v-for="section in menuSections" :key="section.title">
                <p 
                    v-if="sidebarOpen"
                    class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3"
                >
                    {{ section.title }}
                </p>
                <div v-else class="w-full h-px bg-slate-200 my-2"></div>
                
                <div class="space-y-1">
                    <Link
                        v-for="item in section.items.filter(i => hasPermission(i.permission))"
                        :key="item.title"
                        :href="item.route ? route(item.route) : '#'"
                        :class="[
                            item.route && route().current(item.route + '*')
                            ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' 
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                            !sidebarOpen && 'justify-center'
                        ]"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative"
                        :title="!sidebarOpen ? item.title : ''"
                    >
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 24 24" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="2" 
                            stroke-linecap="round" 
                            stroke-linejoin="round"
                            class="w-5 h-5 flex-shrink-0"
                            v-html="icons[item.icon]"
                        />
                        <span v-if="sidebarOpen" class="font-medium text-sm truncate">
                            {{ item.title }}
                        </span>
                        
                        <!-- Badge de notificação -->
                        <span 
                            v-if="item.badge && getBadgeCount(item.badge) > 0"
                            :class="[
                                'ml-auto flex items-center justify-center text-xs font-bold',
                                'bg-red-500 text-white rounded-full',
                                sidebarOpen ? 'px-2 py-0.5 min-w-[20px]' : 'absolute -top-1 -right-1 w-5 h-5'
                            ]"
                        >
                            {{ getBadgeCount(item.badge) }}
                        </span>

                        <!-- Tooltip para sidebar fechada -->
                        <div 
                            v-if="!sidebarOpen"
                            class="absolute left-full ml-6 px-3 py-2 bg-slate-900 text-white text-sm rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap z-50 pointer-events-none"
                        >
                            {{ item.title }}
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-8 border-transparent border-r-slate-900"></div>
                        </div>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Footer da Sidebar -->
        <div class="p-4 border-t border-slate-100">
            <div 
                v-if="sidebarOpen"
                class="p-3 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl"
            >
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs font-semibold text-slate-700">Sistema Online</span>
                </div>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Todos os dados estão protegidos com criptografia AES-256
                </p>
            </div>
            <div v-else class="flex justify-center">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            </div>
        </div>
    </aside>
</template>
