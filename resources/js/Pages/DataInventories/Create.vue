<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LGPDFriendlyHelp from '@/Components/LGPDFriendlyHelp.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    departments: Array,
});

const currentStep = ref(1);
const totalSteps = 4;

const form = useForm({
    // Passo 1: Informações Básicas
    nome_processo: '',
    department_id: '',
    finalidade: '',
    
    // Passo 2: Dados e Base Legal
    base_legal: '',
    categoria_dados: [],
    
    // Passo 3: Compartilhamento
    origem_dados: [],
    destinatarios_dados: [],
    quem_acessa: [],
    transferencia_internacional: false,
    pais_destino: '',
    
    // Passo 4: Segurança e Retenção
    medidas_seguranca: '',
    tempo_retencao: '',
});

// Opções pré-definidas
const baseLegalOptions = [
    { value: 'consentimento', label: 'Consentimento', description: 'O titular autorizou o uso' },
    { value: 'contrato', label: 'Execução de Contrato', description: 'Necessário para cumprir contrato' },
    { value: 'obrigacao_legal', label: 'Obrigação Legal', description: 'Exigido por lei' },
    { value: 'legitimo_interesse', label: 'Legítimo Interesse', description: 'Interesse legítimo documentado' },
    { value: 'protecao_da_vida', label: 'Proteção da Vida', description: 'Saúde ou segurança do titular' },
    { value: 'exercicio_regular_direitos', label: 'Exercício Regular de Direitos', description: 'Em processos judiciais/administrativos' },
];

const categoriaDadosOptions = [
    'Dados Cadastrais (nome, e-mail, telefone)',
    'CPF/CNPJ',
    'RG/Identidade',
    'Endereço',
    'Data de Nascimento',
    'Dados Bancários',
    'Dados de Saúde',
    'Dados Biométricos (digital, face)',
    'Dados de Localização',
    'Dados de Navegação/Cookies',
    'Histórico de Compras',
    'Preferências e Comportamento',
];

const origemDadosOptions = [
    'Diretamente do titular (formulários, cadastros)',
    'Fontes públicas (Receita Federal, Cartórios)',
    'Parceiros comerciais',
    'Redes sociais',
    'Cookies e tecnologias de rastreamento',
    'Terceiros (bureaus de crédito, fornecedores)',
];

const medidasSegurancaExamples = [
    'Criptografia em trânsito e repouso',
    'Controle de acesso baseado em função (RBAC)',
    'Logs de auditoria',
    'Autenticação multifator (MFA)',
    'Backups regulares',
    'Firewalls e antivírus',
    'Treinamento de equipe',
    'Políticas de senha forte',
];

const progress = computed(() => (currentStep.value / totalSteps) * 100);

const canGoNext = computed(() => {
    switch (currentStep.value) {
        case 1:
            return form.nome_processo && form.department_id && form.finalidade;
        case 2:
            return form.base_legal && form.categoria_dados.length > 0;
        case 3:
            return true; // Campos opcionais
        case 4:
            return form.medidas_seguranca && form.tempo_retencao;
        default:
            return false;
    }
});

const nextStep = () => {
    if (currentStep.value < totalSteps && canGoNext.value) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submit = () => {
    form.post(route('data-inventories.store'), {
        onSuccess: () => {
            // Redireciona para a visualização
        },
    });
};

// Adicionar/remover itens de arrays
const addCustomCategoria = ref('');
const addCategoria = () => {
    if (addCustomCategoria.value && !form.categoria_dados.includes(addCustomCategoria.value)) {
        form.categoria_dados.push(addCustomCategoria.value);
        addCustomCategoria.value = '';
    }
};

const removeCategoria = (index) => {
    form.categoria_dados.splice(index, 1);
};

const addCustomOrigem = ref('');
const addOrigem = () => {
    if (addCustomOrigem.value && !form.origem_dados.includes(addCustomOrigem.value)) {
        form.origem_dados.push(addCustomOrigem.value);
        addCustomOrigem.value = '';
    }
};

const addCustomDestinatario = ref('');
const addDestinatario = () => {
    if (addCustomDestinatario.value && !form.destinatarios_dados.includes(addCustomDestinatario.value)) {
        form.destinatarios_dados.push(addCustomDestinatario.value);
        addCustomDestinatario.value = '';
    }
};

const addCustomAcesso = ref('');
const addAcesso = () => {
    if (addCustomAcesso.value && !form.quem_acessa.includes(addCustomAcesso.value)) {
        form.quem_acessa.push(addCustomAcesso.value);
        addCustomAcesso.value = '';
    }
};
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 mb-2">Criar Inventário de Dados (ROPA)</h1>
            <p class="text-slate-600">Wizard guiado para documentar o tratamento de dados pessoais</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-slate-600">Passo {{ currentStep }} de {{ totalSteps }}</span>
                <span class="text-sm font-bold text-indigo-600">{{ Math.round(progress) }}% completo</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-3">
                <div 
                    class="bg-gradient-to-r from-indigo-600 to-blue-500 h-3 rounded-full transition-all duration-500"
                    :style="{ width: progress + '%' }"
                ></div>
            </div>
            
            <!-- Step Indicators -->
            <div class="flex justify-between mt-4">
                <div 
                    v-for="step in totalSteps"
                    :key="step"
                    class="flex flex-col items-center"
                    :class="{ 'opacity-50': currentStep < step }"
                >
                    <div 
                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all"
                        :class="currentStep >= step ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-400'"
                    >
                        <span v-if="currentStep > step">✓</span>
                        <span v-else>{{ step }}</span>
                    </div>
                    <span class="text-xs font-medium text-slate-600 mt-2 text-center">
                        {{ step === 1 ? 'Básico' : step === 2 ? 'Dados' : step === 3 ? 'Compartilhamento' : 'Segurança' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Wizard Steps -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 shadow-sm p-8 mb-8">
            <!-- Passo 1: Informações Básicas -->
            <div v-show="currentStep === 1" class="space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-2xl">📋</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Informações Básicas</h3>
                        <p class="text-sm text-slate-600">Identifique o processo de tratamento de dados</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <label class="block text-sm font-bold text-slate-700">Nome do Processo *</label>
                        <LGPDFriendlyHelp topic="ropa" position="inline" />
                    </div>
                    <input
                        v-model="form.nome_processo"
                        type="text"
                        placeholder="Ex: Folha de Pagamento, Newsletter Marketing, Vendas E-commerce"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.nome_processo }"
                    />
                    <p v-if="form.errors.nome_processo" class="mt-1 text-sm text-red-600">{{ form.errors.nome_processo }}</p>
                    <p class="mt-1 text-xs text-slate-500">Seja específico: descreva a atividade que usa dados pessoais</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Departamento Responsável *</label>
                    <select
                        v-model="form.department_id"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.department_id }"
                    >
                        <option value="">Selecione o departamento</option>
                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.department_id" class="mt-1 text-sm text-red-600">{{ form.errors.department_id }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Finalidade do Tratamento *</label>
                    <textarea
                        v-model="form.finalidade"
                        rows="4"
                        placeholder="Descreva PARA QUÊ você usa esses dados. Ex: Processar pagamento de salários e benefícios dos colaboradores conforme legislação trabalhista"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.finalidade }"
                    ></textarea>
                    <p v-if="form.errors.finalidade" class="mt-1 text-sm text-red-600">{{ form.errors.finalidade }}</p>
                    <p class="mt-1 text-xs text-slate-500">💡 Dica: Seja claro e específico sobre o objetivo do uso dos dados</p>
                </div>
            </div>

            <!-- Passo 2: Dados e Base Legal -->
            <div v-show="currentStep === 2" class="space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-2xl">⚖️</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Dados e Base Legal</h3>
                        <p class="text-sm text-slate-600">Defina quais dados você coleta e por qual justificativa</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <label class="block text-sm font-bold text-slate-700">Base Legal *</label>
                        <LGPDFriendlyHelp topic="base-legal" position="inline" />
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div
                            v-for="option in baseLegalOptions"
                            :key="option.value"
                            @click="form.base_legal = option.value"
                            class="p-4 border-2 rounded-xl cursor-pointer transition-all hover:shadow-md"
                            :class="form.base_legal === option.value 
                                ? 'border-indigo-500 bg-indigo-50' 
                                : 'border-slate-200 hover:border-indigo-200'"
                        >
                            <div class="flex items-start gap-3">
                                <div 
                                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-0.5"
                                    :class="form.base_legal === option.value 
                                        ? 'border-indigo-600 bg-indigo-600' 
                                        : 'border-slate-300'"
                                >
                                    <div v-if="form.base_legal === option.value" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ option.label }}</div>
                                    <div class="text-xs text-slate-600 mt-0.5">{{ option.description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-if="form.errors.base_legal" class="mt-2 text-sm text-red-600">{{ form.errors.base_legal }}</p>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <label class="block text-sm font-bold text-slate-700">Categoria de Dados *</label>
                        <LGPDFriendlyHelp topic="dados-sensiveis" position="inline" />
                    </div>
                    
                    <!-- Opções pré-definidas -->
                    <div class="mb-3 flex flex-wrap gap-2">
                        <button
                            v-for="(categoria, index) in categoriaDadosOptions"
                            :key="index"
                            @click="form.categoria_dados.includes(categoria) 
                                ? form.categoria_dados = form.categoria_dados.filter(c => c !== categoria)
                                : form.categoria_dados.push(categoria)"
                            type="button"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-all"
                            :class="form.categoria_dados.includes(categoria)
                                ? 'bg-indigo-100 text-indigo-700 border-2 border-indigo-300'
                                : 'bg-slate-100 text-slate-600 border-2 border-slate-200 hover:border-indigo-200'"
                        >
                            {{ categoria }}
                        </button>
                    </div>

                    <!-- Adicionar customizado -->
                    <div class="flex gap-2">
                        <input
                            v-model="addCustomCategoria"
                            @keyup.enter="addCategoria"
                            type="text"
                            placeholder="Adicionar categoria personalizada"
                            class="flex-1 px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        />
                        <button
                            @click="addCategoria"
                            type="button"
                            class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                        >
                            Adicionar
                        </button>
                    </div>

                    <!-- Selecionados -->
                    <div v-if="form.categoria_dados.length > 0" class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Dados Selecionados:</div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="(categoria, index) in form.categoria_dados"
                                :key="index"
                                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium"
                            >
                                {{ categoria }}
                                <button @click="removeCategoria(index)" type="button" class="hover:text-indigo-900">×</button>
                            </span>
                        </div>
                    </div>
                    <p v-if="form.errors.categoria_dados" class="mt-2 text-sm text-red-600">{{ form.errors.categoria_dados }}</p>
                </div>
            </div>

            <!-- Passo 3: Compartilhamento -->
            <div v-show="currentStep === 3" class="space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-2xl">🔄</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Compartilhamento de Dados</h3>
                        <p class="text-sm text-slate-600">Documente origem, destinatários e acessos</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Origem dos Dados</label>
                    <div class="space-y-2">
                        <label
                            v-for="(origem, index) in origemDadosOptions"
                            :key="index"
                            class="flex items-center gap-3 p-3 border-2 border-slate-200 rounded-xl hover:border-indigo-200 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                :value="origem"
                                v-model="form.origem_dados"
                                class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
                            />
                            <span class="text-sm text-slate-700">{{ origem }}</span>
                        </label>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <input
                            v-model="addCustomOrigem"
                            @keyup.enter="addOrigem"
                            type="text"
                            placeholder="Adicionar origem personalizada"
                            class="flex-1 px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        />
                        <button
                            @click="addOrigem"
                            type="button"
                            class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                        >
                            +
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Destinatários dos Dados</label>
                    <p class="text-xs text-slate-500 mb-3">Com quem você compartilha esses dados? (Terceiros, parceiros, fornecedores)</p>
                    <div class="flex gap-2 mb-2">
                        <input
                            v-model="addCustomDestinatario"
                            @keyup.enter="addDestinatario"
                            type="text"
                            placeholder="Ex: Empresa de folha de pagamento, Google Analytics, Correios"
                            class="flex-1 px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        />
                        <button
                            @click="addDestinatario"
                            type="button"
                            class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                        >
                            Adicionar
                        </button>
                    </div>
                    <div v-if="form.destinatarios_dados.length > 0" class="space-y-2">
                        <div
                            v-for="(dest, index) in form.destinatarios_dados"
                            :key="index"
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200"
                        >
                            <span class="text-sm font-medium text-slate-700">{{ dest }}</span>
                            <button
                                @click="form.destinatarios_dados.splice(index, 1)"
                                type="button"
                                class="text-red-600 hover:text-red-800 font-bold"
                            >
                                Remover
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Quem Acessa Internamente</label>
                    <p class="text-xs text-slate-500 mb-3">Quais departamentos ou funções da sua empresa acessam esses dados?</p>
                    <div class="flex gap-2 mb-2">
                        <input
                            v-model="addCustomAcesso"
                            @keyup.enter="addAcesso"
                            type="text"
                            placeholder="Ex: RH, TI, Financeiro, Gerentes de Vendas"
                            class="flex-1 px-4 py-2 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        />
                        <button
                            @click="addAcesso"
                            type="button"
                            class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                        >
                            Adicionar
                        </button>
                    </div>
                    <div v-if="form.quem_acessa.length > 0" class="flex flex-wrap gap-2 mt-2">
                        <span
                            v-for="(acesso, index) in form.quem_acessa"
                            :key="index"
                            class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium"
                        >
                            {{ acesso }}
                            <button @click="form.quem_acessa.splice(index, 1)" type="button" class="hover:text-blue-900">×</button>
                        </span>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 border-2 border-amber-200 rounded-xl">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="form.transferencia_internacional"
                            class="w-5 h-5 text-amber-600 border-amber-300 rounded focus:ring-amber-500 mt-0.5"
                        />
                        <div>
                            <div class="font-bold text-slate-900">Transferência Internacional de Dados</div>
                            <div class="text-sm text-slate-600 mt-1">Os dados são enviados para fora do Brasil?</div>
                        </div>
                    </label>
                    
                    <div v-if="form.transferencia_internacional" class="mt-3">
                        <input
                            v-model="form.pais_destino"
                            type="text"
                            placeholder="Digite o(s) país(es) de destino"
                            class="w-full px-4 py-2 border-2 border-amber-300 rounded-xl focus:border-amber-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.pais_destino }"
                        />
                        <p v-if="form.errors.pais_destino" class="mt-1 text-sm text-red-600">{{ form.errors.pais_destino }}</p>
                    </div>
                </div>
            </div>

            <!-- Passo 4: Segurança e Retenção -->
            <div v-show="currentStep === 4" class="space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-2xl">🔒</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Segurança e Retenção</h3>
                        <p class="text-sm text-slate-600">Como você protege e por quanto tempo guarda os dados</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Medidas de Segurança *</label>
                    <textarea
                        v-model="form.medidas_seguranca"
                        rows="5"
                        placeholder="Descreva as medidas técnicas e organizacionais..."
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.medidas_seguranca }"
                    ></textarea>
                    <p v-if="form.errors.medidas_seguranca" class="mt-1 text-sm text-red-600">{{ form.errors.medidas_seguranca }}</p>
                    
                    <!-- Exemplos -->
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="text-xs font-bold text-blue-900 uppercase mb-2">💡 Exemplos de Medidas:</div>
                        <div class="grid sm:grid-cols-2 gap-2">
                            <button
                                v-for="(medida, index) in medidasSegurancaExamples"
                                :key="index"
                                @click="form.medidas_seguranca += (form.medidas_seguranca ? '\n' : '') + '- ' + medida"
                                type="button"
                                class="text-left px-3 py-2 bg-white border border-blue-200 rounded-lg text-xs text-slate-700 hover:bg-blue-50 transition-colors"
                            >
                                {{ medida }}
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tempo de Retenção *</label>
                    <input
                        v-model="form.tempo_retencao"
                        type="text"
                        placeholder="Ex: 5 anos após término do contrato, Enquanto conta ativa + 6 meses"
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                        :class="{ 'border-red-300': form.errors.tempo_retencao }"
                    />
                    <p v-if="form.errors.tempo_retencao" class="mt-1 text-sm text-red-600">{{ form.errors.tempo_retencao }}</p>
                    <p class="mt-1 text-xs text-slate-500">💡 Seja específico sobre quando e como os dados são excluídos</p>
                </div>

                <!-- Resumo Final -->
                <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl">
                    <div class="text-center mb-4">
                        <div class="text-4xl mb-2">✅</div>
                        <h4 class="text-lg font-black text-slate-900">Pronto para Finalizar!</h4>
                        <p class="text-sm text-slate-600">Revise as informações e salve o inventário</p>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span class="font-medium text-slate-700">Processo: <strong>{{ form.nome_processo || '-' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span class="font-medium text-slate-700">Base Legal: <strong>{{ form.base_legal || '-' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span class="font-medium text-slate-700">Categorias: <strong>{{ form.categoria_dados.length }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span class="font-medium text-slate-700">Transferência Int.: <strong>{{ form.transferencia_internacional ? 'Sim' : 'Não' }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between">
            <button
                @click="prevStep"
                v-if="currentStep > 1"
                type="button"
                class="px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
            >
                ← Voltar
            </button>
            <div v-else></div>

            <div class="flex items-center gap-3">
                <button
                    v-if="currentStep < totalSteps"
                    @click="nextStep"
                    :disabled="!canGoNext"
                    type="button"
                    class="px-6 py-3 font-bold rounded-xl transition-colors"
                    :class="canGoNext 
                        ? 'bg-indigo-600 text-white hover:bg-indigo-700' 
                        : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                >
                    Próximo →
                </button>
                
                <button
                    v-else
                    @click="submit"
                    :disabled="form.processing || !canGoNext"
                    type="button"
                    class="px-8 py-3 font-bold rounded-xl transition-colors shadow-lg"
                    :class="form.processing || !canGoNext
                        ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                        : 'bg-green-600 text-white hover:bg-green-700 shadow-green-200'"
                >
                    {{ form.processing ? 'Salvando...' : '✓ Salvar Inventário' }}
                </button>
            </div>
        </div>
    </div>
</template>
