<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    users: Array
});

const showModal = ref(false);
const editMode = ref(false);
const selectedUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const openCreate = () => {
    form.reset();
    editMode.value = false;
    showModal.value = true;
};

const openEdit = (user) => {
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.password_confirmation = '';
    selectedUser.value = user;
    editMode.value = true;
    showModal.value = true;
};

const submit = () => {
    if (editMode.value) {
        form.put(route('users.update', selectedUser.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); }
        });
    }
};

const deleteUser = (user) => {
    if (confirm(`Tem certeza que deseja remover o usuário ${user.name}?`)) {
        form.delete(route('users.destroy', user.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">👥 Usuários</h1>
                    <p class="text-slate-600">Gerencie usuários da empresa</p>
                </div>
                <button
                    @click="openCreate"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700"
                >
                    + Novo Usuário
                </button>
            </div>

            <div class="bg-white rounded-2xl border-2 border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Nome</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">E-mail</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Cadastrado em</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ user.name }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ user.email }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">
                                {{ new Date(user.created_at).toLocaleDateString('pt-BR') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <button
                                        @click="openEdit(user)"
                                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-bold text-sm"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteUser(user)"
                                        class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-bold text-sm"
                                    >
                                        Remover
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click.self="showModal = false"
            >
                <div class="bg-white rounded-2xl p-8 max-w-md w-full">
                    <h2 class="text-2xl font-black mb-6">{{ editMode ? 'Editar' : 'Novo' }} Usuário</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Nome</label>
                            <input v-model="form.name" type="text" required class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">E-mail</label>
                            <input v-model="form.email" type="email" required class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">
                                {{ editMode ? 'Nova Senha (deixe em branco para manter)' : 'Senha' }}
                            </label>
                            <input v-model="form.password" type="password" :required="!editMode" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Confirmar Senha</label>
                            <input v-model="form.password_confirmation" type="password" :required="!editMode" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
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
