<script setup>
import { ref, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';
import OnboardingWizard from '@/Components/OnboardingWizard.vue';
import CookieBanner from '@/Components/CookieBanner.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Controlar exibição do Onboarding (verificar se usuário já completou)
const showOnboarding = ref(!user.value?.onboarding_completed && user.value?.is_super_user);

const handleOnboardingComplete = () => {
    showOnboarding.value = false;
};

// Props para title/subtitle do Header (podem ser passadas pelo slot)
const headerTitle = ref('Dashboard');
const headerSubtitle = ref('Visão Geral');
</script>

<template>
    <div class="flex h-screen bg-slate-50 font-sans antialiased text-slate-900">
        <Head title="LGPDGo" />
        
        <!-- Sidebar Component -->
        <Sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header Component -->
            <Header>
                <template #title>
                    {{ headerTitle }}
                </template>
                <template #subtitle>
                    {{ headerSubtitle }}
                </template>
            </Header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50/50 p-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Onboarding Wizard Modal -->
        <OnboardingWizard 
            :show="showOnboarding" 
            @close="showOnboarding = false"
            @complete="handleOnboardingComplete"
        />

        <!-- Cookie Banner -->
        <CookieBanner 
            :company-name="user?.company?.name || 'Nossa empresa'"
            privacy-policy-url="/politica-privacidade"
        />
    </div>
</template>

<style>
/* Smooth Scrollbar para Webkit */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
