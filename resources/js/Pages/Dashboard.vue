<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const stats = [
    { title: 'Usuários Totais', value: '12', trend: '+2 no mês', icon: 'users' },
    { title: 'Permissões Ativas', value: '8', trend: 'Segurança Alta', icon: 'shield' },
    { title: 'Grupos Criados', value: '3', trend: 'Organizados', icon: 'layers' },
    { title: 'Status Empresa', value: 'OK', trend: 'Conformidade', icon: 'check' },
];
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 ">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200 pb-8">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-md">Painel de Controle</span>
                        <span v-if="user?.is_super_user" class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-md">Admin Master</span>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">
                        Olá, {{ user?.name?.split(' ')[0] }}
                    </h1>
                    <p class="text-slate-500 font-medium">
                        Bem-vindo ao centro de operações <span class="text-slate-900 font-bold">LGPDGo.</span>
                    </p>
                </div>

                <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="bg-slate-900 p-3 rounded-xl shadow-lg">
                        <img src="/assets/images/lgpdgo.png" alt="LGPDGo" class="h-6 w-auto object-contain" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Organização</p>
                        <p class="text-sm font-bold text-slate-800">{{ company?.name || 'Sistema' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in stats" :key="stat.title" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg v-if="stat.icon === 'users'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-slate-900">{{ stat.value }}</p>
                    <p class="text-sm font-bold text-slate-800">{{ stat.title }}</p>
                    <p class="text-xs text-slate-400 mt-1 font-medium">{{ stat.trend }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-slate-900 italic">LGPDGo <span class="not-italic text-slate-400 font-light">Ecosystem</span></h3>
                            <button class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">Ver detalhes →</button>
                        </div>
                        <div class="p-8 grid sm:grid-cols-2 gap-8">
                            <div v-for="i in 4" :key="i" class="flex gap-4">
                                <div class="w-1.5 h-full bg-indigo-500 rounded-full"></div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Módulo de Segurança {{ i }}</h4>
                                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">Gerenciamento automatizado de dados e conformidade com as diretrizes vigentes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-2xl font-black border border-white/10">
                            {{ user?.name?.charAt(0) }}
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-1">Usuário Logado</p>
                            <h3 class="text-xl font-bold truncate">{{ user?.name }}</h3>
                            <p class="text-slate-400 text-sm truncate">{{ user?.email }}</p>
                        </div>

                        <div class="pt-6 space-y-3">
                            <div class="flex justify-between items-center text-xs border-b border-white/5 pb-2">
                                <span class="text-slate-400 font-medium">Nível de Acesso</span>
                                <span class="font-bold">{{ user?.is_super_user ? 'Master' : 'Padrão' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-b border-white/5 pb-2">
                                <span class="text-slate-400 font-medium">Licença</span>
                                <span class="text-emerald-400 font-bold italic">Ativa</span>
                            </div>
                        </div>

                        <button class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 transition-colors rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-900/20">
                            Configurações de Conta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Garante que o fundo cinza ocupe tudo */
:deep(main) {
    background-color: #f8fafc;
    padding: 0 !important;
}
</style>