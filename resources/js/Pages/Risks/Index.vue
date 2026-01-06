<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    risks: Array
});

const showModal = ref(false);
const editMode = ref(false);
const selectedRisk = ref(null);

const form = useForm({
    titulo: '',
    descricao: '',
    nivel_risco: 'medio',
    status: 'identificado',
    plano_acao: '',
    responsavel: '',
    prazo: ''
});

const niveisRisco = [
    { value: 'baixo', label: 'Baixo', color: 'green' },
    { value: 'medio', label: 'Médio', color: 'yellow' },
    { value: 'alto', label: 'Alto', color: 'orange' },
    { value: 'critico', label: 'Crítico', color: 'red' }
];

const statusOptions = [
    { value: 'identificado', label: 'Identificado' },
    { value: 'em_analise', label: 'Em Análise' },
    { value: 'mitigado', label: 'Mitigado' },
    { value: 'resolvido', label: 'Resolvido' }
];

const getRiskColor = (nivel) => {
    const colors = {
        baixo: 'bg-green-100 text-green-700',
        medio: 'bg-yellow-100 text-yellow-700',
        alto: 'bg-orange-100 text-orange-700',
        critico: 'bg-red-100 text-red-700'
    };
    return colors[nivel];
};

const openCreate = () => {
    form.reset();
    editMode.value = false;
    showModal.value = true;
};

const openEdit = (risk) => {
    Object.keys(form.data()).forEach(key => {
        form[key] = risk[key];
    });
    selectedRisk.value = risk;
    editMode.value = true;
    showModal.value = true;
};

const submit = () => {
    if (editMode.value) {
        form.put(route('risks.update', selectedRisk.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    } else {
        form.post(route('risks.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    }
};

const deleteRisk = (risk) => {
    if (confirm('Tem certeza que deseja remover este risco?')) {
        form.delete(route('risks.destroy', risk.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">⚠️ Matriz de Riscos</h1>
                    <p class="text-slate-600">Gerencie riscos à privacidade de dados</p>
                </div>
                <button
                    @click="openCreate"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                >
                    + Novo Risco
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div
                    v-for="risk in risks"
                    :key="risk.id"
                    class="bg-white rounded-2xl border-2 border-slate-200 p-6 hover:shadow-lg transition"
                >
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900">{{ risk.titulo }}</h3>
                        <span :class="getRiskColor(risk.nivel_risco)" class="px-3 py-1 text-xs font-bold rounded-lg uppercase">
                            {{ risk.nivel_risco }}
                        </span>
                    </div>
                    <p class="text-slate-600 text-sm mb-4">{{ risk.descricao }}</p>
                    <div class="flex gap-2 mb-4">
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs rounded">
                            Status: {{ risk.status.replace('_', ' ') }}
                        </span>
                        <span v-if="risk.responsavel" class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                            {{ risk.responsavel }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="openEdit(risk)"
                            class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-sm"
                        >
                            Editar
                        </button>
                        <button
                            @click="deleteRisk(risk)"
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-bold text-sm"
                        >
                            Remover
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto"
                @click.self="showModal = false"
            >
                <div class="bg-white rounded-2xl p-8 max-w-2xl w-full my-8">
                    <h2 class="text-2xl font-black mb-6">{{ editMode ? 'Editar' : 'Novo' }} Risco</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Título</label>
                            <input v-model="form.titulo" type="text" required class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Descrição</label>
                            <textarea v-model="form.descricao" rows="3" required class="w-full px-4 py-2 border border-slate-300 rounded-lg"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Nível de Risco</label>
                                <select v-model="form.nivel_risco" required class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                                    <option v-for="nivel in niveisRisco" :key="nivel.value" :value="nivel.value">{{ nivel.label }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Status</label>
                                <select v-model="form.status" required class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Plano de Ação</label>
                            <textarea v-model="form.plano_acao" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Responsável</label>
                                <input v-model="form.responsavel" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Prazo</label>
                                <input v-model="form.prazo" type="date" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                        </div>
                        <div class="flex gap-2 mt-6">
                            <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-bold">Cancelar</button>
                            <button type="submit" :disabled="form.processing" class="flex-1 px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg font-bold">
                                {{ editMode ? 'Atualizar' : 'Criar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
