<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const menuItems = [
    { title: 'Dashboard', icon: 'home', route: 'dashboard' },
    { title: 'Usuários', icon: 'users', route: null }, 
    { title: 'Empresas', icon: 'building', route: null },
    { title: 'Configurações', icon: 'settings', route: null },
];

const logout = () => {
    router.post(route('logout'));
};

// Mapeamento de ícones SVG para um look profissional
const icons = {
    home: `<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>`,
    users: `<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>`,
    building: `<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/>`,
    settings: `<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>`
};
</script>

<template>
    <div class="flex h-screen bg-slate-50 font-sans antialiased text-slate-900">
        <Head title="LGPDGo" />
        
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col z-50">
            <div class="h-20 flex items-center px-6">
                <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                    <div class="p-2 bg-slate-900 rounded-xl shadow-lg shadow-slate-200 group-hover:scale-105 transition-transform">
                        <img 
                            src="/assets/images/lgpdgo.png" 
                            alt="LGPDGo" 
                            class="w-8 h-8 object-contain" 
                        />
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight text-slate-900">
                            LGPD<span class="text-indigo-600">Go</span>
                        </h1>
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Proteção Ativa</p>
                        </div>
                    </div>
                </Link>
            </div>

            <div v-if="company" class="px-4 mb-4">
                <div class="p-3 bg-slate-900 rounded-2xl text-white shadow-xl shadow-slate-200 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-lg">🏢</div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight leading-none mb-1">Empresa</p>
                        <p class="text-xs font-bold truncate">{{ company.name }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-3 space-y-1 mt-2">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4 mt-6">Menu Principal</p>
                <Link
                    v-for="item in menuItems"
                    :key="item.title"
                    :href="item.route ? route(item.route) : '#'"
                    :class="[
                        item.route && route().current(item.route) 
                        ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' 
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                    ]"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                >
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        class="w-5 h-5 transition-transform group-hover:scale-110"
                        v-html="icons[item.icon]"
                    ></svg>
                    <span class="text-sm font-semibold">{{ item.title }}</span>
                </Link>
            </nav>

            <div class="p-4 bg-slate-50/80 border-t border-slate-100">
                <div class="flex items-center gap-3 p-2 bg-white rounded-2xl border border-slate-200 shadow-sm mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-bold shadow-inner">
                        {{ user?.name?.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate leading-none">{{ user?.name }}</p>
                        <p class="text-[11px] text-slate-500 truncate mt-1">Nível: {{ user?.is_super_user ? 'Admin' : 'Membro' }}</p>
                    </div>
                </div>
                <button
                    @click="logout"
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 rounded-xl border border-slate-200 transition-all text-xs font-bold uppercase tracking-wider shadow-sm hover:border-red-100"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sair da Conta
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-8">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Dashboard</h2>
                        <nav class="flex text-[11px] font-medium text-slate-400 uppercase tracking-widest mt-0.5">
                            <span>Início</span>
                            <span class="mx-2 text-slate-300">/</span>
                            <span class="text-indigo-500">Visão Geral</span>
                        </nav>
                    </div>

                    <div class="hidden lg:flex items-center bg-slate-100 px-4 py-2 rounded-xl border border-slate-200 w-80 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" placeholder="Buscar no sistema..." class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-slate-400 ml-2" />
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
                    </button>

                    <div v-if="user?.is_super_user" class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 border border-amber-100 rounded-lg">
                        <span class="text-sm">👑</span>
                        <span class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Master Admin</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50/50 p-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
/* Smooth Scrollbar para Webkit */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>