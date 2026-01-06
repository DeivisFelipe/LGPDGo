<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ComplianceGauge from '@/Components/ComplianceGauge.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    compliance: Object,
    dsarPending: Array,
    criticalRisks: Array,
    stats: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

// Helper para formatar urgência
const getUrgencyClass = (urgency) => {
    switch (urgency) {
        case 'overdue':
            return 'bg-red-100 text-red-700 border-red-200';
        case 'critical':
            return 'bg-orange-100 text-orange-700 border-orange-200';
        case 'high':
            return 'bg-amber-100 text-amber-700 border-amber-200';
        default:
            return 'bg-blue-100 text-blue-700 border-blue-200';
    }
};

const getUrgencyLabel = (urgency) => {
    switch (urgency) {
        case 'overdue':
            return 'ATRASADO';
        case 'critical':
            return 'URGENTE';
        case 'high':
            return 'PRIORITÁRIO';
        default:
            return 'NORMAL';
    }
};

const getRiskClass = (nivel) => {
    switch (nivel) {
        case 'critico':
            return 'bg-red-500 text-white';
        case 'alto':
            return 'bg-orange-500 text-white';
        case 'medio':
            return 'bg-amber-500 text-white';
        default:
            return 'bg-blue-500 text-white';
    }
};
</script>

<template>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">
                    Olá, {{ user?.name?.split(' ')[0] }} 👋
                </h1>
                <p class="text-slate-600 mt-1">Visão geral da conformidade LGPD da sua empresa</p>
            </div>
            
            <div v-if="company" class="px-4 py-2 bg-white rounded-xl border-2 border-slate-200 shadow-sm">
                <div class="text-xs text-slate-500 font-bold uppercase tracking-wide">Empresa</div>
                <div class="text-sm font-bold text-slate-900">{{ company.name }}</div>
            </div>
        </div>

        <!-- Score de Adequação -->
        <div class="bg-gradient-to-br from-indigo-600 to-blue-600 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-200">
            <div class="grid lg:grid-cols-3 gap-8 items-center">
                <!-- Gauge Chart -->
                <div class="flex justify-center">
                    <div class="bg-white rounded-2xl p-6 shadow-xl">
                        <ComplianceGauge :score="compliance?.score || 0" :size="200" />
                    </div>
                </div>

                <!-- Informações -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <h2 class="text-2xl font-black mb-2">Score de Adequação LGPD</h2>
                        <p class="text-indigo-100 text-lg">{{ compliance?.level?.description }}</p>
                    </div>

                    <!-- Breakdown por Categoria -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold">📋 ROPA (Inventário)</span>
                                <span class="text-lg font-black">{{ compliance?.details?.ropa?.score?.toFixed(0) }}/25</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div 
                                    class="bg-white h-2 rounded-full transition-all duration-500"
                                    :style="{ width: ((compliance?.details?.ropa?.score / 25) * 100) + '%' }"
                                ></div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold">📨 DSAR (Solicitações)</span>
                                <span class="text-lg font-black">{{ compliance?.details?.dsar?.score?.toFixed(0) }}/20</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div 
                                    class="bg-white h-2 rounded-full transition-all duration-500"
                                    :style="{ width: ((compliance?.details?.dsar?.score / 20) * 100) + '%' }"
                                ></div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold">⚠️ Gestão de Riscos</span>
                                <span class="text-lg font-black">{{ compliance?.details?.risks?.score?.toFixed(0) }}/20</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div 
                                    class="bg-white h-2 rounded-full transition-all duration-500"
                                    :style="{ width: ((compliance?.details?.risks?.score / 20) * 100) + '%' }"
                                ></div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold">🍪 Cookies</span>
                                <span class="text-lg font-black">{{ compliance?.details?.cookies?.score?.toFixed(0) }}/15</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div 
                                    class="bg-white h-2 rounded-full transition-all duration-500"
                                    :style="{ width: ((compliance?.details?.cookies?.score / 15) * 100) + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Rápidos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-2xl">📋</div>
                    <span 
                        class="text-xs font-bold px-2 py-1 rounded-full"
                        :class="compliance?.details?.ropa?.status === 'good' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                    >
                        {{ compliance?.details?.ropa?.percentage }}%
                    </span>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats?.total_inventories || 0 }}</div>
                <div class="text-sm font-bold text-slate-600">Inventários ROPA</div>
                <div class="text-xs text-slate-400 mt-1">{{ compliance?.details?.ropa?.complete }} completos</div>
            </div>

            <div class="bg-white rounded-2xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-2xl">📨</div>
                    <span 
                        v-if="stats?.overdue_dsar > 0"
                        class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 animate-pulse"
                    >
                        {{ stats.overdue_dsar }} atrasado{{ stats.overdue_dsar > 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats?.pending_dsar || 0 }}</div>
                <div class="text-sm font-bold text-slate-600">DSAR Pendentes</div>
                <div class="text-xs text-slate-400 mt-1">{{ stats?.total_dsar }} no total</div>
            </div>

            <div class="bg-white rounded-2xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-2xl">⚠️</div>
                    <span 
                        v-if="stats?.critical_risks > 0"
                        class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700"
                    >
                        {{ stats.critical_risks }} crítico{{ stats.critical_risks > 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats?.total_risks || 0 }}</div>
                <div class="text-sm font-bold text-slate-600">Riscos Mapeados</div>
                <div class="text-xs text-slate-400 mt-1">{{ compliance?.details?.risks?.mitigated }} mitigados</div>
            </div>

            <div class="bg-white rounded-2xl p-6 border-2 border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-2xl">🏛️</div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ compliance?.details?.structure?.departments || 0 }}</div>
                <div class="text-sm font-bold text-slate-600">Departamentos</div>
                <div class="text-xs text-slate-400 mt-1">{{ compliance?.details?.structure?.users }} usuários</div>
            </div>
        </div>

        <!-- Grid Principal: Próximos Passos + DSAR -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Próximos Passos (2 colunas) -->
            <div class="lg:col-span-2 bg-white rounded-3xl border-2 border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-blue-50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Próximos Passos</h3>
                        <p class="text-sm text-slate-600">Ações recomendadas para melhorar seu score</p>
                    </div>
                    <div class="text-3xl">🎯</div>
                </div>

                <div class="p-6 space-y-4">
                    <div 
                        v-for="(step, index) in compliance?.nextSteps"
                        :key="index"
                        class="flex items-start gap-4 p-4 rounded-xl border-2 transition-all hover:shadow-md"
                        :class="{
                            'border-red-200 bg-red-50': step.impact === 'critical',
                            'border-amber-200 bg-amber-50': step.impact === 'high',
                            'border-blue-200 bg-blue-50': step.impact === 'medium',
                            'border-slate-200 bg-slate-50': step.impact === 'low',
                            'border-green-200 bg-green-50': step.impact === 'achievement'
                        }"
                    >
                        <div class="text-3xl">{{ step.icon }}</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-slate-900">{{ step.title }}</h4>
                                <span 
                                    v-if="step.impact === 'critical'"
                                    class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-600 text-white"
                                >
                                    URGENTE
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">{{ step.description }}</p>
                            <Link
                                v-if="step.route"
                                :href="route(step.route)"
                                class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                            >
                                <span>Ir para {{ step.title }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <div v-if="!compliance?.nextSteps?.length" class="text-center py-12">
                        <div class="text-6xl mb-4">🎉</div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">Tudo em dia!</h4>
                        <p class="text-slate-600">Não há ações urgentes no momento.</p>
                    </div>
                </div>
            </div>

            <!-- DSAR Pendentes (1 coluna) -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-black text-slate-900">DSAR Pendentes</h3>
                        <div class="text-2xl">📨</div>
                    </div>
                    <p class="text-sm text-slate-600">Solicitações de titulares aguardando resposta</p>
                </div>

                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    <div 
                        v-for="dsar in dsarPending"
                        :key="dsar.id"
                        class="p-4 rounded-xl border-2 hover:shadow-md transition-all"
                        :class="getUrgencyClass(dsar.urgency)"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <div class="text-xs font-black uppercase tracking-wider">
                                {{ dsar.protocolo }}
                            </div>
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-white/50">
                                {{ getUrgencyLabel(dsar.urgency) }}
                            </span>
                        </div>
                        <div class="font-bold text-slate-900 mb-1">{{ dsar.nome_titular }}</div>
                        <div class="text-sm text-slate-600 mb-2 capitalize">{{ dsar.tipo.replace('_', ' ') }}</div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium">Prazo: {{ dsar.prazo_legal }}</span>
                            <span 
                                class="font-black"
                                :class="{
                                    'text-red-700': dsar.is_overdue,
                                    'text-orange-700': dsar.days_remaining <= 3,
                                    'text-amber-700': dsar.days_remaining <= 7
                                }"
                            >
                                {{ dsar.is_overdue ? 'ATRASADO' : `${dsar.days_remaining} dias` }}
                            </span>
                        </div>
                    </div>

                    <div v-if="!dsarPending?.length" class="text-center py-8">
                        <div class="text-4xl mb-2">✅</div>
                        <p class="text-sm text-slate-600">Nenhuma solicitação pendente</p>
                    </div>
                </div>

                <div v-if="dsarPending?.length" class="p-4 border-t border-slate-100">
                    <Link
                        :href="route('requests.index')"
                        class="block text-center py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-colors"
                    >
                        Ver Todas as Solicitações
                    </Link>
                </div>
            </div>
        </div>

        <!-- Riscos Críticos -->
        <div v-if="criticalRisks?.length" class="bg-white rounded-3xl border-2 border-red-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-red-100 bg-red-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">⚠️ Riscos Críticos e Altos</h3>
                        <p class="text-sm text-slate-600">Requerem atenção imediata</p>
                    </div>
                    <Link
                        :href="route('risks.index')"
                        class="text-sm font-bold text-red-600 hover:text-red-800"
                    >
                        Ver Todos →
                    </Link>
                </div>
            </div>

            <div class="p-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                    v-for="risk in criticalRisks"
                    :key="risk.id"
                    class="p-4 rounded-xl border-2 border-slate-200 hover:shadow-md transition-all"
                >
                    <div class="flex items-start gap-3 mb-3">
                        <span 
                            class="px-2 py-1 rounded-lg text-xs font-black uppercase"
                            :class="getRiskClass(risk.nivel_risco)"
                        >
                            {{ risk.nivel_risco }}
                        </span>
                        <span 
                            v-if="!risk.tem_plano"
                            class="px-2 py-1 rounded-lg text-xs font-black uppercase bg-amber-100 text-amber-700"
                        >
                            Sem Plano
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">{{ risk.titulo }}</h4>
                    <div class="text-xs text-slate-600 capitalize">Status: {{ risk.status.replace('_', ' ') }}</div>
                </div>
            </div>
        </div>

        <!-- Botão Gerar Selo (só aparece com score >= 85) -->
        <div 
            v-if="compliance?.score >= 85"
            class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-8 text-white shadow-2xl shadow-green-200 text-center"
        >
            <div class="text-6xl mb-4">🏅</div>
            <h3 class="text-2xl font-black mb-2">Parabéns! Você está pronto para o Selo LGPD</h3>
            <p class="text-green-100 mb-6 max-w-2xl mx-auto">
                Sua empresa atingiu um excelente nível de conformidade. Gere seu selo oficial e mostre ao mercado seu compromisso com a proteção de dados.
            </p>
            <Link
                :href="route('seal.generate')"
                class="inline-block px-8 py-4 bg-white text-green-600 font-black rounded-xl hover:bg-green-50 transition-colors shadow-xl text-lg"
            >
                Gerar Selo de Conformidade LGPD
            </Link>
        </div>
    </div>
</template>
