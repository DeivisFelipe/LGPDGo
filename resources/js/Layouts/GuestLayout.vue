<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <v-app theme="dark">
        <Head :title="title" />
        
        <v-main>
            <v-container fluid class="fill-height">
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="5" lg="4">
                        <v-card elevation="8" rounded="lg" theme="dark">
                            <v-card-text class="pa-8">
                                <div class="text-center mb-8">
                                    <v-icon size="64" color="primary">mdi-shield-lock</v-icon>
                                    <h1 class="text-h4 font-weight-bold mt-4">LGPDGo</h1>
                                    <p class="text-subtitle-1 text-medium-emphasis">
                                        Sistema de Gerenciamento de Dados
                                    </p>
                                </div>

                                <slot />
                            </v-card-text>
                        </v-card>

                        <div class="text-center mt-4">
                            <p class="text-caption text-medium-emphasis">
                                © {{ new Date().getFullYear() }} LGPDGo. Todos os direitos reservados.
                            </p>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.fill-height {
    min-height: 100vh;
}
</style>
