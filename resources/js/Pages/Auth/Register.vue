<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: GuestLayout,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Nome</label>
            <input
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white"
                placeholder="Digite seu nome"
                :class="{ 'border-red-500': form.errors.name }"
                required
                autofocus
            />
            <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
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
            />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
            <div class="relative">
                <input
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white pr-10"
                    placeholder="Digite sua senha"
                    :class="{ 'border-red-500': form.errors.password }"
                    required
                />
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-300"
                >
                    <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">{{ form.errors.password }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmar Senha</label>
            <div class="relative">
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white pr-10"
                    placeholder="Confirme sua senha"
                    :class="{ 'border-red-500': form.errors.password_confirmation }"
                    required
                />
                <button
                    type="button"
                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-300"
                >
                    <svg v-if="showPasswordConfirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-500">{{ form.errors.password_confirmation }}</p>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span v-if="form.processing">Registrando...</span>
            <span v-else>Registrar</span>
        </button>

        <hr class="my-6 border-gray-600" />

        <div class="text-center">
            <span class="text-gray-400 text-sm">Já tem uma conta?</span>
            <Link :href="route('login')" class="text-blue-400 hover:text-blue-300 text-sm ml-1">
                Faça login
            </Link>
        </div>
    </form>
</template>
