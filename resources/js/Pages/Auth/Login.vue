<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: GuestLayout,
});

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-text-field
            v-model="form.email"
            label="E-mail"
            type="email"
            prepend-inner-icon="mdi-email"
            variant="filled"
            :error-messages="form.errors.email"
            required
            autofocus
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

        <v-checkbox
            v-model="form.remember"
            label="Lembrar-me"
            color="primary"
            hide-details
        ></v-checkbox>

        <v-btn
            type="submit"
            color="primary"
            size="large"
            block
            :loading="form.processing"
            :disabled="form.processing"
            class="mt-6"
        >
            Entrar
        </v-btn>

        <div class="text-center mt-4">
            <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-primary text-decoration-none"
            >
                Esqueceu sua senha?
            </Link>
        </div>

        <v-divider class="my-6"></v-divider>

        <div class="text-center">
            <span class="text-medium-emphasis">Não tem uma conta?</span>
            <Link :href="route('register')" class="text-primary text-decoration-none ml-1">
                Registre-se
            </Link>
        </div>
    </v-form>
</template>
