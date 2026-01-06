<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// Layout público (sem auth)
defineOptions({ layout: undefined });

const page = usePage();
const companySlug = ref(new URL(window.location.href).searchParams.get('empresa') || '');

const form = useForm({
    company_slug: companySlug.value,
    nome_titular: '',
    email: '',
    telefone: '',
    cpf: '',
    tipo_solicitacao: '',
    descricao: '',
    preferencia_contato: 'email',
});

const submit = () => {
    form.post(route('dsar.store'));
};

const tiposSolicitacao = [
    {
        value: 'acesso',
        title: 'Acesso aos Dados',
        description: 'Solicitar cópia dos meus dados pessoais',
        icon: '📄',
        color: 'blue',
    },
    {
        value: 'retificacao',
        title: 'Retificação',
        description: 'Corrigir dados incorretos ou desatualizados',
        icon: '✏️',
        color: 'green',
    },
    {
        value: 'exclusao',
        title: 'Exclusão (Direito ao Esquecimento)',
        description: 'Remover meus dados do sistema',
        icon: '🗑️',
        color: 'red',
    },
    {
        value: 'portabilidade',
        title: 'Portabilidade',
        description: 'Transferir meus dados para outro fornecedor',
        icon: '📦',
        color: 'purple',
    },
    {
        value: 'oposicao',
        title: 'Oposição',
        description: 'Me opor a um tratamento de dados',
        icon: '🚫',
        color: 'amber',
    },
    {
        value: 'informacao',
        title: 'Informações',
        description: 'Saber como meus dados são usados',
        icon: 'ℹ️',
        color: 'slate',
    },
];

const colorClasses = {
    blue: 'border-blue-300 hover:bg-blue-50',
    green: 'border-green-300 hover:bg-green-50',
    red: 'border-red-300 hover:bg-red-50',
    purple: 'border-purple-300 hover:bg-purple-50',
    amber: 'border-amber-300 hover:bg-amber-50',
    slate: 'border-slate-300 hover:bg-slate-50',
};

const selectedColorClass = {
    blue: 'border-blue-500 bg-blue-50',
    green: 'border-green-500 bg-green-50',
    red: 'border-red-500 bg-red-50',
    purple: 'border-purple-500 bg-purple-50',
    amber: 'border-amber-500 bg-amber-50',
    slate: 'border-slate-500 bg-slate-50',
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
        <!-- Header -->
        <div class="bg-white border-b-2 border-slate-200 shadow-sm">
            <div class="max-w-4xl mx-auto px-6 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-200">
                        🔐
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">Portal LGPD</h1>
                        <p class="text-sm text-slate-600">Solicitação de Direitos do Titular</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-6 py-12">
            <!-- Intro -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8 mb-8 shadow-sm">
                <div class="text-center">
                    <div class="text-5xl mb-4">⚖️</div>
                    <h2 class="text-3xl font-black text-slate-900 mb-3">Exerça Seus Direitos LGPD</h2>
                    <p class="text-slate-600 leading-relaxed max-w-2xl mx-auto">
                        A Lei Geral de Proteção de Dados (LGPD) garante direitos sobre seus dados pessoais.
                        Preencha o formulário abaixo para fazer uma solicitação. Responderemos em até <strong>15 dias úteis</strong>.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Empresa -->
                <div v-if="!companySlug" class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-4">Empresa</h3>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Código da Empresa *</label>
                        <input
                            v-model="form.company_slug"
                            type="text"
                            placeholder="Ex: minhaempresa"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.company_slug }"
                        />
                        <p v-if="form.errors.company_slug" class="mt-1 text-sm text-red-600">{{ form.errors.company_slug }}</p>
                        <p class="mt-1 text-xs text-slate-500">Você recebeu este código por e-mail ou pelo site da empresa</p>
                    </div>
                </div>

                <!-- Tipo de Solicitação -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-4">Tipo de Solicitação *</h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div
                            v-for="tipo in tiposSolicitacao"
                            :key="tipo.value"
                            @click="form.tipo_solicitacao = tipo.value"
                            class="p-4 border-2 rounded-xl cursor-pointer transition-all"
                            :class="form.tipo_solicitacao === tipo.value
                                ? selectedColorClass[tipo.color]
                                : colorClasses[tipo.color]"
                        >
                            <div class="text-3xl mb-2">{{ tipo.icon }}</div>
                            <div class="font-bold text-slate-900 text-sm mb-1">{{ tipo.title }}</div>
                            <div class="text-xs text-slate-600">{{ tipo.description }}</div>
                        </div>
                    </div>
                    <p v-if="form.errors.tipo_solicitacao" class="mt-2 text-sm text-red-600">{{ form.errors.tipo_solicitacao }}</p>
                </div>

                <!-- Seus Dados -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Seus Dados</h3>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nome Completo *</label>
                            <input
                                v-model="form.nome_titular"
                                type="text"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.nome_titular }"
                            />
                            <p v-if="form.errors.nome_titular" class="mt-1 text-sm text-red-600">{{ form.errors.nome_titular }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">E-mail *</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.email }"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Telefone</label>
                            <input
                                v-model="form.telefone"
                                type="text"
                                placeholder="(11) 99999-9999"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.telefone }"
                            />
                            <p v-if="form.errors.telefone" class="mt-1 text-sm text-red-600">{{ form.errors.telefone }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">CPF</label>
                            <input
                                v-model="form.cpf"
                                type="text"
                                placeholder="000.000.000-00"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                                :class="{ 'border-red-300': form.errors.cpf }"
                            />
                            <p v-if="form.errors.cpf" class="mt-1 text-sm text-red-600">{{ form.errors.cpf }}</p>
                            <p class="mt-1 text-xs text-slate-500">Usado para validar sua identidade</p>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-4">Detalhe sua Solicitação *</h3>
                    <textarea
                        v-model="form.descricao"
                        rows="6"
                        placeholder="Descreva sua solicitação com o máximo de detalhes possível..."
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.descricao }"
                    ></textarea>
                    <p v-if="form.errors.descricao" class="mt-1 text-sm text-red-600">{{ form.errors.descricao }}</p>

                    <div class="mt-6">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Preferência de Contato *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    v-model="form.preferencia_contato"
                                    value="email"
                                    class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                />
                                <span class="text-sm font-medium text-slate-700">📧 E-mail</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    v-model="form.preferencia_contato"
                                    value="telefone"
                                    class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                />
                                <span class="text-sm font-medium text-slate-700">📞 Telefone</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl border-2 border-indigo-200 p-8">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="text-3xl">ℹ️</div>
                        <div class="flex-1">
                            <h4 class="font-black text-slate-900 mb-2">O que acontece agora?</h4>
                            <ul class="text-sm text-slate-600 space-y-1">
                                <li>✓ Você receberá um protocolo de acompanhamento</li>
                                <li>✓ Analisaremos sua solicitação em até 15 dias úteis</li>
                                <li>✓ Entraremos em contato pelo meio escolhido</li>
                                <li>✓ Seus dados serão tratados com total confidencialidade</li>
                            </ul>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-4 font-black text-lg rounded-xl transition-all shadow-lg"
                        :class="form.processing
                            ? 'bg-slate-300 text-slate-500 cursor-not-allowed'
                            : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200 hover:shadow-xl'"
                    >
                        {{ form.processing ? '📤 Enviando...' : '📨 Enviar Solicitação' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-white border-t-2 border-slate-200 py-6 mt-12">
            <div class="max-w-4xl mx-auto px-6 text-center text-sm text-slate-500">
                <p>🔒 Suas informações são protegidas pela Lei Geral de Proteção de Dados (LGPD - Lei 13.709/2018)</p>
            </div>
        </div>
    </div>
</template>

