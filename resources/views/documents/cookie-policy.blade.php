<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Política de Cookies - {{ $company->name }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #f59e0b;
        }
        .header h1 {
            color: #f59e0b;
            font-size: 22pt;
            margin: 0 0 10px 0;
        }
        .header .company-name {
            font-size: 14pt;
            color: #64748b;
            margin: 5px 0;
        }
        .header .date {
            font-size: 10pt;
            color: #94a3b8;
        }
        h2 {
            color: #f59e0b;
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #fef3c7;
        }
        h3 {
            color: #fb923c;
            font-size: 12pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            margin: 10px 0;
            text-align: justify;
        }
        .highlight {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            margin: 15px 0;
        }
        .cookie-category {
            background-color: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .cookie-category h3 {
            margin-top: 0;
            color: #334155;
        }
        .cookie-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10pt;
        }
        .cookie-table th {
            background-color: #f59e0b;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .cookie-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }
        .cookie-table tr:nth-child(even) {
            background-color: #fffbeb;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-necessarios {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-funcionais {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-analytics {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-marketing {
            background-color: #fce7f3;
            color: #9f1239;
        }
        .consent-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .consent-box h3 {
            margin-top: 0;
            color: #78350f;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            padding: 10px;
            border-top: 1px solid #e2e8f0;
        }
        ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍪 Política de Cookies</h1>
        <div class="company-name">{{ $company->name }}</div>
        <div class="date">Última atualização: {{ now()->format('d/m/Y') }}</div>
    </div>

    <h2>1. O que são Cookies?</h2>
    <p>
        Cookies são pequenos arquivos de texto que são armazenados no seu dispositivo (computador, tablet ou celular) 
        quando você visita um website. Eles são amplamente utilizados para fazer os sites funcionarem de forma mais 
        eficiente, além de fornecer informações aos proprietários do site.
    </p>

    <div class="highlight">
        <strong>🔒 Sua Privacidade é Importante:</strong> A <strong>{{ $company->name }}</strong> respeita sua 
        privacidade e está comprometida com a transparência no uso de cookies, em conformidade com a LGPD 
        (Lei 13.709/2018) e o Marco Civil da Internet (Lei 12.965/2014).
    </div>

    <h2>2. Por que Usamos Cookies?</h2>
    <p>Utilizamos cookies para diversos propósitos:</p>
    <ul>
        <li><strong>Essenciais:</strong> Para garantir o funcionamento adequado do site e recursos de segurança</li>
        <li><strong>Funcionais:</strong> Para lembrar suas preferências e personalizar sua experiência</li>
        <li><strong>Analíticos:</strong> Para entender como você interage com nosso site e melhorar nossos serviços</li>
        <li><strong>Marketing:</strong> Para exibir anúncios relevantes (somente com seu consentimento)</li>
    </ul>

    <h2>3. Tipos de Cookies que Utilizamos</h2>

    @php
        $categorias = [
            'necessarios' => [
                'title' => '🔐 Cookies Necessários',
                'badge' => 'badge-necessarios',
                'description' => 'Estes cookies são essenciais para o funcionamento do site e não podem ser desativados. Eles geralmente são configurados apenas em resposta a ações feitas por você, como login, preenchimento de formulários ou configurações de privacidade.',
                'consent' => false
            ],
            'funcionais' => [
                'title' => '⚙️ Cookies Funcionais',
                'badge' => 'badge-funcionais',
                'description' => 'Estes cookies permitem que o site forneça funcionalidades aprimoradas e personalização, como lembrar suas preferências de idioma, região ou outras configurações.',
                'consent' => true
            ],
            'analytics' => [
                'title' => '📊 Cookies de Análise',
                'badge' => 'badge-analytics',
                'description' => 'Estes cookies nos ajudam a entender como os visitantes interagem com nosso site, coletando e relatando informações de forma anônima. Isso nos permite melhorar continuamente nossos serviços.',
                'consent' => true
            ],
            'marketing' => [
                'title' => '🎯 Cookies de Marketing',
                'badge' => 'badge-marketing',
                'description' => 'Estes cookies são usados para exibir anúncios relevantes para você e seus interesses. Eles também podem limitar o número de vezes que você vê um anúncio e ajudar a medir a eficácia de campanhas publicitárias.',
                'consent' => true
            ]
        ];
    @endphp

    @foreach($categorias as $categoria_key => $categoria_info)
        @php
            $cookies_categoria = $cookies_by_category[$categoria_key] ?? collect();
        @endphp

        <div class="cookie-category">
            <h3>
                {{ $categoria_info['title'] }}
                <span class="badge {{ $categoria_info['badge'] }}">
                    {{ $cookies_categoria->count() }} cookie(s)
                </span>
            </h3>
            
            <p>{{ $categoria_info['description'] }}</p>

            @if($categoria_info['consent'])
                <p style="font-size: 10pt; color: #64748b; margin-top: 10px;">
                    <em>⚠️ Estes cookies requerem seu consentimento prévio.</em>
                </p>
            @else
                <p style="font-size: 10pt; color: #64748b; margin-top: 10px;">
                    <em>✓ Estes cookies são necessários e sempre ativos.</em>
                </p>
            @endif

            @if($cookies_categoria->count() > 0)
                <table class="cookie-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Nome</th>
                            <th style="width: 35%;">Finalidade</th>
                            <th style="width: 15%;">Duração</th>
                            <th style="width: 15%;">Fornecedor</th>
                            <th style="width: 15%;">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cookies_categoria as $cookie)
                            <tr>
                                <td><strong>{{ $cookie->nome }}</strong></td>
                                <td>{{ $cookie->finalidade }}</td>
                                <td>{{ $cookie->duracao }}</td>
                                <td>{{ $cookie->fornecedor ?? 'Próprio' }}</td>
                                <td>{{ ucfirst($cookie->tipo ?? 'HTTP') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-size: 10pt; color: #94a3b8; font-style: italic;">
                    Nenhum cookie desta categoria está atualmente em uso.
                </p>
            @endif
        </div>
    @endforeach

    <h2>4. Gerenciamento de Cookies</h2>
    
    <div class="consent-box">
        <h3>🎛️ Como Gerenciar suas Preferências</h3>
        <p>
            Você tem controle total sobre os cookies que aceita. Pode gerenciar suas preferências de cookies 
            através do banner de consentimento que aparece quando você visita nosso site pela primeira vez.
        </p>
        <p>
            <strong>Para alterar suas preferências a qualquer momento:</strong>
        </p>
        <ul>
            <li>Acesse as configurações de cookies no rodapé do nosso site</li>
            <li>Selecione quais categorias de cookies você deseja aceitar ou rejeitar</li>
            <li>Salve suas preferências</li>
        </ul>
    </div>

    <h3>Configurações do Navegador</h3>
    <p>
        Você também pode gerenciar cookies diretamente através das configurações do seu navegador. Aqui estão 
        os links para os navegadores mais populares:
    </p>
    <ul>
        <li><strong>Google Chrome:</strong> Menu &gt; Configurações &gt; Privacidade e segurança &gt; Cookies</li>
        <li><strong>Mozilla Firefox:</strong> Menu &gt; Opções &gt; Privacidade e Segurança &gt; Cookies</li>
        <li><strong>Safari:</strong> Preferências &gt; Privacidade &gt; Cookies e dados de sites</li>
        <li><strong>Microsoft Edge:</strong> Configurações &gt; Privacidade e serviços &gt; Cookies</li>
    </ul>

    <div class="highlight">
        <strong>⚠️ Atenção:</strong> Desabilitar todos os cookies pode afetar a funcionalidade do site. 
        Alguns recursos podem não funcionar corretamente sem os cookies necessários.
    </div>

    <h2>5. Cookies de Terceiros</h2>
    <p>
        Além dos nossos próprios cookies, também podemos usar cookies de terceiros para relatar estatísticas de 
        uso do site, fornecer anúncios e assim por diante. Estes terceiros incluem:
    </p>
    <ul>
        <li>Serviços de análise (ex: Google Analytics)</li>
        <li>Plataformas de mídia social (ex: Facebook, LinkedIn)</li>
        <li>Redes de publicidade</li>
        <li>Provedores de conteúdo incorporado (ex: YouTube, Vimeo)</li>
    </ul>
    <p>
        Recomendamos que você consulte as políticas de privacidade desses terceiros para obter mais informações 
        sobre como eles usam cookies.
    </p>

    <h2>6. Duração dos Cookies</h2>
    <p>Os cookies que utilizamos podem ser:</p>
    <ul>
        <li>
            <strong>Cookies de Sessão:</strong> Temporários e expiram quando você fecha o navegador. 
            São usados para manter sua sessão ativa enquanto navega no site.
        </li>
        <li>
            <strong>Cookies Persistentes:</strong> Permanecem no seu dispositivo por um período determinado 
            (especificado na tabela de cookies) e são ativados cada vez que você visita o site.
        </li>
    </ul>

    <h2>7. Seus Direitos (LGPD)</h2>
    <p>De acordo com a LGPD, você tem os seguintes direitos em relação aos seus dados:</p>
    <ul>
        <li>Confirmação da existência de tratamento de dados</li>
        <li>Acesso aos dados coletados através de cookies</li>
        <li>Correção de dados incompletos ou desatualizados</li>
        <li>Eliminação dos dados tratados com seu consentimento</li>
        <li>Revogação do consentimento a qualquer momento</li>
        <li>Oposição ao tratamento de dados</li>
    </ul>

    <h2>8. Contato</h2>
    <p>
        Se você tiver dúvidas sobre nossa Política de Cookies ou sobre como gerenciamos seus dados, 
        entre em contato conosco:
    </p>
    <ul>
        @if($company->dpo_name && $company->dpo_email)
            <li><strong>Encarregado de Dados (DPO):</strong> {{ $company->dpo_name }}</li>
            <li><strong>E-mail:</strong> {{ $company->dpo_email }}</li>
        @endif
        @if($company->dpo_phone)
            <li><strong>Telefone:</strong> {{ $company->dpo_phone }}</li>
        @endif
        @if($company->address)
            <li><strong>Endereço:</strong> {{ $company->address }}</li>
        @endif
    </ul>

    <h2>9. Atualizações desta Política</h2>
    <p>
        Podemos atualizar esta Política de Cookies periodicamente para refletir mudanças em nossas práticas 
        ou por outros motivos operacionais, legais ou regulatórios. A data da última atualização está indicada 
        no início deste documento.
    </p>
    <p>
        Recomendamos que você revise esta política regularmente para se manter informado sobre como usamos cookies.
    </p>

    <div class="highlight">
        <strong>📅 Data de vigência:</strong> Esta política entra em vigor a partir de {{ now()->format('d/m/Y') }} 
        e substitui todas as versões anteriores.
    </div>

    <div class="footer">
        {{ $company->name }} - Política de Cookies - Documento gerado automaticamente em {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
