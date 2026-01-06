<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    inventories: Object,
    departments: Array,
    filters: Object,
    stats: Object,
});

const filters = ref({
    search: props.filters.search || '',
    department_id: props.filters.department_id || '',
    base_legal: props.filters.base_legal || '',
    transferencia_internacional: props.filters.transferencia_internacional || '',
    sort_by: props.filters.sort_by || 'created_at',
    sort_order: props.filters.sort_order || 'desc',
});

const applyFilters = () => {
    router.get(route('data-inventories.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filters.value = {
        search: '',
        department_id: '',
        base_legal: '',
        transferencia_internacional: '',
        sort_by: 'created_at',
        sort_order: 'desc',
    };
    applyFilters();
};

const deleteInventory = (id) => {
    if (confirm('Tem certeza que deseja excluir este inventário? Esta ação não pode ser desfeita.')) {
        router.delete(route('data-inventories.destroy', id), {
            preserveScroll: true,
        });
    }
};

const exportInventories = () => {
    window.location.href = route('data-inventories.export');
};

const baseLegalLabels = {
    'consentimento': 'Consentimento',
    'contrato': 'Contrato',
    'obrigacao_legal': 'Obrigação Legal',
    'legitimo_interesse': 'Legítimo Interesse',
    'protecao_da_vida': 'Proteção da Vida',
    'exercicio_regular_direitos': 'Exercício de Direitos',
};

const baseLegalColors = {
    'consentimento': 'bg-green-100 text-green-700 border-green-200',
    'contrato': 'bg-blue-100 text-blue-700 border-blue-200',
    'obrigacao_legal': 'bg-purple-100 text-purple-700 border-purple-200',
    'legitimo_interesse': 'bg-amber-100 text-amber-700 border-amber-200',
    'protecao_da_vida': 'bg-red-100 text-red-700 border-red-200',
    'exercicio_regular_direitos': 'bg-indigo-100 text-indigo-700 border-indigo-200',
};

const sortBy = (field) => {
    if (filters.value.sort_by === field) {
        filters.value.sort_order = filters.value.sort_order === 'asc' ? 'desc' : 'asc';
    } else {
        filters.value.sort_by = field;
        filters.value.sort_order = 'asc';
    }
    applyFilters();
};

const showFiltersPanel = ref(false);
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Inventário de Dados (ROPA)</h1>
                <p class="text-slate-600">Registro de atividades de tratamento de dados pessoais</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="exportInventories"
                    class="px-4 py-2 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                >
                    📥 Exportar
                </button>
                <Link
                    :href="route('data-inventories.create')"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                >
                    + Novo Inventário
                </Link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ stats.total }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Total de Inventários</div>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">📋</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ stats.with_international_transfer }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Transferência Internacional</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">🌍</div>
                </div>
            </div>

            <div
                v-for="(count, base) in stats.by_base_legal"
                :key="base"
                class="bg-white rounded-2xl border-2 border-slate-200 p-6"
            >
                <div class="text-2xl font-black text-slate-900">{{ count }}</div>
                <div class="text-sm font-medium text-slate-600 mt-1">{{ baseLegalLabels[base] || base }}</div>
                <span
                    class="inline-block mt-2 px-2 py-1 text-xs font-bold rounded border"
                    :class="baseLegalColors[base] || 'bg-slate-100 text-slate-700'"
                >
                    {{ base }}
                </span>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-black text-slate-900">Filtros e Busca</h3>
                <button
                    @click="showFiltersPanel = !showFiltersPanel"
                    class="text-sm font-bold text-indigo-600 hover:text-indigo-700"
                >
                    {{ showFiltersPanel ? 'Ocultar Filtros' : 'Mostrar Filtros' }}
                </button>
            </div>

            <div class="space-y-4">
                <!-- Search -->
                <div class="flex gap-3">
                    <input
                        v-model="filters.search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Buscar por nome do processo, finalidade, departamento..."
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

                <!-- Advanced Filters -->
                <div v-show="showFiltersPanel" class="grid sm:grid-cols-3 gap-4 pt-4 border-t-2 border-slate-200">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Departamento</label>
                        <select
                            v-model="filters.department_id"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todos os departamentos</option>
                            <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                {{ dept.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Base Legal</label>
                        <select
                            v-model="filters.base_legal"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todas as bases legais</option>
                            <option v-for="(label, value) in baseLegalLabels" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Transferência Internacional</label>
                        <select
                            v-model="filters.transferencia_internacional"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todos</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
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
                            <th
                                @click="sortBy('nome_processo')"
                                class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider cursor-pointer hover:bg-slate-100"
                            >
                                Processo
                                <span v-if="filters.sort_by === 'nome_processo'">
                                    {{ filters.sort_order === 'asc' ? '↑' : '↓' }}
                                </span>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Departamento
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Base Legal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Categorias
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                                Transf. Int.
                            </th>
                            <th
                                @click="sortBy('created_at')"
                                class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider cursor-pointer hover:bg-slate-100"
                            >
                                Criado em
                                <span v-if="filters.sort_by === 'created_at'">
                                    {{ filters.sort_order === 'asc' ? '↑' : '↓' }}
                                </span>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-700 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-if="inventories.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="text-5xl mb-3">📋</div>
                                <div class="font-bold text-lg mb-1">Nenhum inventário encontrado</div>
                                <div class="text-sm">
                                    <Link :href="route('data-inventories.create')" class="text-indigo-600 hover:text-indigo-700 font-bold">
                                        Clique aqui
                                    </Link>
                                    para criar o primeiro inventário de dados
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="inventory in inventories.data"
                            :key="inventory.id"
                            class="hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <Link
                                    :href="route('data-inventories.show', inventory.id)"
                                    class="font-bold text-slate-900 hover:text-indigo-600"
                                >
                                    {{ inventory.nome_processo }}
                                </Link>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    {{ inventory.finalidade }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-700">{{ inventory.department?.name || '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-bold rounded-lg border"
                                    :class="baseLegalColors[inventory.base_legal] || 'bg-slate-100 text-slate-700'"
                                >
                                    {{ baseLegalLabels[inventory.base_legal] || inventory.base_legal }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-700">
                                    {{ inventory.categoria_dados?.length || 0 }}
                                    <span class="text-xs text-slate-500">categorias</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    v-if="inventory.transferencia_internacional"
                                    class="inline-block px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded border border-amber-200"
                                >
                                    🌍 Sim
                                </span>
                                <span v-else class="text-slate-400 text-sm">—</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700">
                                    {{ new Date(inventory.created_at).toLocaleDateString('pt-BR') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ new Date(inventory.created_at).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('data-inventories.show', inventory.id)"
                                        class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                        title="Visualizar"
                                    >
                                        👁️
                                    </Link>
                                    <Link
                                        :href="route('data-inventories.edit', inventory.id)"
                                        class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Editar"
                                    >
                                        ✏️
                                    </Link>
                                    <button
                                        @click="deleteInventory(inventory.id)"
                                        class="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Excluir"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="inventories.links.length > 3" class="px-6 py-4 border-t-2 border-slate-200 bg-slate-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Mostrando
                        <span class="font-bold text-slate-900">{{ inventories.from || 0 }}</span>
                        a
                        <span class="font-bold text-slate-900">{{ inventories.to || 0 }}</span>
                        de
                        <span class="font-bold text-slate-900">{{ inventories.total }}</span>
                        resultados
                    </div>
                    <div class="flex items-center gap-2">
                        <Link
                            v-for="(link, index) in inventories.links"
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
                            :disabled="!link.url"
                            preserve-scroll
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
