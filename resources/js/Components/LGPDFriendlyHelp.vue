<script setup>
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    topic: {
        type: String,
        required: true
    },
    position: {
        type: String,
        default: 'inline' // 'inline' ou 'floating'
    }
});

const showModal = ref(false);

// Base de conhecimento LGPD simplificada
const helpContent = {
    'base-legal': {
        title: 'Base Legal',
        icon: '⚖️',
        simpleExplanation: 'É a "justificativa" para você usar os dados de alguém. Tipo uma autorização legal.',
        detailedExplanation: 'A Base Legal é o fundamento jurídico que permite sua empresa processar dados pessoais. É como se fosse a "permissão" que você tem para usar aquela informação.',
        examples: [
            {
                name: 'Consentimento',
                description: 'A pessoa autorizou você a usar os dados',
                example: 'Cliente marcou "aceito receber e-mails" no formulário'
            },
            {
                name: 'Execução de Contrato',
                description: 'Você precisa dos dados para cumprir um contrato',
                example: 'CPF e endereço para entregar um produto comprado'
            },
            {
                name: 'Obrigação Legal',
                description: 'A lei obriga você a guardar aquele dado',
                example: 'Nota fiscal eletrônica (exigência da Receita Federal)'
            },
            {
                name: 'Legítimo Interesse',
                description: 'Você tem um interesse legítimo (mas precisa avaliar)',
                example: 'Análise de fraude em transações financeiras'
            }
        ],
        tips: [
            'Escolha sempre a base legal MAIS ESPECÍFICA para aquele tratamento',
            'Se for Consentimento, você PRECISA ter prova de que a pessoa aceitou',
            'Legítimo Interesse é a mais complexa - quando usar, documente MUITO bem'
        ],
        law: 'Art. 7º e 11 da LGPD (Lei 13.709/2018)'
    },
    'titular': {
        title: 'Titular de Dados',
        icon: '👤',
        simpleExplanation: 'É a pessoa "dona" dos dados. Tipo você mesmo quando cadastra seu CPF em algum lugar.',
        detailedExplanation: 'Titular é a pessoa física a quem os dados pessoais se referem. É o indivíduo que tem direitos sobre suas informações.',
        examples: [
            {
                name: 'Seus Funcionários',
                description: 'Colaboradores da empresa',
                example: 'João da Silva, CPF 123.456.789-00, cargo de analista'
            },
            {
                name: 'Seus Clientes',
                description: 'Pessoas que compram seus produtos',
                example: 'Maria, cadastrou e-mail para receber newsletter'
            },
            {
                name: 'Parceiros/Fornecedores',
                description: 'Contatos de outras empresas',
                example: 'Representante comercial que você tem o WhatsApp salvo'
            }
        ],
        tips: [
            'Toda pessoa física é um titular - até visitantes do seu site!',
            'Titulares têm DIREITOS: pedir cópia dos dados, exclusão, correção, etc.',
            'Você tem 15 dias (corridos) para responder pedidos de titulares'
        ],
        law: 'Art. 5º, V e Art. 18 da LGPD'
    },
    'dados-sensiveis': {
        title: 'Dados Sensíveis',
        icon: '🔒',
        simpleExplanation: 'São dados "super privados" que podem causar discriminação. Ex: religião, saúde, orientação sexual.',
        detailedExplanation: 'Dados pessoais sensíveis são informações que podem gerar discriminação ou prejuízo ao titular. A lei protege com regras mais rígidas.',
        examples: [
            {
                name: 'Saúde',
                description: 'Qualquer informação médica',
                example: 'Atestado médico, exame de COVID, plano de saúde'
            },
            {
                name: 'Convicção Religiosa',
                description: 'Religião ou crença da pessoa',
                example: '"Preciso de alimentação kosher" (indica judaísmo)'
            },
            {
                name: 'Orientação Sexual',
                description: 'Identidade de gênero ou orientação',
                example: 'Cadastro informando "casal homoafetivo"'
            },
            {
                name: 'Dados Biométricos',
                description: 'Digital, face, íris para identificação',
                example: 'Relógio de ponto com digital'
            }
        ],
        tips: [
            'NUNCA colete dado sensível "só porque sim" - precisa ter justificativa FORTE',
            'A base legal geralmente é Consentimento ESPECÍFICO ou Obrigação Legal',
            'Armazene com criptografia SEMPRE. Acesso ultra-restrito.'
        ],
        law: 'Art. 5º, II e Art. 11 da LGPD'
    },
    'dpo': {
        title: 'DPO (Encarregado de Dados)',
        icon: '🛡️',
        simpleExplanation: 'É a pessoa responsável pela LGPD na sua empresa. O "xerife" dos dados pessoais.',
        detailedExplanation: 'O Data Protection Officer (DPO) ou Encarregado é quem cuida da proteção de dados na empresa e faz a ponte com a ANPD e titulares.',
        examples: [
            {
                name: 'Atende Titulares',
                description: 'Responde pedidos de exclusão, cópia de dados, etc',
                example: 'Cliente envia e-mail pedindo exclusão → DPO processa'
            },
            {
                name: 'Treina a Equipe',
                description: 'Ensina os colaboradores sobre LGPD',
                example: 'Faz workshop "Como NÃO vazar dados" para o time'
            },
            {
                name: 'Fala com a ANPD',
                description: 'É o contato oficial com o órgão fiscalizador',
                example: 'ANPD solicita informações → DPO responde oficialmente'
            }
        ],
        tips: [
            'Pode ser funcionário interno OU consultoria externa',
            'O e-mail/canal do DPO deve estar na Política de Privacidade',
            'NÃO pode ser punido por fazer o trabalho dele (tem proteção legal)'
        ],
        law: 'Art. 5º, VIII e Art. 41 da LGPD'
    },
    'ropa': {
        title: 'ROPA (Inventário de Dados)',
        icon: '📋',
        simpleExplanation: 'É um "mapa" de todos os dados pessoais que sua empresa usa. Tipo um cadastro completo.',
        detailedExplanation: 'Record of Processing Activities (ROPA) ou Registro de Operações de Tratamento documenta todos os dados pessoais que você coleta, usa, armazena e compartilha.',
        examples: [
            {
                name: 'Folha de Pagamento',
                description: 'Dados de RH para pagar salários',
                example: 'CPF, banco, cargo → Base Legal: Contrato de Trabalho'
            },
            {
                name: 'Newsletter Marketing',
                description: 'E-mails para envio de campanhas',
                example: 'E-mail, nome → Base Legal: Consentimento'
            },
            {
                name: 'Sistema de Vendas',
                description: 'Cadastro de clientes',
                example: 'Nome, telefone, endereço → Base Legal: Execução de Contrato'
            }
        ],
        tips: [
            'Mapeie TUDO - até aquela planilha "esquecida" do Excel',
            'Revise o ROPA a cada 6 meses (ou quando mudar processo)',
            'A ANPD pode pedir seu ROPA a qualquer momento - mantenha atualizado!'
        ],
        law: 'Art. 37 da LGPD'
    },
    'dsar': {
        title: 'DSAR (Pedido do Titular)',
        icon: '📨',
        simpleExplanation: 'É quando uma pessoa pede algo sobre os dados dela: ver, corrigir ou apagar.',
        detailedExplanation: 'Data Subject Access Request (DSAR) são as solicitações que titulares fazem exercendo seus direitos garantidos pela LGPD.',
        examples: [
            {
                name: 'Direito de Acesso',
                description: 'Pessoa quer saber quais dados você tem dela',
                example: '"Me mande todos os meus dados cadastrados aí"'
            },
            {
                name: 'Direito de Exclusão',
                description: 'Pessoa quer que você apague os dados',
                example: '"Quero deletar minha conta e todos meus dados"'
            },
            {
                name: 'Direito de Portabilidade',
                description: 'Pessoa quer levar os dados para outro serviço',
                example: '"Me envie meu histórico em CSV para migrar"'
            },
            {
                name: 'Direito de Correção',
                description: 'Pessoa quer corrigir informação errada',
                example: '"Meu telefone está desatualizado, trocar para..."'
            }
        ],
        tips: [
            'Você tem 15 DIAS CORRIDOS para responder (prazo legal)',
            'Confirme a identidade antes de enviar dados (evite fraudes)',
            'Documente TUDO - print, e-mail, quando atendeu, etc.'
        ],
        law: 'Art. 18 da LGPD'
    },
    'cookies': {
        title: 'Cookies',
        icon: '🍪',
        simpleExplanation: 'São "arquivinhos" que sites guardam no seu navegador para lembrar de você.',
        detailedExplanation: 'Cookies são pequenos arquivos de texto que sites armazenam no navegador do usuário para funcionalidades, análise ou publicidade.',
        examples: [
            {
                name: 'Cookies Necessários',
                description: 'Essenciais para o site funcionar',
                example: 'Manter você logado, carrinho de compras'
            },
            {
                name: 'Cookies Analíticos',
                description: 'Para medir visitantes e comportamento',
                example: 'Google Analytics - quantas pessoas visitam o site'
            },
            {
                name: 'Cookies de Marketing',
                description: 'Para anúncios personalizados',
                example: 'Facebook Pixel - mostrar anúncios no Instagram'
            }
        ],
        tips: [
            'Cookies Necessários: NÃO precisa de consentimento',
            'Outros cookies: SIM, precisa pedir permissão (banner)',
            'Tenha uma Política de Cookies clara e acessível'
        ],
        law: 'Art. 7º, I da LGPD + Marco Civil da Internet'
    },
    'incidente': {
        title: 'Incidente de Segurança',
        icon: '🚨',
        simpleExplanation: 'É quando algo dá errado e dados pessoais vazam, são roubados ou perdidos.',
        detailedExplanation: 'Incidente de segurança é qualquer situação que compromete a confidencialidade, integridade ou disponibilidade de dados pessoais.',
        examples: [
            {
                name: 'Vazamento',
                description: 'Dados expostos publicamente',
                example: 'Banco de dados fica aberto na internet sem senha'
            },
            {
                name: 'Ransomware',
                description: 'Hackers criptografam e pedem resgate',
                example: 'Vírus bloqueia sistema e exige Bitcoin'
            },
            {
                name: 'Erro Humano',
                description: 'Funcionário envia dados por engano',
                example: 'E-mail com planilha de CPFs enviado para pessoa errada'
            }
        ],
        tips: [
            'Você tem "prazo razoável" para avisar a ANPD (geralmente 2-72h)',
            'Se for grave, TAMBÉM avise os titulares afetados',
            'Tenha um "Plano de Resposta a Incidentes" ANTES de acontecer'
        ],
        law: 'Art. 48 da LGPD'
    }
};

const content = computed(() => helpContent[props.topic] || {
    title: 'Ajuda não disponível',
    icon: '❓',
    simpleExplanation: 'Conteúdo de ajuda não encontrado para este tópico.',
    detailedExplanation: 'Entre em contato com o suporte para mais informações.',
    examples: [],
    tips: [],
    law: ''
});

const openHelp = () => {
    showModal.value = true;
};
</script>

<template>
    <div>
        <!-- Botão de Ajuda -->
        <button
            @click="openHelp"
            :class="[
                'transition-all duration-200',
                position === 'floating' 
                    ? 'fixed bottom-8 right-8 w-16 h-16 rounded-full shadow-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 z-50' 
                    : 'inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200'
            ]"
            type="button"
            :title="`Ajuda: ${content.title}`"
        >
            <svg 
                class="transition-transform hover:scale-110" 
                :class="position === 'floating' ? 'w-8 h-8 text-white' : 'w-4 h-4'"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>

        <!-- Modal de Ajuda -->
        <Modal :show="showModal" @close="showModal = false" max-width="3xl">
            <div class="p-8">
                <!-- Header -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-4xl shadow-lg">
                        {{ content.icon }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-black text-slate-900 mb-2">{{ content.title }}</h2>
                        <p class="text-lg text-slate-600 leading-relaxed">{{ content.simpleExplanation }}</p>
                    </div>
                </div>

                <!-- Explicação Detalhada -->
                <div class="mb-8 p-6 bg-blue-50 rounded-2xl border-2 border-blue-100">
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Entendendo Melhor
                    </h3>
                    <p class="text-slate-700 leading-relaxed">{{ content.detailedExplanation }}</p>
                </div>

                <!-- Exemplos Práticos -->
                <div v-if="content.examples?.length" class="mb-8">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Exemplos na Prática
                    </h3>
                    <div class="grid gap-4">
                        <div 
                            v-for="(example, index) in content.examples"
                            :key="index"
                            class="p-4 bg-white rounded-xl border-2 border-slate-200 hover:border-indigo-200 transition-colors"
                        >
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-sm">
                                    {{ index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 mb-1">{{ example.name }}</h4>
                                    <p class="text-sm text-slate-600 mb-2">{{ example.description }}</p>
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                        <p class="text-sm text-slate-700 italic">💡 {{ example.example }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dicas Importantes -->
                <div v-if="content.tips?.length" class="mb-8">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Dicas Importantes
                    </h3>
                    <div class="space-y-3">
                        <div 
                            v-for="(tip, index) in content.tips"
                            :key="index"
                            class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border-2 border-amber-200"
                        >
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-slate-700 font-medium">{{ tip }}</p>
                        </div>
                    </div>
                </div>

                <!-- Referência Legal -->
                <div v-if="content.law" class="p-4 bg-slate-900 text-white rounded-xl">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-1">Base Legal</p>
                    <p class="font-semibold">{{ content.law }}</p>
                </div>

                <!-- Botão Fechar -->
                <div class="mt-8 flex justify-end">
                    <button
                        @click="showModal = false"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-indigo-200"
                    >
                        Entendi! Fechar
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
