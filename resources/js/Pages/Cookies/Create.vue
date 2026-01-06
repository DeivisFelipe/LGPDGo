<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LGPDFriendlyHelp from '@/Components/LGPDFriendlyHelp.vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const form = useForm({
    nome: '',
    descricao: '',
    categoria: 'necessarios',
    fornecedor: '',
    duracao: '',
    finalidade: '',
    tipo_cookie: 'first-party',
    is_active: true,
});

const submit = () => {
    form.post(route('cookies.store'));
};

const categorias = [
    {
        value: 'necessarios',
        label: 'Necessários',
        description: 'Essenciais para o funcionamento do site',
        icon: '🔒',
        examples: 'Cookies de sessão, autenticação, carrinho de compras',
    },
    {
        value: 'funcionais',
        label: 'Funcionais',
        description: 'Melhoram a experiência do usuário',
        icon: '⚙️',
        examples: 'Preferências de idioma, tema escuro/claro, configurações',
    },
    {
        value: 'analytics',
        label: 'Analytics',
        description: 'Rastreiam uso e comportamento',
        icon: '📊',
        examples: 'Google Analytics, Hotjar, métricas de acesso',
    },
    {
        value: 'marketing',
        label: 'Marketing',
        description: 'Publicidade e remarketing',
        icon: '📣',
        examples: 'Google Ads, Facebook Pixel, cookies de rastreamento',
    },
];

const duracaoExamples = [
    'Sessão (até fechar o navegador)',
    '1 hora',
    '24 horas',
    '7 dias',
    '30 dias',
    '1 ano',
    '2 anos',
    'Persistente',
];
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 mb-2">Registrar Novo Cookie</h1>
            <p class="text-slate-600">Adicione um cookie ao inventário de rastreamento</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">🍪</div>
                    <h3 class="text-xl font-black text-slate-900">Informações Básicas</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nome do Cookie *</label>
                        <input
                            v-model="form.nome"
                            type="text"
                            placeholder="Ex: _ga, session_id, user_preferences"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.nome }"
                        />
                        <p v-if="form.errors.nome" class="mt-1 text-sm text-red-600">{{ form.errors.nome }}</p>
                        <p class="mt-1 text-xs text-slate-500">Nome exato como aparece no navegador</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Descrição *</label>
                        <textarea
                            v-model="form.descricao"
                            rows="3"
                            placeholder="Descreva o que este cookie faz..."
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.descricao }"
                        ></textarea>
                        <p v-if="form.errors.descricao" class="mt-1 text-sm text-red-600">{{ form.errors.descricao }}</p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Fornecedor *</label>
                            <input
                                v-model="form.fornecedor"
                                type="text"
                                placeholder="Ex: Google, Facebook, Próprio"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.fornecedor }"
                            />
                            <p v-if="form.errors.fornecedor" class="mt-1 text-sm text-red-600">{{ form.errors.fornecedor }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Duração *</label>
                            <input
                                v-model="form.duracao"
                                type="text"
                                placeholder="Ex: 24 horas, 1 ano, Sessão"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.duracao }"
                                list="duracao-options"
                            />
                            <datalist id="duracao-options">
                                <option v-for="(ex, i) in duracaoExamples" :key="i" :value="ex" />
                            </datalist>
                            <p v-if="form.errors.duracao" class="mt-1 text-sm text-red-600">{{ form.errors.duracao }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categoria e Classificação -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🏷️</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-black text-slate-900">Categoria e Classificação</h3>
                    </div>
                    <LGPDFriendlyHelp topic="cookies" position="inline" />
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Categoria *</label>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div
                                v-for="cat in categorias"
                                :key="cat.value"
                                @click="form.categoria = cat.value"
                                class="p-4 border-2 rounded-xl cursor-pointer transition-all"
                                :class="form.categoria === cat.value
                                    ? 'border-indigo-500 bg-indigo-50'
                                    : 'border-slate-200 hover:border-indigo-200'"
                            >
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">{{ cat.icon }}</div>
                                    <div class="flex-1">
                                        <div class="font-bold text-slate-900 mb-1">{{ cat.label }}</div>
                                        <div class="text-xs text-slate-600 mb-2">{{ cat.description }}</div>
                                        <div class="text-xs text-slate-500 italic">Ex: {{ cat.examples }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.categoria" class="mt-2 text-sm text-red-600">{{ form.errors.categoria }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Tipo de Cookie *</label>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div
                                @click="form.tipo_cookie = 'first-party'"
                                class="p-4 border-2 rounded-xl cursor-pointer transition-all"
                                :class="form.tipo_cookie === 'first-party'
                                    ? 'border-green-500 bg-green-50'
                                    : 'border-slate-200 hover:border-green-200'"
                            >
                                <div class="font-bold text-slate-900 mb-1">🏠 First-Party</div>
                                <div class="text-xs text-slate-600">Definido pelo próprio site</div>
                            </div>
                            <div
                                @click="form.tipo_cookie = 'third-party'"
                                class="p-4 border-2 rounded-xl cursor-pointer transition-all"
                                :class="form.tipo_cookie === 'third-party'
                                    ? 'border-amber-500 bg-amber-50'
                                    : 'border-slate-200 hover:border-amber-200'"
                            >
                                <div class="font-bold text-slate-900 mb-1">🌐 Third-Party</div>
                                <div class="text-xs text-slate-600">Definido por domínio externo</div>
                            </div>
                        </div>
                        <p v-if="form.errors.tipo_cookie" class="mt-2 text-sm text-red-600">{{ form.errors.tipo_cookie }}</p>
                    </div>
                </div>
            </div>

            <!-- Finalidade -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🎯</div>
                    <h3 class="text-xl font-black text-slate-900">Finalidade</h3>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Para que serve este cookie? *</label>
                    <textarea
                        v-model="form.finalidade"
                        rows="4"
                        placeholder="Ex: Rastrear sessões de usuários logados para manter autenticação ativa"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.finalidade }"
                    ></textarea>
                    <p v-if="form.errors.finalidade" class="mt-1 text-sm text-red-600">{{ form.errors.finalidade }}</p>
                    <p class="mt-1 text-xs text-slate-500">💡 Seja específico: explique o objetivo e impacto na experiência do usuário</p>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900 mb-1">Cookie Ativo?</div>
                        <div class="text-sm text-slate-600">Desative se o cookie não estiver mais em uso</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="form.is_active"
                            class="sr-only peer"
                        />
                        <div
                            class="w-14 h-8 rounded-full transition-all"
                            :class="form.is_active ? 'bg-green-600' : 'bg-slate-300'"
                        >
                            <div
                                class="absolute top-1 left-1 bg-white w-6 h-6 rounded-full transition-transform"
                                :class="{ 'translate-x-6': form.is_active }"
                            ></div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a
                    :href="route('cookies.index')"
                    class="px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-8 py-3 font-bold rounded-xl transition-colors shadow-lg"
                    :class="form.processing
                        ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                        : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200'"
                >
                    {{ form.processing ? 'Salvando...' : '✓ Salvar Cookie' }}
                </button>
            </div>
        </form>
    </div>
</template>
