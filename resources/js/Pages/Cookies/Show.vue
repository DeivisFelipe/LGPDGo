<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    cookie: Object,
});

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

const tipoLabels = {
    'first-party': '🏠 First-Party',
    'third-party': '🌐 Third-Party',
};
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Link
                        :href="route('cookies.index')"
                        class="text-slate-600 hover:text-slate-900 transition-colors"
                    >
                        ← Voltar
                    </Link>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cookie</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900">{{ cookie.nome }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-4 py-2 text-sm font-bold rounded-xl"
                    :class="cookie.is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'"
                >
                    {{ cookie.is_active ? '✓ Ativo' : '✗ Inativo' }}
                </span>
                <Link
                    :href="route('cookies.edit', cookie.id)"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                >
                    ✏️ Editar
                </Link>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border-2 border-indigo-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-2xl text-white">
                        🍪
                    </div>
                    <div>
                        <div class="text-lg font-black text-slate-900">Cookie Registrado</div>
                        <div class="text-sm text-slate-600">Criado em {{ new Date(cookie.created_at).toLocaleString('pt-BR') }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Fornecedor</div>
                    <div class="text-lg font-black text-slate-900">{{ cookie.fornecedor }}</div>
                </div>
            </div>
        </div>

        <!-- Grid de Informações -->
        <div class="grid lg:grid-cols-2 gap-8 mb-8">
            <!-- Classificação -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🏷️</div>
                    <h3 class="text-xl font-black text-slate-900">Classificação</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Categoria</div>
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold border-2 text-lg"
                            :class="categoriaColors[cookie.categoria]"
                        >
                            <span class="text-2xl">{{ categoriaIcons[cookie.categoria] }}</span>
                            {{ categoriaLabels[cookie.categoria] }}
                        </span>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipo</div>
                        <div class="text-lg font-bold text-slate-900">{{ tipoLabels[cookie.tipo_cookie] }}</div>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Duração</div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">⏱️</span>
                            <span class="text-lg font-bold text-slate-900">{{ cookie.duracao }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">📝</div>
                    <h3 class="text-xl font-black text-slate-900">Descrição</h3>
                </div>

                <p class="text-slate-700 leading-relaxed">{{ cookie.descricao }}</p>
            </div>
        </div>

        <!-- Finalidade -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-xl">🎯</div>
                <h3 class="text-xl font-black text-slate-900">Finalidade</h3>
            </div>

            <div class="prose prose-sm max-w-none">
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ cookie.finalidade }}</p>
            </div>
        </div>

        <!-- Informações Técnicas -->
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-3xl border-2 border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-slate-700 rounded-xl flex items-center justify-center text-xl text-white">⚙️</div>
                <h3 class="text-xl font-black text-slate-900">Informações Técnicas</h3>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nome Técnico</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <code class="text-sm font-mono text-slate-900">{{ cookie.nome }}</code>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipo de Cookie</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200 text-sm font-medium text-slate-700">
                        {{ cookie.tipo_cookie === 'first-party' ? 'Próprio (First-Party)' : 'Terceiro (Third-Party)' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <span
                            class="inline-block px-3 py-1 text-xs font-bold rounded-lg"
                            :class="cookie.is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700'"
                        >
                            {{ cookie.is_active ? '✓ Ativo' : '✗ Inativo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadados -->
        <div class="mt-8 p-6 bg-slate-50 border-2 border-slate-200 rounded-2xl">
            <div class="grid sm:grid-cols-3 gap-6 text-sm">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Criado em</div>
                    <div class="text-slate-900 font-medium">{{ new Date(cookie.created_at).toLocaleString('pt-BR') }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Última Atualização</div>
                    <div class="text-slate-900 font-medium">{{ new Date(cookie.updated_at).toLocaleString('pt-BR') }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">ID</div>
                    <div class="text-slate-900 font-medium">#{{ cookie.id }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
