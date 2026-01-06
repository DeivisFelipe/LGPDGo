<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    inventory: Object,
});

const baseLegalLabels = {
    'consentimento': 'Consentimento do Titular',
    'contrato': 'Execução de Contrato',
    'obrigacao_legal': 'Cumprimento de Obrigação Legal',
    'legitimo_interesse': 'Legítimo Interesse',
    'protecao_da_vida': 'Proteção da Vida',
    'exercicio_regular_direitos': 'Exercício Regular de Direitos',
};

const baseLegalColors = {
    'consentimento': 'bg-green-100 text-green-700 border-green-300',
    'contrato': 'bg-blue-100 text-blue-700 border-blue-300',
    'obrigacao_legal': 'bg-purple-100 text-purple-700 border-purple-300',
    'legitimo_interesse': 'bg-amber-100 text-amber-700 border-amber-300',
    'protecao_da_vida': 'bg-red-100 text-red-700 border-red-300',
    'exercicio_regular_direitos': 'bg-indigo-100 text-indigo-700 border-indigo-300',
};
</script>

<template>
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Link
                        :href="route('data-inventories.index')"
                        class="text-slate-600 hover:text-slate-900 transition-colors"
                    >
                        ← Voltar
                    </Link>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">ROPA</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900">{{ inventory.nome_processo }}</h1>
                <p class="text-sm text-slate-500 mt-1">UUID: {{ inventory.uuid }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="window.print()"
                    class="px-4 py-2 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                >
                    🖨️ Imprimir
                </button>
                <Link
                    :href="route('data-inventories.edit', inventory.id)"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                >
                    ✏️ Editar
                </Link>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-2xl text-white">
                        ✓
                    </div>
                    <div>
                        <div class="text-lg font-black text-slate-900">Inventário Completo</div>
                        <div class="text-sm text-slate-600">Criado em {{ new Date(inventory.created_at).toLocaleString('pt-BR') }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Departamento</div>
                    <div class="text-lg font-black text-slate-900">{{ inventory.department?.name || '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Grid de Informações -->
        <div class="grid lg:grid-cols-2 gap-8 mb-8">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">📋</div>
                    <h3 class="text-xl font-black text-slate-900">Informações Básicas</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nome do Processo</div>
                        <div class="text-lg font-bold text-slate-900">{{ inventory.nome_processo }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Finalidade do Tratamento</div>
                        <div class="text-sm text-slate-700 leading-relaxed">{{ inventory.finalidade }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Base Legal</div>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold border-2"
                            :class="baseLegalColors[inventory.base_legal] || 'bg-slate-100 text-slate-700'"
                        >
                            ⚖️ {{ baseLegalLabels[inventory.base_legal] || inventory.base_legal }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Categorias de Dados -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📊</div>
                    <h3 class="text-xl font-black text-slate-900">Categorias de Dados</h3>
                </div>

                <div v-if="inventory.categoria_dados && inventory.categoria_dados.length > 0" class="space-y-2">
                    <div
                        v-for="(categoria, index) in inventory.categoria_dados"
                        :key="index"
                        class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl border border-blue-200"
                    >
                        <span class="text-blue-600 font-bold mt-0.5">•</span>
                        <span class="text-sm text-slate-700 flex-1">{{ categoria }}</span>
                    </div>
                </div>
                <div v-else class="text-sm text-slate-500 italic">Nenhuma categoria registrada</div>

                <div class="mt-6 pt-6 border-t-2 border-slate-100">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total de Categorias</div>
                    <div class="text-3xl font-black text-slate-900">
                        {{ inventory.categoria_dados?.length || 0 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Origem e Compartilhamento -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🔄</div>
                <h3 class="text-xl font-black text-slate-900">Origem e Compartilhamento</h3>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Origem -->
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Origem dos Dados</div>
                    <div v-if="inventory.origem_dados && inventory.origem_dados.length > 0" class="space-y-2">
                        <div
                            v-for="(origem, index) in inventory.origem_dados"
                            :key="index"
                            class="flex items-center gap-2 text-sm text-slate-700"
                        >
                            <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                            {{ origem }}
                        </div>
                    </div>
                    <div v-else class="text-sm text-slate-400 italic">Não informado</div>
                </div>

                <!-- Quem Acessa -->
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Acesso Interno</div>
                    <div v-if="inventory.quem_acessa && inventory.quem_acessa.length > 0" class="space-y-2">
                        <div
                            v-for="(acesso, index) in inventory.quem_acessa"
                            :key="index"
                            class="flex items-center gap-2 text-sm text-slate-700"
                        >
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            {{ acesso }}
                        </div>
                    </div>
                    <div v-else class="text-sm text-slate-400 italic">Não informado</div>
                </div>

                <!-- Destinatários -->
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Destinatários Externos</div>
                    <div v-if="inventory.destinatarios_dados && inventory.destinatarios_dados.length > 0" class="space-y-2">
                        <div
                            v-for="(dest, index) in inventory.destinatarios_dados"
                            :key="index"
                            class="flex items-center gap-2 text-sm text-slate-700"
                        >
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            {{ dest }}
                        </div>
                    </div>
                    <div v-else class="text-sm text-slate-400 italic">Não informado</div>
                </div>
            </div>

            <!-- Transferência Internacional -->
            <div v-if="inventory.transferencia_internacional" class="mt-6 pt-6 border-t-2 border-slate-200">
                <div class="p-4 bg-amber-50 border-2 border-amber-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl">🌍</div>
                        <div>
                            <div class="font-black text-slate-900">Transferência Internacional</div>
                            <div class="text-sm text-slate-600 mt-1">
                                Destino: <span class="font-bold">{{ inventory.pais_destino || 'Não especificado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segurança e Retenção -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Medidas de Segurança -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-xl">🔒</div>
                    <h3 class="text-xl font-black text-slate-900">Medidas de Segurança</h3>
                </div>

                <div class="prose prose-sm max-w-none">
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ inventory.medidas_seguranca }}</p>
                </div>
            </div>

            <!-- Tempo de Retenção -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl">⏱️</div>
                    <h3 class="text-xl font-black text-slate-900">Tempo de Retenção</h3>
                </div>

                <div class="p-4 bg-amber-50 border-2 border-amber-200 rounded-xl">
                    <div class="text-lg font-bold text-slate-900">{{ inventory.tempo_retencao }}</div>
                </div>

                <div class="mt-6 pt-6 border-t-2 border-slate-100 space-y-3 text-sm text-slate-600">
                    <div class="flex items-start gap-2">
                        <span class="text-green-600">✓</span>
                        <span>Os dados devem ser eliminados após o prazo especificado</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-green-600">✓</span>
                        <span>A eliminação deve ser documentada e auditável</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-green-600">✓</span>
                        <span>Titular pode solicitar eliminação antecipada (Art. 16 LGPD)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadados -->
        <div class="mt-8 p-6 bg-slate-50 border-2 border-slate-200 rounded-2xl">
            <div class="grid sm:grid-cols-3 gap-6 text-sm">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Criado em</div>
                    <div class="text-slate-900 font-medium">{{ new Date(inventory.created_at).toLocaleString('pt-BR') }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Última Atualização</div>
                    <div class="text-slate-900 font-medium">{{ new Date(inventory.updated_at).toLocaleString('pt-BR') }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Empresa</div>
                    <div class="text-slate-900 font-medium">{{ inventory.company?.name || '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-5xl, .max-w-5xl * {
        visibility: visible;
    }
    .max-w-5xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    button, .shadow-lg {
        display: none !important;
    }
}
</style>
