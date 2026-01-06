<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    request: Object,
});

const form = useForm({
    status: props.request.status,
    resposta: props.request.resposta || '',
    observacoes_internas: props.request.observacoes_internas || '',
});

const showResponseForm = ref(false);

const submit = () => {
    if (form.status === 'concluida' && !form.resposta.trim()) {
        alert('Por favor, preencha a resposta antes de concluir a solicitação.');
        return;
    }

    form.put(route('requests.update', props.request.id), {
        onSuccess: () => {
            showResponseForm.value = false;
        },
    });
};

const deleteRequest = () => {
    if (!confirm('Tem certeza que deseja excluir esta solicitação? Esta ação não pode ser desfeita.')) {
        return;
    }

    router.delete(route('requests.destroy', props.request.id));
};

const tipoLabels = {
    'acesso': '📄 Acesso aos Dados',
    'retificacao': '✏️ Retificação',
    'exclusao': '🗑️ Exclusão',
    'portabilidade': '📦 Portabilidade',
    'oposicao': '🚫 Oposição ao Tratamento',
    'informacao': 'ℹ️ Informação sobre Tratamento',
};

const statusLabels = {
    'pendente': 'Pendente',
    'em_andamento': 'Em Andamento',
    'concluida': 'Concluída',
    'rejeitada': 'Rejeitada',
};

const statusColors = {
    'pendente': 'bg-amber-100 text-amber-700 border-amber-300',
    'em_andamento': 'bg-blue-100 text-blue-700 border-blue-300',
    'concluida': 'bg-green-100 text-green-700 border-green-300',
    'rejeitada': 'bg-red-100 text-red-700 border-red-300',
};

const urgencyLabels = {
    'overdue': '🔴 Vencida',
    'critical': '🟠 Crítica',
    'high': '🟡 Alta',
    'normal': '🔵 Normal',
    'completed': '✅ Concluída',
};

const urgencyColors = {
    'overdue': 'bg-red-100 text-red-700 border-red-300',
    'critical': 'bg-orange-100 text-orange-700 border-orange-300',
    'high': 'bg-yellow-100 text-yellow-700 border-yellow-300',
    'normal': 'bg-blue-100 text-blue-700 border-blue-300',
    'completed': 'bg-green-100 text-green-700 border-green-300',
};

const getDaysRemainingText = () => {
    if (props.request.status === 'concluida' || props.request.status === 'rejeitada') {
        return 'Finalizada';
    }

    const days = props.request.days_remaining;
    if (days < 0) return `${Math.abs(days)} dias atrasado`;
    if (days === 0) return 'Vence hoje';
    return `${days} dias restantes`;
};
</script>

<template>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Link
                        :href="route('requests.index')"
                        class="text-slate-600 hover:text-slate-900 transition-colors"
                    >
                        ← Voltar
                    </Link>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Solicitação DSAR</span>
                </div>
                <div class="flex items-center gap-4">
                    <h1 class="text-3xl font-black text-slate-900">{{ request.protocolo }}</h1>
                    <span
                        class="px-4 py-2 text-sm font-bold rounded-xl border-2"
                        :class="statusColors[request.status]"
                    >
                        {{ statusLabels[request.status] }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button
                    v-if="request.status !== 'concluida' && request.status !== 'rejeitada'"
                    @click="showResponseForm = !showResponseForm"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                >
                    {{ showResponseForm ? '✗ Cancelar' : '✏️ Atualizar Status' }}
                </button>
                <button
                    v-if="request.status === 'rejeitada' || (new Date(request.created_at).getTime() < Date.now() - 365 * 24 * 60 * 60 * 1000)"
                    @click="deleteRequest"
                    class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-200"
                >
                    🗑️ Excluir
                </button>
            </div>
        </div>

        <!-- Urgência Alert -->
        <div
            v-if="request.status !== 'concluida' && request.status !== 'rejeitada'"
            class="mb-8 p-6 rounded-2xl border-2"
            :class="request.urgency === 'overdue' || request.urgency === 'critical'
                ? 'bg-red-50 border-red-300'
                : request.urgency === 'high'
                    ? 'bg-yellow-50 border-yellow-300'
                    : 'bg-blue-50 border-blue-300'"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                        :class="request.urgency === 'overdue' || request.urgency === 'critical'
                            ? 'bg-red-200'
                            : request.urgency === 'high'
                                ? 'bg-yellow-200'
                                : 'bg-blue-200'"
                    >
                        ⏱️
                    </div>
                    <div>
                        <div class="text-lg font-black text-slate-900">
                            {{ urgencyLabels[request.urgency] }}
                        </div>
                        <div class="text-sm text-slate-600">{{ getDaysRemainingText() }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Prazo LGPD</div>
                    <div class="text-lg font-black text-slate-900">
                        {{ new Date(request.prazo_resposta).toLocaleDateString('pt-BR') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form de Atualização -->
        <div v-if="showResponseForm" class="mb-8 bg-white rounded-3xl border-2 border-indigo-500 p-8 shadow-xl">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">✏️</div>
                <h3 class="text-xl font-black text-slate-900">Atualizar Solicitação</h3>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status *</label>
                    <select
                        v-model="form.status"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                    >
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                        <option value="rejeitada">Rejeitada</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Resposta {{ form.status === 'concluida' ? '*' : '(opcional)' }}
                    </label>
                    <textarea
                        v-model="form.resposta"
                        rows="5"
                        placeholder="Resposta detalhada para o titular dos dados..."
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                    ></textarea>
                    <p class="mt-1 text-xs text-slate-500">💡 Esta resposta será enviada ao titular dos dados</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Observações Internas (opcional)</label>
                    <textarea
                        v-model="form.observacoes_internas"
                        rows="3"
                        placeholder="Notas internas, contexto adicional, ações tomadas..."
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                    ></textarea>
                    <p class="mt-1 text-xs text-slate-500">🔒 Uso interno apenas, não será enviado ao titular</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="showResponseForm = false"
                        class="px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 font-bold rounded-xl transition-colors shadow-lg"
                        :class="form.processing
                            ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                            : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200'"
                    >
                        {{ form.processing ? 'Salvando...' : '✓ Salvar Atualização' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Dados do Titular -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">👤</div>
                        <h3 class="text-xl font-black text-slate-900">Dados do Titular</h3>
                    </div>

                    <div class="space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nome Completo</div>
                                <div class="text-lg font-bold text-slate-900">{{ request.nome_titular }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">CPF</div>
                                <div class="text-lg font-medium text-slate-900">{{ request.cpf }}</div>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">E-mail</div>
                                <div class="text-sm text-indigo-600 font-medium">{{ request.email }}</div>
                            </div>
                            <div v-if="request.telefone">
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Telefone</div>
                                <div class="text-sm text-slate-900 font-medium">{{ request.telefone }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Preferência de Contato</div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium">
                                {{ request.preferencia_contato === 'email' ? '📧 E-mail' : '📞 Telefone' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Detalhes da Solicitação -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">📋</div>
                        <h3 class="text-xl font-black text-slate-900">Detalhes da Solicitação</h3>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipo de Solicitação</div>
                            <div class="text-lg font-bold text-slate-900">{{ tipoLabels[request.tipo_solicitacao] }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Descrição</div>
                            <div class="prose prose-sm max-w-none">
                                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ request.descricao }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resposta -->
                <div v-if="request.resposta" class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl border-2 border-green-300 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-600 text-white rounded-xl flex items-center justify-center text-xl">💬</div>
                        <h3 class="text-xl font-black text-slate-900">Resposta Enviada</h3>
                    </div>

                    <div class="prose prose-sm max-w-none">
                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ request.resposta }}</p>
                    </div>
                </div>

                <!-- Observações Internas -->
                <div v-if="request.observacoes_internas" class="bg-slate-50 rounded-3xl border-2 border-slate-200 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-slate-700 text-white rounded-xl flex items-center justify-center text-xl">🔒</div>
                        <h3 class="text-xl font-black text-slate-900">Observações Internas</h3>
                    </div>

                    <div class="prose prose-sm max-w-none">
                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ request.observacoes_internas }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Timeline -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-xl">📅</span>
                        <h3 class="text-lg font-black text-slate-900">Timeline</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-sm">✓</div>
                                <div class="w-0.5 h-full bg-slate-200"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-bold text-slate-900">Criada</div>
                                <div class="text-xs text-slate-500">{{ new Date(request.created_at).toLocaleString('pt-BR') }}</div>
                            </div>
                        </div>

                        <div v-if="request.status === 'em_andamento' || request.status === 'concluida' || request.status === 'rejeitada'" class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm">⚙️</div>
                                <div v-if="request.status === 'concluida' || request.status === 'rejeitada'" class="w-0.5 h-full bg-slate-200"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="text-sm font-bold text-slate-900">Em Andamento</div>
                                <div class="text-xs text-slate-500">{{ new Date(request.updated_at).toLocaleString('pt-BR') }}</div>
                            </div>
                        </div>

                        <div v-if="request.status === 'concluida'" class="flex gap-3">
                            <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm">✓</div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-green-600">Concluída</div>
                                <div class="text-xs text-slate-500">{{ new Date(request.updated_at).toLocaleString('pt-BR') }}</div>
                            </div>
                        </div>

                        <div v-if="request.status === 'rejeitada'" class="flex gap-3">
                            <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm">✗</div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-red-600">Rejeitada</div>
                                <div class="text-xs text-slate-500">{{ new Date(request.updated_at).toLocaleString('pt-BR') }}</div>
                            </div>
                        </div>

                        <div v-if="request.status === 'pendente'" class="flex gap-3">
                            <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-sm">⏳</div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-slate-500">Aguardando</div>
                                <div class="text-xs text-slate-500">Próxima ação pendente</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl border-2 border-indigo-200 p-6">
                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Protocolo</div>
                            <div class="font-mono text-slate-900 font-bold">{{ request.protocolo }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Prazo LGPD</div>
                            <div class="font-bold text-slate-900">{{ new Date(request.prazo_resposta).toLocaleDateString('pt-BR') }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">ID</div>
                            <div class="text-slate-900">#{{ request.id }}</div>
                        </div>
                    </div>
                </div>

                <!-- LGPD Info -->
                <div class="bg-slate-50 rounded-2xl border-2 border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-xl">📚</span>
                        <h4 class="text-sm font-black text-slate-900">Base Legal LGPD</h4>
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                        <p><strong>Art. 18</strong> - Direitos do titular dos dados</p>
                        <p><strong>Art. 19</strong> - Prazo de 15 dias úteis para resposta</p>
                        <p><strong>Art. 20</strong> - Revisão de decisões automatizadas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
