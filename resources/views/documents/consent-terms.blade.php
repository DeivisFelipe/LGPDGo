<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Termos de Consentimento - {{ $company->name }}</title>
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
            border-bottom: 3px solid #4f46e5;
        }
        .header h1 {
            color: #4f46e5;
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
            color: #4f46e5;
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e7ff;
        }
        h3 {
            color: #6366f1;
            font-size: 12pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            margin: 10px 0;
            text-align: justify;
        }
        .highlight {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 15px 0;
        }
        .consent-item {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .consent-item h4 {
            color: #334155;
            font-size: 11pt;
            margin: 0 0 10px 0;
        }
        .consent-item p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid #4f46e5;
            border-radius: 3px;
            vertical-align: middle;
            margin-right: 8px;
        }
        .cookie-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }
        .cookie-table th {
            background-color: #4f46e5;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .cookie-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
        }
        .cookie-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .signature-section {
            margin-top: 40px;
            padding: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
        }
        .signature-line {
            border-top: 2px solid #1e293b;
            margin-top: 50px;
            padding-top: 10px;
            text-align: center;
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
        <h1>📜 Termos de Consentimento</h1>
        <div class="company-name">{{ $company->name }}</div>
        <div class="date">Documento gerado em {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <h2>1. Introdução</h2>
    <p>
        Este documento apresenta os termos de consentimento para o tratamento de dados pessoais pela 
        <strong>{{ $company->name }}</strong>, em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei 13.709/2018).
    </p>
    
    <div class="highlight">
        <strong>⚠️ Importante:</strong> O consentimento é livre, informado e inequívoco. Você pode revogar seu 
        consentimento a qualquer momento através dos canais de atendimento informados neste documento.
    </div>

    <h2>2. Identificação do Controlador</h2>
    <p><strong>Razão Social:</strong> {{ $company->name }}</p>
    @if($company->cnpj)
        <p><strong>CNPJ:</strong> {{ $company->cnpj }}</p>
    @endif
    @if($company->address)
        <p><strong>Endereço:</strong> {{ $company->address }}</p>
    @endif
    @if($company->dpo_email)
        <p><strong>Encarregado de Dados (DPO):</strong> {{ $company->dpo_name ?? 'Não informado' }}</p>
        <p><strong>E-mail do DPO:</strong> {{ $company->dpo_email }}</p>
    @endif

    <h2>3. Finalidades do Tratamento de Dados</h2>
    <p>Seus dados pessoais serão tratados para as seguintes finalidades:</p>
    <ul>
        <li>Prestação de serviços contratados</li>
        <li>Cumprimento de obrigações legais e regulatórias</li>
        <li>Comunicação sobre produtos, serviços e novidades (se consentido)</li>
        <li>Melhoria da experiência do usuário</li>
        <li>Análise estatística e pesquisa de mercado (dados anonimizados)</li>
        <li>Prevenção de fraudes e proteção de direitos</li>
    </ul>

    <h2>4. Dados Coletados</h2>
    <p>Os seguintes tipos de dados poderão ser coletados e tratados:</p>
    <ul>
        <li>Dados de identificação (nome, CPF/CNPJ, RG)</li>
        <li>Dados de contato (e-mail, telefone, endereço)</li>
        <li>Dados de navegação (cookies, IP, logs de acesso)</li>
        <li>Dados financeiros (quando aplicável para processamento de pagamentos)</li>
        <li>Dados profissionais (quando relevante para a prestação de serviços)</li>
    </ul>

    <h2>5. Cookies e Tecnologias Similares</h2>
    <p>
        Utilizamos cookies para melhorar sua experiência em nossos serviços. Você pode gerenciar suas 
        preferências de cookies a qualquer momento.
    </p>

    @if($cookies->count() > 0)
        <h3>Cookies Utilizados</h3>
        <table class="cookie-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Finalidade</th>
                    <th>Duração</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cookies as $cookie)
                    <tr>
                        <td>{{ $cookie->nome }}</td>
                        <td>{{ ucfirst($cookie->categoria) }}</td>
                        <td>{{ $cookie->finalidade }}</td>
                        <td>{{ $cookie->duracao }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>6. Consentimentos Específicos</h2>
    <p>Para as seguintes atividades, solicitamos seu consentimento expresso:</p>

    <div class="consent-item">
        <h4><span class="checkbox"></span> Tratamento de Dados Pessoais</h4>
        <p>
            Concordo com o tratamento dos meus dados pessoais pela {{ $company->name }} para as 
            finalidades descritas neste documento.
        </p>
    </div>

    <div class="consent-item">
        <h4><span class="checkbox"></span> Cookies e Rastreamento</h4>
        <p>
            Concordo com a utilização de cookies e tecnologias similares para melhorar minha experiência 
            e personalizar os serviços oferecidos.
        </p>
    </div>

    <div class="consent-item">
        <h4><span class="checkbox"></span> Comunicações de Marketing (Opcional)</h4>
        <p>
            Concordo em receber comunicações sobre produtos, serviços, promoções e novidades da 
            {{ $company->name }} por e-mail, SMS ou outros canais de comunicação.
        </p>
        <p style="font-size: 9pt; color: #64748b;">
            <em>Este consentimento é opcional e não afeta a prestação dos serviços contratados.</em>
        </p>
    </div>

    <div class="consent-item">
        <h4><span class="checkbox"></span> Compartilhamento com Parceiros (Opcional)</h4>
        <p>
            Concordo com o compartilhamento dos meus dados com parceiros comerciais da {{ $company->name }} 
            para ofertas e serviços que possam ser do meu interesse.
        </p>
        <p style="font-size: 9pt; color: #64748b;">
            <em>Este consentimento é opcional e não afeta a prestação dos serviços contratados.</em>
        </p>
    </div>

    <h2>7. Direitos do Titular</h2>
    <p>Você possui os seguintes direitos sobre seus dados pessoais:</p>
    <ul>
        <li>Confirmação da existência de tratamento</li>
        <li>Acesso aos dados</li>
        <li>Correção de dados incompletos, inexatos ou desatualizados</li>
        <li>Anonimização, bloqueio ou eliminação de dados desnecessários</li>
        <li>Portabilidade dos dados</li>
        <li>Eliminação dos dados tratados com consentimento</li>
        <li>Informação sobre compartilhamento de dados</li>
        <li>Revogação do consentimento</li>
    </ul>

    <h2>8. Revogação do Consentimento</h2>
    <p>
        Você pode revogar seu consentimento a qualquer momento, sem comprometer a licitude do tratamento 
        realizado anteriormente. Para revogar seu consentimento, entre em contato através dos canais:
    </p>
    <ul>
        @if($company->dpo_email)
            <li><strong>E-mail:</strong> {{ $company->dpo_email }}</li>
        @endif
        @if($company->dpo_phone)
            <li><strong>Telefone:</strong> {{ $company->dpo_phone }}</li>
        @endif
        <li><strong>Portal DSAR:</strong> Através do nosso portal de solicitação de direitos do titular</li>
    </ul>

    <div class="highlight">
        <strong>📌 Prazo de Resposta:</strong> Suas solicitações serão respondidas em até 15 dias corridos, 
        conforme estabelecido pela LGPD.
    </div>

    <h2>9. Validade do Consentimento</h2>
    <p>
        Este consentimento é válido até sua revogação ou até o término da finalidade para a qual foi concedido, 
        respeitando sempre os prazos legais de retenção de dados quando aplicáveis.
    </p>

    <div class="signature-section">
        <h3>10. Assinatura e Aceitação</h3>
        <p>
            Ao aceitar estes termos, você declara ter lido e compreendido todas as informações apresentadas 
            e concorda com o tratamento de seus dados pessoais conforme descrito.
        </p>

        <div style="margin-top: 40px;">
            <p><strong>Nome do Titular:</strong> _________________________________________</p>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>CPF:</strong> _________________________________________</p>
        </div>

        <div class="signature-line">
            Assinatura do Titular
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <p><strong>Data:</strong> _____ / _____ / _________</p>
        </div>
    </div>

    <div class="footer">
        {{ $company->name }} - Termos de Consentimento LGPD - Documento gerado automaticamente em {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
