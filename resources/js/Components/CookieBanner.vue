<script setup>
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    companyName: {
        type: String,
        default: 'Nossa empresa'
    },
    privacyPolicyUrl: {
        type: String,
        default: '/politica-privacidade'
    }
});

const showBanner = ref(false);
const showCustomize = ref(false);

const preferences = ref({
    necessarios: true, // Sempre true, não pode desativar
    funcionais: false,
    analytics: false,
    marketing: false,
});

const CONSENT_KEY = 'lgpd_cookie_consent';
const CONSENT_VERSION = '1.0';

onMounted(() => {
    const stored = localStorage.getItem(CONSENT_KEY);
    if (!stored) {
        showBanner.value = true;
    } else {
        const parsed = JSON.parse(stored);
        if (parsed.version !== CONSENT_VERSION) {
            showBanner.value = true;
        } else {
            preferences.value = parsed.preferences;
        }
    }
});

const acceptAll = () => {
    preferences.value = {
        necessarios: true,
        funcionais: true,
        analytics: true,
        marketing: true,
    };
    saveConsent();
};

const rejectNonEssential = () => {
    preferences.value = {
        necessarios: true,
        funcionais: false,
        analytics: false,
        marketing: false,
    };
    saveConsent();
};

const saveCustomPreferences = () => {
    saveConsent();
    showCustomize.value = false;
};

const saveConsent = () => {
    const consent = {
        version: CONSENT_VERSION,
        timestamp: new Date().toISOString(),
        preferences: preferences.value,
    };
    localStorage.setItem(CONSENT_KEY, JSON.stringify(consent));
    showBanner.value = false;
    
    // Disparar evento customizado para analytics/tracking
    window.dispatchEvent(new CustomEvent('cookieConsentUpdated', { detail: preferences.value }));
};

const categories = computed(() => [
    {
        key: 'necessarios',
        title: 'Cookies Necessários',
        description: 'Essenciais para o funcionamento do site (login, carrinho, segurança)',
        required: true,
        icon: '🔒',
    },
    {
        key: 'funcionais',
        title: 'Cookies Funcionais',
        description: 'Melhoram a experiência com preferências e personalização',
        required: false,
        icon: '⚙️',
    },
    {
        key: 'analytics',
        title: 'Cookies de Analytics',
        description: 'Ajudam a entender como você usa o site (Google Analytics, etc)',
        required: false,
        icon: '📊',
    },
    {
        key: 'marketing',
        title: 'Cookies de Marketing',
        description: 'Utilizados para exibir anúncios relevantes',
        required: false,
        icon: '📣',
    },
]);
</script>

<template>
    <!-- Banner Principal -->
    <Transition name="slide-up">
        <div
            v-if="showBanner && !showCustomize"
            class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t-4 border-indigo-600 shadow-2xl"
        >
            <div class="max-w-7xl mx-auto px-6 py-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <!-- Texto -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="text-3xl">🍪</div>
                            <h3 class="text-lg font-black text-slate-900">Cookies e Privacidade</h3>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ companyName }} utiliza cookies para melhorar sua experiência, analisar tráfego e personalizar conteúdo.
                            Ao continuar navegando, você concorda com nossa
                            <a :href="privacyPolicyUrl" class="text-indigo-600 hover:text-indigo-700 font-bold underline">Política de Privacidade</a>.
                        </p>
                    </div>

                    <!-- Ações -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            @click="showCustomize = true"
                            class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-200 rounded-xl hover:bg-slate-300 transition-colors"
                        >
                            ⚙️ Personalizar
                        </button>
                        <button
                            @click="rejectNonEssential"
                            class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-200 rounded-xl hover:bg-slate-300 transition-colors"
                        >
                            Rejeitar Não-Essenciais
                        </button>
                        <button
                            @click="acceptAll"
                            class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                        >
                            ✓ Aceitar Todos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>

    <!-- Modal de Customização -->
    <Transition name="fade">
        <div
            v-if="showCustomize"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            @click.self="showCustomize = false"
        >
            <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b-2 border-slate-200 px-8 py-6 rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="text-3xl">🍪</div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900">Preferências de Cookies</h2>
                                <p class="text-sm text-slate-600 mt-1">Escolha quais tipos de cookies você permite</p>
                            </div>
                        </div>
                        <button
                            @click="showCustomize = false"
                            class="text-slate-400 hover:text-slate-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Categorias -->
                <div class="px-8 py-6 space-y-4">
                    <div
                        v-for="category in categories"
                        :key="category.key"
                        class="p-6 border-2 border-slate-200 rounded-2xl"
                        :class="{ 'bg-slate-50': category.required }"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-2xl">{{ category.icon }}</span>
                                    <h3 class="text-lg font-black text-slate-900">{{ category.title }}</h3>
                                    <span
                                        v-if="category.required"
                                        class="px-2 py-1 bg-slate-700 text-white text-xs font-bold rounded uppercase"
                                    >
                                        Obrigatório
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600">{{ category.description }}</p>
                            </div>
                            
                            <!-- Toggle -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    v-model="preferences[category.key]"
                                    :disabled="category.required"
                                    class="sr-only peer"
                                />
                                <div
                                    class="w-14 h-8 rounded-full transition-all"
                                    :class="preferences[category.key] || category.required
                                        ? 'bg-indigo-600'
                                        : 'bg-slate-300'"
                                >
                                    <div
                                        class="absolute top-1 left-1 bg-white w-6 h-6 rounded-full transition-transform"
                                        :class="{ 'translate-x-6': preferences[category.key] || category.required }"
                                    ></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 bg-slate-50 border-t-2 border-slate-200 px-8 py-6 rounded-b-3xl">
                    <div class="flex items-center justify-between gap-4">
                        <a
                            :href="privacyPolicyUrl"
                            class="text-sm font-bold text-indigo-600 hover:text-indigo-700"
                        >
                            📄 Ler Política de Privacidade
                        </a>
                        <div class="flex items-center gap-3">
                            <button
                                @click="showCustomize = false"
                                class="px-6 py-3 text-sm font-bold text-slate-700 bg-white border-2 border-slate-200 rounded-xl hover:bg-slate-100 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                @click="saveCustomPreferences"
                                class="px-8 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
                            >
                                ✓ Salvar Preferências
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
    transition: transform 0.3s ease-out;
}

.slide-up-enter-from,
.slide-up-leave-to {
    transform: translateY(100%);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
