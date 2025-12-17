<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const company = computed(() => page.props.auth.user?.company);

const stats = [
    { title: 'Usuários', value: '0', icon: 'mdi-account-multiple', color: 'primary' },
    { title: 'Permissões', value: '8', icon: 'mdi-shield-key', color: 'success' },
    { title: 'Grupos', value: '3', icon: 'mdi-account-group', color: 'warning' },
    { title: 'Empresas', value: '1', icon: 'mdi-domain', color: 'info' },
];
</script>

<template>
    <!-- Welcome Section -->
    <v-row class="mb-4">
            <v-col cols="12">
                <v-card>
                    <v-card-text>
                        <div class="d-flex align-center">
                            <v-avatar color="primary" size="64" class="mr-4">
                                <span class="text-h4">{{ user?.name?.charAt(0) }}</span>
                            </v-avatar>
                            <div>
                                <h2 class="text-h5 font-weight-bold">
                                    Olá, {{ user?.name }}! 👋
                                </h2>
                                <p class="text-subtitle-1 text-medium-emphasis mb-0">
                                    Bem-vindo ao LGPDGo
                                </p>
                                <v-chip v-if="user?.is_super_user" color="error" size="small" class="mt-1">
                                    <v-icon start>mdi-shield-crown</v-icon>
                                    Superusuário
                                </v-chip>
                                <v-chip v-if="company" color="primary" size="small" class="mt-1 ml-2">
                                    <v-icon start>mdi-domain</v-icon>
                                    {{ company.name }}
                                </v-chip>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Stats Cards -->
        <v-row>
            <v-col v-for="stat in stats" :key="stat.title" cols="12" sm="6" md="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <p class="text-caption text-medium-emphasis mb-1">
                                    {{ stat.title }}
                                </p>
                                <h3 class="text-h4 font-weight-bold">
                                    {{ stat.value }}
                                </h3>
                            </div>
                            <v-avatar :color="stat.color" size="56">
                                <v-icon size="32">{{ stat.icon }}</v-icon>
                            </v-avatar>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Info Cards -->
        <v-row class="mt-4">
            <v-col cols="12" md="8">
                <v-card>
                    <v-card-title>
                        <v-icon class="mr-2">mdi-information</v-icon>
                        Sobre o Sistema
                    </v-card-title>
                    <v-card-text>
                        <p class="mb-4">
                            O LGPDGo é um sistema completo de gerenciamento de dados com foco em conformidade com a LGPD.
                        </p>
                        <v-list>
                            <v-list-item prepend-icon="mdi-check-circle" title="Multi-tenancy">
                                <v-list-item-subtitle>Isolamento completo de dados por empresa</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item prepend-icon="mdi-check-circle" title="Sistema de Permissões">
                                <v-list-item-subtitle>Controle granular de acessos e funcionalidades</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item prepend-icon="mdi-check-circle" title="Grupos de Permissões">
                                <v-list-item-subtitle>Organize permissões em grupos para facilitar o gerenciamento</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item prepend-icon="mdi-check-circle" title="Autenticação Segura">
                                <v-list-item-subtitle>Laravel Sanctum com Inertia.js e Vue 3</v-list-item-subtitle>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" md="4">
                <v-card>
                    <v-card-title>
                        <v-icon class="mr-2">mdi-account</v-icon>
                        Suas Informações
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item>
                                <v-list-item-title>Nome</v-list-item-title>
                                <v-list-item-subtitle>{{ user?.name }}</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-title>E-mail</v-list-item-title>
                                <v-list-item-subtitle>{{ user?.email }}</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item v-if="company">
                                <v-list-item-title>Empresa</v-list-item-title>
                                <v-list-item-subtitle>{{ company.name }}</v-list-item-subtitle>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-title>Tipo de Usuário</v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ user?.is_super_user ? 'Superusuário' : 'Usuário Regular' }}
                                </v-list-item-subtitle>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
</template>
