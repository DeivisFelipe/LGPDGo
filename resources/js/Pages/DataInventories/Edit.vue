<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LGPDFriendlyHelp from '@/Components/LGPDFriendlyHelp.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    inventory: Object,
    departments: Array,
});

const form = useForm({
    nome_processo: props.inventory.nome_processo,
    department_id: props.inventory.department_id,
    finalidade: props.inventory.finalidade,
    base_legal: props.inventory.base_legal,
    categoria_dados: props.inventory.categoria_dados || [],
    origem_dados: props.inventory.origem_dados || [],
    destinatarios_dados: props.inventory.destinatarios_dados || [],
    quem_acessa: props.inventory.quem_acessa || [],
    transferencia_internacional: props.inventory.transferencia_internacional || false,
    pais_destino: props.inventory.pais_destino || '',
    medidas_seguranca: props.inventory.medidas_seguranca,
    tempo_retencao: props.inventory.tempo_retencao,
});

const submit = () => {
    form.put(route('data-inventories.update', props.inventory.id));
};

const baseLegalOptions = [
    { value: 'consentimento', label: 'Consentimento', description: 'O titular autorizou o uso' },
    { value: 'contrato', label: 'Execução de Contrato', description: 'Necessário para cumprir contrato' },
    { value: 'obrigacao_legal', label: 'Obrigação Legal', description: 'Exigido por lei' },
    { value: 'legitimo_interesse', label: 'Legítimo Interesse', description: 'Interesse legítimo documentado' },
    { value: 'protecao_da_vida', label: 'Proteção da Vida', description: 'Saúde ou segurança do titular' },
    { value: 'exercicio_regular_direitos', label: 'Exercício Regular de Direitos', description: 'Em processos judiciais/administrativos' },
];
</script>

<template>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Link
                        :href="route('data-inventories.show', inventory.id)"
                        class="text-slate-600 hover:text-slate-900 transition-colors"
                    >
                        ← Voltar
                    </Link>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Editar ROPA</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900">{{ inventory.nome_processo }}</h1>
                <p class="text-sm text-slate-500 mt-1">UUID: {{ inventory.uuid }}</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Informações Básicas -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">📋</div>
                    <h3 class="text-xl font-black text-slate-900">Informações Básicas</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <label class="block text-sm font-bold text-slate-700">Nome do Processo *</label>
                            <LGPDFriendlyHelp topic="ropa" position="inline" />
                        </div>
                        <input
                            v-model="form.nome_processo"
                            type="text"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.nome_processo }"
                        />
                        <p v-if="form.errors.nome_processo" class="mt-1 text-sm text-red-600">{{ form.errors.nome_processo }}</p>
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
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.finalidade }"
                        ></textarea>
                        <p v-if="form.errors.finalidade" class="mt-1 text-sm text-red-600">{{ form.errors.finalidade }}</p>
                    </div>
                </div>
            </div>

            <!-- Base Legal e Dados -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">⚖️</div>
                    <h3 class="text-xl font-black text-slate-900">Base Legal e Dados</h3>
                </div>

                <div class="space-y-6">
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
                                class="p-4 border-2 rounded-xl cursor-pointer transition-all"
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
                        <div class="flex items-center gap-2 mb-2">
                            <label class="block text-sm font-bold text-slate-700">Categorias de Dados *</label>
                            <LGPDFriendlyHelp topic="dados-sensiveis" position="inline" />
                        </div>
                        <textarea
                            v-model="form.categoria_dados"
                            rows="3"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.categoria_dados }"
                            placeholder="Ex: Nome, CPF, E-mail, Telefone"
                        ></textarea>
                        <p v-if="form.errors.categoria_dados" class="mt-1 text-sm text-red-600">{{ form.errors.categoria_dados }}</p>
                        <p class="mt-1 text-xs text-slate-500">Separe múltiplas categorias por vírgula</p>
                    </div>
                </div>
            </div>

            <!-- Compartilhamento -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🔄</div>
                    <h3 class="text-xl font-black text-slate-900">Compartilhamento</h3>
                </div>

                <div class="space-y-6">
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
            </div>

            <!-- Segurança e Retenção -->
            <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-xl">🔒</div>
                    <h3 class="text-xl font-black text-slate-900">Segurança e Retenção</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Medidas de Segurança *</label>
                        <textarea
                            v-model="form.medidas_seguranca"
                            rows="5"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.medidas_seguranca }"
                        ></textarea>
                        <p v-if="form.errors.medidas_seguranca" class="mt-1 text-sm text-red-600">{{ form.errors.medidas_seguranca }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tempo de Retenção *</label>
                        <input
                            v-model="form.tempo_retencao"
                            type="text"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                            :class="{ 'border-red-300': form.errors.tempo_retencao }"
                        />
                        <p v-if="form.errors.tempo_retencao" class="mt-1 text-sm text-red-600">{{ form.errors.tempo_retencao }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('data-inventories.show', inventory.id)"
                    class="px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors"
                >
                    Cancelar
                </Link>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-8 py-3 font-bold rounded-xl transition-colors shadow-lg"
                    :class="form.processing
                        ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                        : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200'"
                >
                    {{ form.processing ? 'Salvando...' : '✓ Salvar Alterações' }}
                </button>
            </div>
        </form>
    </div>
</template>
