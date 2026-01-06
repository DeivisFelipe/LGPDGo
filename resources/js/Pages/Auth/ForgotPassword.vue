<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: GuestLayout,
});

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-900 dark:text-blue-100">
                Esqueceu sua senha? Sem problemas. Informe seu e-mail e enviaremos um link para redefinir sua senha.
            </p>
        </div>

        <div v-if="status" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <p class="text-sm text-green-900 dark:text-green-100">
                {{ status }}
            </p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">E-mail</label>
            <input
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white"
                placeholder="Digite seu e-mail"
                :class="{ 'border-red-500': form.errors.email }"
                required
                autofocus
            />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span v-if="form.processing">Enviando...</span>
            <span v-else>Enviar Link de Recuperação</span>
        </button>

        <hr class="my-6 border-gray-600" />

        <div class="text-center">
            <Link :href="route('login')" class="text-blue-400 hover:text-blue-300 text-sm">
                Voltar para o login
            </Link>
        </div>
    </form>
</template>
