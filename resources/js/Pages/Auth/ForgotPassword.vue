<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

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
    <GuestLayout title="Recuperar Senha">
        <v-alert type="info" variant="tonal" class="mb-4">
            Esqueceu sua senha? Sem problemas. Informe seu e-mail e enviaremos um link para redefinir sua senha.
        </v-alert>

        <v-alert v-if="status" type="success" variant="tonal" class="mb-4">
            {{ status }}
        </v-alert>

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

            <v-btn
                type="submit"
                color="primary"
                size="large"
                block
                :loading="form.processing"
                :disabled="form.processing"
                class="mt-6"
            >
                Enviar Link de Recuperação
            </v-btn>

            <v-divider class="my-6"></v-divider>

            <div class="text-center">
                <Link :href="route('login')" class="text-primary text-decoration-none">
                    Voltar para o login
                </Link>
            </div>
        </v-form>
    </GuestLayout>
</template>
