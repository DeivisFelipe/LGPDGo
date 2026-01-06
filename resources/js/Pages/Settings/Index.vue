<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    company: Object
});

const form = useForm({
    name: props.company.name,
    dpo_name: props.company.dpo_name || '',
    dpo_email: props.company.dpo_email || '',
    dpo_phone: props.company.dpo_phone || '',
    address: props.company.address || '',
    city: props.company.city || '',
    state: props.company.state || '',
    zip_code: props.company.zip_code || ''
});

const submit = () => {
    form.put(route('settings.update'));
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8 max-w-4xl">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900">⚙️ Configurações</h1>
                <p class="text-slate-600">Configure informações da empresa e DPO</p>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-2xl border-2 border-slate-200 p-8 space-y-6">
                <!-- Empresa -->
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Informações da Empresa</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Nome da Empresa</label>
                            <input v-model="form.name" type="text" required class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2">Endereço</label>
                            <input v-model="form.address" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Cidade</label>
                                <input v-model="form.city" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Estado</label>
                                <input v-model="form.state" type="text" maxlength="2" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">CEP</label>
                                <input v-model="form.zip_code" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200" />

                <!-- DPO -->
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Dados do DPO (Encarregado)</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-2">Nome do DPO</label>
                            <input v-model="form.dpo_name" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">E-mail do DPO</label>
                                <input v-model="form.dpo_email" type="email" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Telefone do DPO</label>
                                <input v-model="form.dpo_phone" type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 disabled:opacity-50"
                    >
                        Salvar Configurações
                    </button>
                </div>

                <div v-if="form.recentlySuccessful" class="text-green-600 font-bold text-center">
                    ✅ Configurações salvas com sucesso!
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
