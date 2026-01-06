<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'complete']);

const currentStep = ref(1);
const totalSteps = 7;

const steps = [
    {
        id: 1,
        title: 'Bem-vindo ao LGPDGo! 🎉',
        description: 'Vamos configurar sua empresa para estar em conformidade com a LGPD',
        icon: '👋',
        content: 'Este assistente vai te guiar pelos principais passos para começar. Leva só uns 5 minutos!',
        action: null
    },
    {
        id: 2,
        title: 'Informações da Empresa',
        description: 'Dados básicos da sua organização',
        icon: '🏢',
        content: 'Certifique-se de que os dados da sua empresa estão corretos. Eles aparecerão nos documentos gerados.',
        action: { label: 'Editar Empresa', route: 'companies.edit' }
    },
    {
        id: 3,
        title: 'Defina seu DPO',
        description: 'Quem é o responsável pela LGPD?',
        icon: '🛡️',
        content: 'O DPO (Data Protection Officer) é quem cuida da proteção de dados. Pode ser você mesmo ou alguém da equipe.',
        action: { label: 'Cadastrar DPO', route: 'users.create' }
    },
    {
        id: 4,
        title: 'Crie Departamentos',
        description: 'Organize as áreas da empresa',
        icon: '🏛️',
        content: 'Departamentos ajudam a mapear onde os dados pessoais são usados (RH, Vendas, Marketing, etc).',
        action: { label: 'Criar Departamentos', route: 'departments.index' }
    },
    {
        id: 5,
        title: 'Mapeie os Dados (ROPA)',
        description: 'Inventário de Dados Pessoais',
        icon: '📋',
        content: 'Este é o coração da LGPD! Documente quais dados você coleta e por quê.',
        action: { label: 'Iniciar ROPA', route: 'data-inventories.create' }
    },
    {
        id: 6,
        title: 'Configure Cookies',
        description: 'Banner de consentimento',
        icon: '🍪',
        content: 'Se seu site usa cookies (analytics, ads), você precisa de um banner de consentimento.',
        action: { label: 'Gerenciar Cookies', route: 'cookies.index' }
    },
    {
        id: 7,
        title: 'Tudo Pronto! 🎊',
        description: 'Sua jornada LGPD começou',
        icon: '✅',
        content: 'Parabéns! Você configurou o básico. Agora continue mapeando dados e melhorando sua nota de adequação.',
        action: { label: 'Ir para Dashboard', route: 'dashboard' }
    }
];

const currentStepData = computed(() => steps[currentStep.value - 1]);
const progress = computed(() => (currentStep.value / totalSteps) * 100);
const isFirstStep = computed(() => currentStep.value === 1);
const isLastStep = computed(() => currentStep.value === totalSteps);

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    } else {
        completeOnboarding();
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const goToStep = (stepNumber) => {
    currentStep.value = stepNumber;
};

const skipOnboarding = () => {
    if (confirm('Tem certeza que deseja pular o tutorial? Você pode acessá-lo novamente depois em "Ajuda".')) {
        emit('close');
    }
};

const completeOnboarding = () => {
    // Salvar no backend que o usuário completou o onboarding
    router.post(route('onboarding.complete'), {}, {
        onSuccess: () => {
            emit('complete');
            emit('close');
        }
    });
};

const executeAction = () => {
    if (currentStepData.value.action) {
        emit('close');
        router.visit(route(currentStepData.value.action.route));
    }
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="4xl" :closeable="false">
        <div class="relative bg-gradient-to-br from-indigo-50 via-white to-blue-50">
            <!-- Header com Progress Bar -->
            <div class="sticky top-0 bg-white border-b border-slate-200 z-10">
                <div class="px-8 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Configuração Inicial</h2>
                            <p class="text-sm text-slate-500">Passo {{ currentStep }} de {{ totalSteps }}</p>
                        </div>
                        <button
                            @click="skipOnboarding"
                            class="text-sm text-slate-400 hover:text-slate-600 underline transition-colors"
                        >
                            Pular Tutorial
                        </button>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-200 rounded-full h-2.5">
                        <div 
                            class="bg-gradient-to-r from-indigo-600 to-blue-500 h-2.5 rounded-full transition-all duration-500 ease-out"
                            :style="{ width: progress + '%' }"
                        ></div>
                    </div>
                </div>

                <!-- Step Indicators -->
                <div class="px-8 pb-4 overflow-x-auto">
                    <div class="flex gap-2 min-w-max">
                        <button
                            v-for="step in steps"
                            :key="step.id"
                            @click="goToStep(step.id)"
                            :class="[
                                'flex-shrink-0 w-10 h-10 rounded-full font-bold text-sm transition-all duration-200',
                                currentStep === step.id 
                                    ? 'bg-indigo-600 text-white scale-110 ring-4 ring-indigo-100' 
                                    : currentStep > step.id
                                        ? 'bg-green-100 text-green-700 hover:scale-105'
                                        : 'bg-slate-100 text-slate-400'
                            ]"
                            :title="step.title"
                        >
                            <span v-if="currentStep > step.id">✓</span>
                            <span v-else>{{ step.id }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 min-h-[400px] flex items-center justify-center">
                <div class="max-w-2xl w-full text-center">
                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-5xl shadow-2xl shadow-indigo-200 animate-bounce-slow">
                            {{ currentStepData.icon }}
                        </div>
                    </div>

                    <!-- Title & Description -->
                    <h3 class="text-3xl font-black text-slate-900 mb-3">
                        {{ currentStepData.title }}
                    </h3>
                    <p class="text-lg text-slate-600 mb-6">
                        {{ currentStepData.description }}
                    </p>

                    <!-- Content -->
                    <div class="p-6 bg-white rounded-2xl border-2 border-slate-200 shadow-lg mb-8">
                        <p class="text-slate-700 leading-relaxed">
                            {{ currentStepData.content }}
                        </p>
                    </div>

                    <!-- Action Button (se tiver) -->
                    <div v-if="currentStepData.action" class="mb-6">
                        <button
                            @click="executeAction"
                            class="px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg shadow-green-200 hover:scale-105"
                        >
                            {{ currentStepData.action.label }} →
                        </button>
                        <p class="text-xs text-slate-500 mt-2">Ou avance para ver os próximos passos</p>
                    </div>
                </div>
            </div>

            <!-- Footer Navigation -->
            <div class="sticky bottom-0 bg-white border-t border-slate-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <button
                        @click="prevStep"
                        :disabled="isFirstStep"
                        :class="[
                            'px-6 py-3 font-bold rounded-xl transition-all duration-200',
                            isFirstStep 
                                ? 'bg-slate-100 text-slate-300 cursor-not-allowed' 
                                : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
                        ]"
                    >
                        ← Voltar
                    </button>

                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Use as setas do teclado para navegar
                    </div>

                    <button
                        @click="nextStep"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg shadow-indigo-200"
                    >
                        {{ isLastStep ? 'Finalizar ✓' : 'Próximo →' }}
                    </button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(-5%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}
</style>
