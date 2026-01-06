<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    company: Object,
    compliance: Object,
});

const badgeUrl = computed(() => `/api/compliance-badge/${props.company.slug}`);
const widgetUrl = computed(() => `/api/compliance-widget/${props.company.slug}`);
const jsonUrl = computed(() => `/api/compliance-json/${props.company.slug}`);

const embedCode = ref('');
const activeTab = ref('svg');

const generateEmbedCode = (type) => {
    if (type === 'svg') {
        return `<!-- Selo LGPD - ${props.company.name} -->
<a href="${window.location.origin}/compliance/${props.company.slug}" target="_blank" rel="noopener">
    <img src="${window.location.origin}${badgeUrl.value}" 
         alt="Selo de Conformidade LGPD" 
         width="200" />
</a>`;
    } else if (type === 'iframe') {
        return `<!-- Widget LGPD - ${props.company.name} -->
<iframe 
    src="${window.location.origin}${widgetUrl.value}"
    width="100%" 
    height="400" 
    frameborder="0" 
    scrolling="no"
    style="max-width: 400px;">
</iframe>`;
    } else if (type === 'json') {
        return `// API JSON - ${props.company.name}
fetch('${window.location.origin}${jsonUrl.value}')
    .then(response => response.json())
    .then(data => {
        console.log('Score:', data.compliance.score);
        console.log('Nível:', data.compliance.level.name);
    });`;
    }
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Código copiado para a área de transferência!');
};

const level = computed(() => {
    const score = props.compliance.score;
    if (score >= 90) return { name: 'Excelente', color: 'green', emoji: '🏆' };
    if (score >= 75) return { name: 'Bom', color: 'blue', emoji: '✓' };
    if (score >= 50) return { name: 'Regular', color: 'amber', emoji: '⚠️' };
    return { name: 'Crítico', color: 'red', emoji: '⚠️' };
});
</script>

<template>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 mb-2">🏆 Selo de Conformidade LGPD</h1>
            <p class="text-slate-600">Demonstre transparência e compromisso com a proteção de dados</p>
        </div>

        <!-- Preview Card -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl border-2 border-indigo-200 p-8 mb-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex items-center justify-center">
                    <img
                        :src="badgeUrl"
                        alt="Selo de Conformidade LGPD"
                        class="max-w-[250px] drop-shadow-2xl"
                    />
                </div>
                <div class="flex flex-col justify-center">
                    <div class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-2">Seu Score Atual</div>
                    <div class="flex items-baseline gap-3 mb-4">
                        <div class="text-6xl font-black text-indigo-600">{{ compliance.score }}</div>
                        <div class="text-xl text-slate-600">/100</div>
                    </div>
                    <div class="mb-6">
                        <span
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-lg"
                            :class="level.color === 'green' 
                                ? 'bg-green-100 text-green-700 border-2 border-green-300' 
                                : level.color === 'blue'
                                    ? 'bg-blue-100 text-blue-700 border-2 border-blue-300'
                                    : level.color === 'amber'
                                        ? 'bg-amber-100 text-amber-700 border-2 border-amber-300'
                                        : 'bg-red-100 text-red-700 border-2 border-red-300'"
                        >
                            <span class="text-2xl">{{ level.emoji }}</span>
                            {{ level.name }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-600">
                        O selo é atualizado automaticamente sempre que alguém o visualiza, refletindo seu score mais recente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-3xl border-2 border-slate-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">💻</div>
                <h2 class="text-xl font-black text-slate-900">Código de Incorporação</h2>
            </div>

            <!-- Tab Buttons -->
            <div class="flex gap-2 mb-6 border-b-2 border-slate-200">
                <button
                    @click="activeTab = 'svg'"
                    class="px-6 py-3 font-bold rounded-t-xl transition-all"
                    :class="activeTab === 'svg' 
                        ? 'bg-indigo-600 text-white' 
                        : 'text-slate-600 hover:bg-slate-100'"
                >
                    🖼️ Imagem SVG
                </button>
                <button
                    @click="activeTab = 'iframe'"
                    class="px-6 py-3 font-bold rounded-t-xl transition-all"
                    :class="activeTab === 'iframe' 
                        ? 'bg-indigo-600 text-white' 
                        : 'text-slate-600 hover:bg-slate-100'"
                >
                    📦 Widget Iframe
                </button>
                <button
                    @click="activeTab = 'json'"
                    class="px-6 py-3 font-bold rounded-t-xl transition-all"
                    :class="activeTab === 'json' 
                        ? 'bg-indigo-600 text-white' 
                        : 'text-slate-600 hover:bg-slate-100'"
                >
                    📊 API JSON
                </button>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- SVG Tab -->
                <div v-if="activeTab === 'svg'">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Selo em Imagem SVG</h3>
                        <p class="text-sm text-slate-600">Método recomendado - leve, responsivo e sempre atualizado</p>
                    </div>
                    
                    <div class="bg-slate-900 rounded-xl p-6 font-mono text-sm text-green-400 overflow-x-auto">
                        <pre>{{ generateEmbedCode('svg') }}</pre>
                    </div>
                    
                    <button
                        @click="copyToClipboard(generateEmbedCode('svg'))"
                        class="mt-4 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors"
                    >
                        📋 Copiar Código
                    </button>
                </div>

                <!-- iFrame Tab -->
                <div v-if="activeTab === 'iframe'">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Widget com Detalhes</h3>
                        <p class="text-sm text-slate-600">Exibe o selo com informações detalhadas dos pilares de conformidade</p>
                    </div>
                    
                    <div class="bg-slate-900 rounded-xl p-6 font-mono text-sm text-green-400 overflow-x-auto">
                        <pre>{{ generateEmbedCode('iframe') }}</pre>
                    </div>
                    
                    <button
                        @click="copyToClipboard(generateEmbedCode('iframe'))"
                        class="mt-4 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors"
                    >
                        📋 Copiar Código
                    </button>
                </div>

                <!-- JSON Tab -->
                <div v-if="activeTab === 'json'">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">API JSON</h3>
                        <p class="text-sm text-slate-600">Acesse os dados programaticamente para criar sua própria visualização</p>
                    </div>
                    
                    <div class="mb-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Endpoint</div>
                        <div class="font-mono text-sm text-indigo-600 break-all">{{ window.location.origin }}{{ jsonUrl }}</div>
                    </div>
                    
                    <div class="bg-slate-900 rounded-xl p-6 font-mono text-sm text-green-400 overflow-x-auto">
                        <pre>{{ generateEmbedCode('json') }}</pre>
                    </div>
                    
                    <button
                        @click="copyToClipboard(generateEmbedCode('json'))"
                        class="mt-4 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors"
                    >
                        📋 Copiar Código
                    </button>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border-2 border-green-200 p-6">
                <div class="text-3xl mb-3">🔒</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Transparência</h3>
                <p class="text-sm text-slate-600">
                    Demonstre compromisso com a proteção de dados e construa confiança com seus clientes
                </p>
            </div>

            <div class="bg-white rounded-2xl border-2 border-blue-200 p-6">
                <div class="text-3xl mb-3">⚡</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Tempo Real</h3>
                <p class="text-sm text-slate-600">
                    O selo é atualizado automaticamente conforme você melhora sua conformidade LGPD
                </p>
            </div>

            <div class="bg-white rounded-2xl border-2 border-purple-200 p-6">
                <div class="text-3xl mb-3">🎨</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Design Profissional</h3>
                <p class="text-sm text-slate-600">
                    Visual moderno e responsivo que se adapta perfeitamente ao seu site
                </p>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
            <div class="flex items-start gap-3">
                <div class="text-2xl">📝</div>
                <div class="flex-1">
                    <div class="font-bold text-blue-900 mb-2">Como usar:</div>
                    <ol class="text-sm text-blue-700 space-y-2 list-decimal list-inside">
                        <li>Escolha o formato desejado (SVG, Widget ou JSON)</li>
                        <li>Copie o código fornecido</li>
                        <li>Cole no HTML do seu site onde deseja exibir o selo</li>
                        <li>O selo será exibido automaticamente com seu score atualizado</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>
