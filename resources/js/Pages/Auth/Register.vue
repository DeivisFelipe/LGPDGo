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
    <v-form @submit.prevent="submit">
            <v-text-field
                v-model="form.name"
                label="Nome"
                prepend-inner-icon="mdi-account"
                variant="filled"
                :error-messages="form.errors.name"
                required
                autofocus
                color="primary"
            ></v-text-field>

            <v-text-field
                v-model="form.email"
                label="E-mail"
                type="email"
                prepend-inner-icon="mdi-email"
                variant="filled"
                :error-messages="form.errors.email"
                required
                class="mt-2"
                color="primary"
            ></v-text-field>

            <v-text-field
                v-model="form.password"
                label="Senha"
                :type="showPassword ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock"
                :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append-inner="showPassword = !showPassword"
                variant="filled"
                :error-messages="form.errors.password"
                required
                class="mt-2"
                color="primary"
            ></v-text-field>

            <v-text-field
                v-model="form.password_confirmation"
                label="Confirmar Senha"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                prepend-inner-icon="mdi-lock-check"
                :append-inner-icon="showPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append-inner="showPasswordConfirmation = !showPasswordConfirmation"
                variant="filled"
                :error-messages="form.errors.password_confirmation"
                required
                class="mt-2"
                color="primary"
            ></v-text-field>

            <v-btn
                type="submit"
                color="primary"
                size="large"
                block
                :loading="form.processing"
                :disabled="form.processing"
                class="mt-6"
            >
                Registrar
            </v-btn>

            <v-divider class="my-6"></v-divider>

            <div class="text-center">
                <span class="text-medium-emphasis">Já tem uma conta?</span>
                <Link :href="route('login')" class="text-primary text-decoration-none ml-1">
                    Faça login
                </Link>
            </div>
        </v-form>
</template>
