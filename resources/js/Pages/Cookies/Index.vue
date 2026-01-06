<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    cookies: Object,
    filters: Object,
    stats: Object,
});

const filters = ref({
    search: props.filters.search || '',
    categoria: props.filters.categoria || '',
    is_active: props.filters.is_active || '',
    sort_by: props.filters.sort_by || 'created_at',
    sort_order: props.filters.sort_order || 'desc',
});

const applyFilters = () => {
    router.get(route('cookies.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filters.value = {
        search: '',
        categoria: '',
        is_active: '',
        sort_by: 'created_at',
        sort_order: 'desc',
    };
    applyFilters();
};

const deleteCookie = (id) => {
    if (confirm('Tem certeza que deseja excluir este cookie?')) {
        router.delete(route('cookies.destroy', id), {
            preserveScroll: true,
        });
    }
};

const exportCookies = () => {
    window.location.href = route('cookies.export');
};

const categoriaLabels = {
    'necessarios': 'Necessários',
    'funcionais': 'Funcionais',
    'analytics': 'Analytics',
    'marketing': 'Marketing',
};

const categoriaColors = {
    'necessarios': 'bg-slate-100 text-slate-700 border-slate-300',
    'funcionais': 'bg-blue-100 text-blue-700 border-blue-300',
    'analytics': 'bg-purple-100 text-purple-700 border-purple-300',
    'marketing': 'bg-pink-100 text-pink-700 border-pink-300',
};

const categoriaIcons = {
    'necessarios': '🔒',
    'funcionais': '⚙️',
    'analytics': '📊',
    'marketing': '📣',
};
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Gestão de Cookies</h1>
                <p class="text-slate-600">Registre e categorize os cookies utilizados no site</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="exportCookies"
                    class="px-4 py-2 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                >
                    📥 Exportar
                </button>
                <Link
                    :href="route('cookies.create')"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                >
                    + Novo Cookie
                </Link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-2xl border-2 border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ stats.total }}</div>
                        <div class="text-sm font-medium text-slate-600 mt-1">Total de Cookies</div>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">🍪</div>
                </div>
            </div>

            <div
                v-for="(count, cat) in stats.by_categoria"
                :key="cat"
                class="bg-white rounded-2xl border-2 border-slate-200 p-6"
            >
                <div class="text-2xl font-black text-slate-900">{{ count }}</div>
                <div class="text-sm font-medium text-slate-600 mt-1">{{ categoriaLabels[cat] }}</div>
                <span class="inline-block mt-2 text-xl">{{ categoriaIcons[cat] }}</span>
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
                        placeholder="Buscar por nome, descrição, fornecedor..."
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

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <select
                            v-model="filters.categoria"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todas as categorias</option>
                            <option v-for="(label, value) in categoriaLabels" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filters.is_active"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        >
                            <option value="">Todos os status</option>
                            <option value="1">Ativos</option>
                            <option value="0">Inativos</option>
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
                                Cookie
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Categoria
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Fornecedor
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-700 uppercase tracking-wider">
                                Duração
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-700 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-if="cookies.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="text-5xl mb-3">🍪</div>
                                <div class="font-bold text-lg mb-1">Nenhum cookie encontrado</div>
                                <div class="text-sm">
                                    <Link :href="route('cookies.create')" class="text-indigo-600 hover:text-indigo-700 font-bold">
                                        Clique aqui
                                    </Link>
                                    para registrar o primeiro cookie
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="cookie in cookies.data"
                            :key="cookie.id"
                            class="hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <Link
                                    :href="route('cookies.show', cookie.id)"
                                    class="font-bold text-slate-900 hover:text-indigo-600"
                                >
                                    {{ cookie.nome }}
                                </Link>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    {{ cookie.descricao }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold rounded-lg border-2"
                                    :class="categoriaColors[cookie.categoria]"
                                >
                                    <span>{{ categoriaIcons[cookie.categoria] }}</span>
                                    {{ categoriaLabels[cookie.categoria] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-700">{{ cookie.fornecedor }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-700">{{ cookie.duracao }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-bold rounded-lg"
                                    :class="cookie.is_active
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700'"
                                >
                                    {{ cookie.is_active ? '✓ Ativo' : '✗ Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('cookies.show', cookie.id)"
                                        class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                        title="Visualizar"
                                    >
                                        👁️
                                    </Link>
                                    <Link
                                        :href="route('cookies.edit', cookie.id)"
                                        class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Editar"
                                    >
                                        ✏️
                                    </Link>
                                    <button
                                        @click="deleteCookie(cookie.id)"
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
            <div v-if="cookies.links.length > 3" class="px-6 py-4 border-t-2 border-slate-200 bg-slate-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Mostrando <span class="font-bold">{{ cookies.from || 0 }}</span>
                        a <span class="font-bold">{{ cookies.to || 0 }}</span>
                        de <span class="font-bold">{{ cookies.total }}</span> resultados
                    </div>
                    <div class="flex items-center gap-2">
                        <Link
                            v-for="(link, index) in cookies.links"
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
