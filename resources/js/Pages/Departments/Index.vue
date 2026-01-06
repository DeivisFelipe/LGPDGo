<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    departments: Array
});

const showModal = ref(false);
const editMode = ref(false);
const selectedDepartment = ref(null);

const form = useForm({
    name: '',
    description: '',
    is_active: true
});

const openCreate = () => {
    form.reset();
    editMode.value = false;
    showModal.value = true;
};

const openEdit = (department) => {
    form.name = department.name;
    form.description = department.description;
    form.is_active = department.is_active;
    selectedDepartment.value = department;
    editMode.value = true;
    showModal.value = true;
};

const submit = () => {
    if (editMode.value) {
        form.put(route('departments.update', selectedDepartment.value.id), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('departments.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    }
};

const deleteDepartment = (department) => {
    if (confirm('Tem certeza que deseja remover este departamento?')) {
        form.delete(route('departments.destroy', department.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">🏛️ Departamentos</h1>
                    <p class="text-slate-600">Organize as áreas da sua empresa</p>
                </div>
                <button
                    @click="openCreate"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                >
                    + Novo Departamento
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="dept in departments"
                    :key="dept.id"
                    class="bg-white rounded-2xl border-2 border-slate-200 p-6 hover:shadow-lg transition"
                >
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900">{{ dept.name }}</h3>
                        <span
                            v-if="dept.is_active"
                            class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg"
                        >
                            Ativo
                        </span>
                    </div>
                    <p class="text-slate-600 text-sm mb-4">{{ dept.description || 'Sem descrição' }}</p>
                    <div class="text-sm text-slate-500 mb-4">
                        {{ dept.data_inventories_count }} inventário(s)
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="openEdit(dept)"
                            class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-sm"
                        >
                            Editar
                        </button>
                        <button
                            @click="deleteDepartment(dept)"
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
                <div class="bg-white rounded-2xl p-8 max-w-md w-full">
                    <h2 class="text-2xl font-black mb-6">
                        {{ editMode ? 'Editar' : 'Novo' }} Departamento
                    </h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Nome</label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Descrição</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                            ></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                id="is_active"
                                class="rounded"
                            />
                            <label for="is_active" class="text-sm font-bold">Departamento ativo</label>
                        </div>
                        <div class="flex gap-2 mt-6">
                            <button
                                type="button"
                                @click="showModal = false"
                                class="flex-1 px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg font-bold"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg font-bold"
                            >
                                {{ editMode ? 'Atualizar' : 'Criar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
