<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    requests: Object,
    filters: Object,
    stats: Object,
});

const filters = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    tipo_solicitacao: props.filters.tipo_solicitacao || '',
    urgency: props.filters.urgency || '',
    sort_by: props.filters.sort_by || 'created_at',
    sort_order: props.filters.sort_order || 'desc',
});

const applyFilters = () => {
    router.get(route('requests.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filters.value = {
        search: '',
        status: '',
        tipo_solicitacao: '',
        urgency: '',
        sort_by: 'created_at',
        sort_order: 'desc',
    };
    applyFilters();
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

const tipoLabels = {
    'acesso': 'Acesso',
    'retificacao': 'Retificação',
    'exclusao': 'Exclusão',
    'portabilidade': 'Portabilidade',
    'oposicao': 'Oposição',
    'informacao': 'Informações',
};

const urgencyLabels = {
    'overdue': 'Atrasado',
    'critical': 'Crítico',
    'high': 'Alto',
    'normal': 'Normal',
    'completed': 'Concluído',
};

const urgencyColors = {
    'overdue': 'bg-red-100 text-red-700 border-red-300',
    'critical': 'bg-orange-100 text-orange-700 border-orange-300',
    'high': 'bg-yellow-100 text-yellow-700 border-yellow-300',
    'normal': 'bg-blue-100 text-blue-700 border-blue-300',
    'completed': 'bg-green-100 text-green-700 border-green-300',
};

const getUrgencyIcon = (urgency) => {
    const icons = {
        overdue: '🔴',
        critical: '🟠',
        high: '🟡',
        normal: '🔵',
        completed: '✅',
    };
    return icons[urgency] || '⚪';
};
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Solicitações DSAR</h1>
                <p class="text-slate-600">Direitos dos Titulares de Dados (Data Subject Access Requests)</p>
            </div>
            <a
                :href="route('dsar.create')"
                target="_blank"
                class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
            >
                🔗 Portal Público DSAR
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ stats.total }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Total de Solicitações</div>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-red-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-red-700">{{ stats.overdue }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Atrasadas</div>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">🔴</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-orange-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-orange-700">{{ stats.critical }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Críticas (≤3 dias)</div>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">🟠</div>
                </div>
            </div>

            <div
                v-for="(count, status) in stats.by_status"
                :key="status"
                class="bg-white rounded-2xl border-2 border-slate-200 p-6"
            >
                <div class="text-2xl font-black text-slate-900">{{ count }}</div>
                <div class="text-sm font-medium text-slate-600 mt-1">{{ statusLabels[status] }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 mb-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <input
                        v-model="filters.search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Buscar por nome, e-mail, protocolo..."
                        class="flex-1 px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                    />
                    <button
                        @click="applyFilters"
                        class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                    >
                        🔍 Buscar
                    </button>
                    <button
                        @click="resetFilters"
                        class="px-4 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300"
                    >
                        Limpar
                    </button>
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todos os status</option>
                            <option v-for="(label, value) in statusLabels" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filters.tipo_solicitacao"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todos os tipos</option>
                            <option v-for="(label, value) in tipoLabels" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filters.urgency"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todas as urgências</option>
                            <option v-for="(label, value) in urgencyLabels" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b-2 border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Protocolo
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Titular
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                                Urgência
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Prazo
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-700 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-if="requests.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="text-5xl mb-3">📋</div>
                                <div class="font-bold text-lg mb-1">Nenhuma solicitação encontrada</div>
                                <div class="text-sm">As solicitações DSAR aparecerão aqui</div>
                            </td>
                        </tr>
                        <tr
                            v-for="request in requests.data"
                            :key="request.id"
                            class="hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <Link
                                    :href="route('requests.show', request.id)"
                                    class="font-bold text-slate-900 hover:text-indigo-600"
                                >
                                    {{ request.protocolo }}
                                </Link>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ request.nome_titular }}</div>
                                <div class="text-xs text-slate-500">{{ request.email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-700">{{ tipoLabels[request.tipo_solicitacao] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-lg border-2"
                                    :class="urgencyColors[request.urgency]"
                                >
                                    <span>{{ getUrgencyIcon(request.urgency) }}</span>
                                    {{ urgencyLabels[request.urgency] }}
                                </span>
                                <div v-if="request.days_remaining !== undefined" class="text-xs text-slate-500 mt-1">
                                    {{ request.days_remaining >= 0 ? request.days_remaining + ' dias' : 'Atrasado' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-bold rounded-lg border-2"
                                    :class="statusColors[request.status]"
                                >
                                    {{ statusLabels[request.status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700">
                                    {{ new Date(request.prazo_resposta).toLocaleDateString('pt-BR') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('requests.show', request.id)"
                                        class="px-3 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                    >
                                        Ver Detalhes
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="requests.links.length > 3" class="px-6 py-4 border-t-2 border-slate-200 bg-slate-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Mostrando <span class="font-bold">{{ requests.from || 0 }}</span>
                        a <span class="font-bold">{{ requests.to || 0 }}</span>
                        de <span class="font-bold">{{ requests.total }}</span> resultados
                    </div>
                    <div class="flex items-center gap-2">
                        <Link
                            v-for="(link, index) in requests.links"
                            :key="index"
                            :href="link.url"
                            :class="[
                                'px-4 py-2 text-sm font-bold rounded-lg transition-colors',
                                link.active
                                    ? 'bg-indigo-600 text-white'
                                    : link.url
                                        ? 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
                                        : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                            ]"
                            preserve-scroll
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
