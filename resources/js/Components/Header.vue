<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);
const companies = computed(() => page.props.companies || []);
const notifications = ref([
    { id: 1, type: 'warning', title: 'Solicitação DSAR vencendo', message: 'Prazo de 3 dias', time: '2h atrás', unread: true },
    { id: 2, type: 'info', title: 'Novo treinamento disponível', message: 'Introdução à LGPD', time: '1 dia', unread: true },
    { id: 3, type: 'success', title: 'Mapeamento concluído', message: 'Setor de RH', time: '2 dias', unread: false },
]);

const unreadCount = computed(() => notifications.value.filter(n => n.unread).length);

const notificationDropdownOpen = ref(false);
const profileDropdownOpen = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const switchCompany = (companyId) => {
    // Lógica para trocar de empresa (implementar depois)
    console.log('Switching to company:', companyId);
};

const getNotificationIcon = (type) => {
    const icons = {
        warning: `<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>`,
        info: `<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>`,
        success: `<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>`,
        error: `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`
    };
    return icons[type] || icons.info;
};

const getNotificationColor = (type) => {
    const colors = {
        warning: 'bg-amber-100 text-amber-600',
        info: 'bg-blue-100 text-blue-600',
        success: 'bg-green-100 text-green-600',
        error: 'bg-red-100 text-red-600'
    };
    return colors[type] || colors.info;
};
</script>

<template>
    <header class="bg-white border-b border-slate-200 h-20 flex items-center px-6 z-40">
        <div class="flex items-center justify-between w-full">
            <!-- Breadcrumb / Título da Página -->
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    <slot name="title">Dashboard</slot>
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    <slot name="subtitle">Visão geral da adequação LGPD</slot>
                </p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Seletor de Empresa (apenas para superusuários) -->
                <div v-if="user?.is_super_user && companies.length > 0" class="relative">
                    <button 
                        class="flex items-center gap-3 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors border border-slate-200"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ company?.name?.charAt(0) || 'E' }}
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Empresa Atual</p>
                            <p class="text-sm font-bold text-slate-900 truncate max-w-[150px]">{{ company?.name }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Badge de Empresa (para usuários normais) -->
                <div v-else-if="company" class="px-4 py-2 bg-slate-900 rounded-xl text-white flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-semibold">{{ company.name }}</span>
                </div>

                <!-- Botão de Ajuda LGPD -->
                <button 
                    class="p-2.5 hover:bg-indigo-50 rounded-xl transition-colors group relative"
                    title="Ajuda LGPD"
                >
                    <svg class="w-6 h-6 text-slate-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                </button>

                <!-- Notificações -->
                <div class="relative">
                    <button 
                        @click="notificationDropdownOpen = !notificationDropdownOpen"
                        class="p-2.5 hover:bg-slate-50 rounded-xl transition-colors relative"
                    >
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span 
                            v-if="unreadCount > 0"
                            class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
                        >
                            {{ unreadCount }}
                        </span>
                    </button>

                    <!-- Dropdown de Notificações -->
                    <transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div 
                            v-if="notificationDropdownOpen"
                            @click.away="notificationDropdownOpen = false"
                            class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50"
                        >
                            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-slate-900">Notificações</h3>
                                <button class="text-xs text-indigo-600 font-semibold hover:text-indigo-700">
                                    Marcar todas como lidas
                                </button>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                <div 
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    :class="[
                                        'p-4 hover:bg-slate-50 transition-colors cursor-pointer border-b border-slate-100',
                                        notification.unread && 'bg-blue-50/50'
                                    ]"
                                >
                                    <div class="flex items-start gap-3">
                                        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0', getNotificationColor(notification.type)]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" v-html="getNotificationIcon(notification.type)"/>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="font-semibold text-sm text-slate-900">{{ notification.title }}</p>
                                                <span v-if="notification.unread" class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1"></span>
                                            </div>
                                            <p class="text-sm text-slate-600 mt-1">{{ notification.message }}</p>
                                            <p class="text-xs text-slate-400 mt-2">{{ notification.time }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 border-t border-slate-100">
                                <Link 
                                    href="#"
                                    class="block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 py-2"
                                >
                                    Ver todas as notificações
                                </Link>
                            </div>
                        </div>
                    </transition>
                </div>

                <!-- Divisor -->
                <div class="w-px h-8 bg-slate-200"></div>

                <!-- Perfil do Usuário -->
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button class="flex items-center gap-3 px-3 py-2 hover:bg-slate-50 rounded-xl transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-200">
                                {{ user?.name?.charAt(0).toUpperCase() }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ user?.name }}</p>
                                <p class="text-xs text-slate-500">{{ user?.email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Conta</p>
                            <p class="text-sm font-bold text-slate-900 mt-1">{{ user?.name }}</p>
                            <p class="text-xs text-slate-500">{{ user?.email }}</p>
                        </div>

                        <DropdownLink :href="route('profile.edit')">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meu Perfil
                        </DropdownLink>

                        <DropdownLink href="#">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Configurações
                        </DropdownLink>

                        <div class="border-t border-slate-100"></div>

                        <DropdownLink as="button" @click="logout">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>
    </header>
</template>
