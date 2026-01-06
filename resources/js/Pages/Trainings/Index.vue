<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    trainings: Array
});

const showModal = ref(false);
const editMode = ref(false);
const selectedTraining = ref(null);

const form = useForm({
    titulo: '',
    descricao: '',
    obrigatorio: false,
    is_active: true
});

const openCreate = () => {
    form.reset();
    form.is_active = true;
    editMode.value = false;
    showModal.value = true;
};

const openEdit = (training) => {
    Object.keys(form.data()).forEach(key => {
        form[key] = training[key];
    });
    selectedTraining.value = training;
    editMode.value = true;
    showModal.value = true;
};

const submit = () => {
    if (editMode.value) {
        form.put(route('trainings.update', selectedTraining.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    } else {
        form.post(route('trainings.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    }
};

const deleteTraining = (training) => {
    if (confirm('Tem certeza que deseja remover este treinamento?')) {
        form.delete(route('trainings.destroy', training.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">📚 Treinamentos LGPD</h1>
                    <p class="text-slate-600">Gerencie capacitações sobre privacidade de dados</p>
                </div>
                <button
                    @click="openCreate"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                >
                    + Novo Treinamento
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div
                    v-for="training in trainings"
                    :key="training.id"
                    class="bg-white rounded-2xl border-2 border-slate-200 p-6 hover:shadow-lg transition"
                >
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-bold text-slate-900">{{ training.titulo }}</h3>
                        <div class="flex gap-2">
                            <span v-if="training.obrigatorio" class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg">
                                Obrigatório
                            </span>
                            <span :class="training.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'" class="px-2 py-1 text-xs font-bold rounded-lg">
                                {{ training.is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm mb-4">{{ training.descricao }}</p>
                    <div class="flex gap-2">
                        <button
                            @click="openEdit(training)"
                            class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-sm"
                        >
                            Editar
                        </button>
                        <button
                            @click="deleteTraining(training)"
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
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click.self="showModal = false"
            >
                <div class="bg-white rounded-2xl p-8 max-w-2xl w-full">
                    <h2 class="text-2xl font-black mb-6">{{ editMode ? 'Editar' : 'Novo' }} Treinamento</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Título</label>
                            <input v-model="form.titulo" type="text" required class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Descrição</label>
                            <textarea v-model="form.descricao" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg"></textarea>
                        </div>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input v-model="form.obrigatorio" type="checkbox" class="w-5 h-5 text-indigo-600 rounded" />
                                <span class="text-sm font-bold">Treinamento Obrigatório</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input v-model="form.is_active" type="checkbox" class="w-5 h-5 text-indigo-600 rounded" />
                                <span class="text-sm font-bold">Ativo</span>
                            </label>
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
