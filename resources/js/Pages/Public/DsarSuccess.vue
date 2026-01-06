<script setup>
defineOptions({ layout: undefined });

const props = defineProps({
    protocolo: String,
    prazo_resposta: String,
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center px-6">
        <div class="max-w-2xl w-full bg-white rounded-3xl border-2 border-green-200 shadow-2xl p-12 text-center">
            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-200">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-4xl font-black text-slate-900 mb-4">Solicitação Recebida!</h1>
            <p class="text-lg text-slate-600 mb-8">
                Sua solicitação foi registrada com sucesso. Analisaremos e responderemos em breve.
            </p>

            <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 mb-8">
                <div class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-2">Protocolo</div>
                <div class="text-3xl font-black text-green-700 mb-4">{{ protocolo }}</div>
                <div class="text-sm text-slate-600">
                    <strong>Guarde este número</strong> para acompanhar sua solicitação
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mb-8">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-2xl mb-2">📅</div>
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Prazo de Resposta</div>
                    <div class="text-lg font-black text-slate-900">{{ prazo_resposta }}</div>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-2xl mb-2">📧</div>
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Acompanhamento</div>
                    <div class="text-lg font-black text-slate-900">Por E-mail</div>
                </div>
            </div>

            <div class="p-6 bg-blue-50 border-2 border-blue-200 rounded-2xl mb-8 text-left">
                <div class="flex items-start gap-3">
                    <div class="text-2xl">💡</div>
                    <div class="flex-1">
                        <div class="font-black text-slate-900 mb-2">Próximos Passos</div>
                        <ul class="text-sm text-slate-600 space-y-1">
                            <li>✓ Você receberá um e-mail de confirmação</li>
                            <li>✓ Nossa equipe analisará sua solicitação</li>
                            <li>✓ Entraremos em contato em até 15 dias úteis</li>
                            <li>✓ Se necessário, poderemos solicitar documentos adicionais</li>
                        </ul>
                    </div>
                </div>
            </div>

            <a
                href="/"
                class="inline-block px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200"
            >
                ← Voltar ao Início
            </a>
        </div>
    </div>
</template>
