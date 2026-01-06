<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    company: Object,
    stats: Object,
});

const documents = [
    {
        id: 'privacy-policy',
        title: 'Política de Privacidade',
        description: 'Documento completo descrevendo como sua empresa coleta, usa e protege dados pessoais',
        icon: '📄',
        color: 'blue',
        route: 'documents.privacy-policy',
        required: true,
    },
    {
        id: 'consent-terms',
        title: 'Termos de Consentimento',
        description: 'Formulário de consentimento para coleta e processamento de dados pessoais',
        icon: '✓',
        color: 'green',
        route: 'documents.consent-terms',
        required: true,
    },
    {
        id: 'cookie-policy',
        title: 'Política de Cookies',
        description: 'Detalhamento dos cookies utilizados e suas finalidades',
        icon: '🍪',
        color: 'amber',
        route: 'documents.cookie-policy',
        required: props.stats.cookies_count > 0,
    },
    {
        id: 'ropa-report',
        title: 'Relatório ROPA',
        description: 'Record of Processing Activities - inventário completo de dados',
        icon: '🗂️',
        color: 'purple',
        route: 'documents.ropa-report',
        required: props.stats.ropa_count > 0,
    },
    {
        id: 'compliance-report',
        title: 'Relatório de Conformidade',
        description: 'Análise detalhada do score de adequação LGPD com recomendações',
        icon: '📊',
        color: 'indigo',
        route: 'documents.compliance-report',
        required: false,
    },
    {
        id: 'dsar-report',
        title: 'Relatório DSAR',
        description: 'Histórico de solicitações de titulares de dados',
        icon: '📨',
        color: 'pink',
        route: 'documents.dsar-report',
        required: props.stats.dsar_count > 0,
    },
];

const colorClasses = {
    blue: 'bg-blue-100 text-blue-700 border-blue-300',
    green: 'bg-green-100 text-green-700 border-green-300',
    amber: 'bg-amber-100 text-amber-700 border-amber-300',
    purple: 'bg-purple-100 text-purple-700 border-purple-300',
    indigo: 'bg-indigo-100 text-indigo-700 border-indigo-300',
    pink: 'bg-pink-100 text-pink-700 border-pink-300',
};

const complianceLevel = props.stats.compliance_score.score >= 75 ? 'good' : props.stats.compliance_score.score >= 50 ? 'regular' : 'critical';
</script>

<template>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 mb-2">Gerador de Documentos</h1>
            <p class="text-slate-600">Gere documentos LGPD profissionais em PDF</p>
        </div>

        <!-- Score Card -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl border-2 border-indigo-200 p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-2">Score de Conformidade</div>
                    <div class="flex items-baseline gap-3">
                        <div class="text-5xl font-black text-indigo-600">{{ stats.compliance_score.score }}</div>
                        <div class="text-lg text-slate-600">/100 pontos</div>
                    </div>
                    <div class="mt-3">
                        <span
                            class="inline-block px-4 py-2 rounded-xl font-bold text-sm"
                            :class="complianceLevel === 'good' 
                                ? 'bg-green-100 text-green-700' 
                                : complianceLevel === 'regular' 
                                    ? 'bg-amber-100 text-amber-700' 
                                    : 'bg-red-100 text-red-700'"
                        >
                            {{ complianceLevel === 'good' ? '✓ Bom' : complianceLevel === 'regular' ? '⚠️ Regular' : '⚠️ Crítico' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <Link
                        :href="route('compliance-badge.index')"
                        class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                    >
                        🏆 Ver Selo de Conformidade
                    </Link>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🗂️</div>
                    <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">ROPA</div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats.ropa_count }}</div>
                <div class="text-xs text-slate-600 mt-1">Inventários mapeados</div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl">🍪</div>
                    <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">Cookies</div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats.cookies_count }}</div>
                <div class="text-xs text-slate-600 mt-1">Cookies catalogados</div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center text-xl">📨</div>
                    <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">DSAR</div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ stats.dsar_count }}</div>
                <div class="text-xs text-slate-600 mt-1">Solicitações registradas</div>
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">📑</div>
                <h2 class="text-xl font-black text-slate-900">Documentos Disponíveis</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div
                    v-for="doc in documents"
                    :key="doc.id"
                    class="group relative bg-slate-50 rounded-2xl border-2 border-slate-200 p-6 hover:border-indigo-300 hover:shadow-lg transition-all"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center text-2xl border-2 transition-all"
                            :class="colorClasses[doc.color]"
                        >
                            {{ doc.icon }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-black text-slate-900">{{ doc.title }}</h3>
                                <span
                                    v-if="doc.required"
                                    class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded"
                                >
                                    Obrigatório
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 mb-4">{{ doc.description }}</p>
                            
                            <a
                                :href="route(doc.route)"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-colors"
                            >
                                📥 Baixar PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
            <div class="flex items-start gap-3">
                <div class="text-2xl">💡</div>
                <div class="flex-1">
                    <div class="font-bold text-blue-900 mb-2">Dica: Personalize seus documentos</div>
                    <p class="text-sm text-blue-700">
                        Antes de gerar os documentos, certifique-se de preencher as informações da empresa em
                        <Link :href="route('dashboard')" class="underline font-bold">Configurações</Link>.
                        Isso garantirá que os PDFs contenham seus dados de contato, DPO e informações específicas.
                    </p>
                </div>
            </div>
        </div>

        <!-- Compliance Badge Preview -->
        <div class="mt-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl border-2 border-purple-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-600 text-white rounded-xl flex items-center justify-center text-xl">🏆</div>
                <h2 class="text-xl font-black text-slate-900">Selo de Conformidade LGPD</h2>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <p class="text-slate-700 mb-4">
                        Demonstre transparência e compromisso com a LGPD exibindo nosso selo de conformidade em seu site.
                        O selo é atualizado automaticamente com base no seu score.
                    </p>
                    <Link
                        :href="route('compliance-badge.index')"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors"
                    >
                        ➜ Obter Código do Selo
                    </Link>
                </div>
                <div class="flex items-center justify-center">
                    <img
                        :src="`/api/compliance-badge/${company.slug}`"
                        alt="Selo de Conformidade LGPD"
                        class="max-w-[200px] drop-shadow-xl"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
