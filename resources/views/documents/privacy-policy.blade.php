<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - {{ $company->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        h1 {
            color: #4f46e5;
            font-size: 24pt;
            margin-bottom: 10px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 10px;
        }
        h2 {
            color: #4f46e5;
            font-size: 16pt;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 4px solid #4f46e5;
            padding-left: 10px;
        }
        h3 {
            color: #1e293b;
            font-size: 13pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            text-align: justify;
            margin-bottom: 15px;
        }
        ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        li {
            margin-bottom: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: #f1f5f9;
            border-radius: 8px;
        }
        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 14pt;
            color: #64748b;
            margin-bottom: 5px;
        }
        .generated-date {
            font-size: 9pt;
            color: #94a3b8;
            font-style: italic;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 9pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .highlight-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
        .data-category {
            background: #dbeafe;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin: 5px;
            font-size: 10pt;
        }
        .section {
            page-break-inside: avoid;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background: #4f46e5;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10pt;
        }
        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company->name }}</div>
        <div class="document-title">POLÍTICA DE PRIVACIDADE E PROTEÇÃO DE DADOS</div>
        <div class="generated-date">Documento gerado em: {{ $generated_at }}</div>
    </div>

    <div class="section">
        <h2>1. INTRODUÇÃO</h2>
        <p>
            A <strong>{{ $company->name }}</strong> ("Empresa", "nós" ou "nosso") respeita a privacidade de seus usuários, clientes e parceiros ("você" ou "titular"). 
            Esta Política de Privacidade descreve como coletamos, usamos, armazenamos e protegemos seus dados pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).
        </p>
        
        <div class="highlight-box">
            <strong>⚖️ Compromisso com a LGPD:</strong> Garantimos transparência no tratamento de dados pessoais e respeitamos todos os direitos previstos na legislação brasileira.
        </div>
    </div>

    <div class="section">
        <h2>2. DADOS PESSOAIS COLETADOS</h2>
        <p>Coletamos e processamos as seguintes categorias de dados pessoais:</p>
        
        @if(count($data_categories) > 0)
            <div style="margin: 20px 0;">
                @foreach($data_categories as $category => $count)
                    <span class="data-category">{{ ucfirst($category) }} ({{ $count }} processos)</span>
                @endforeach
            </div>
        @endif
        
        <h3>2.1. Dados Fornecidos Diretamente por Você</h3>
        <ul>
            <li><strong>Dados de identificação:</strong> nome completo, CPF, RG, data de nascimento</li>
            <li><strong>Dados de contato:</strong> e-mail, telefone, endereço</li>
            <li><strong>Dados profissionais:</strong> cargo, empresa, informações de trabalho</li>
            <li><strong>Dados financeiros:</strong> informações de pagamento (quando aplicável)</li>
        </ul>
        
        <h3>2.2. Dados Coletados Automaticamente</h3>
        <ul>
            <li><strong>Dados de navegação:</strong> endereço IP, tipo de navegador, páginas visitadas</li>
            <li><strong>Cookies e tecnologias similares:</strong> conforme nossa Política de Cookies</li>
            <li><strong>Dados de dispositivo:</strong> tipo de dispositivo, sistema operacional, identificadores únicos</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. FINALIDADES DO TRATAMENTO</h2>
        <p>Utilizamos seus dados pessoais para as seguintes finalidades:</p>
        <ul>
            <li><strong>Execução de contrato:</strong> fornecer produtos e serviços contratados</li>
            <li><strong>Cumprimento de obrigação legal:</strong> atender requisitos fiscais, trabalhistas e regulatórios</li>
            <li><strong>Legítimo interesse:</strong> prevenir fraudes, garantir segurança, melhorar nossos serviços</li>
            <li><strong>Consentimento:</strong> envio de comunicações de marketing (quando autorizado)</li>
            <li><strong>Exercício regular de direitos:</strong> defesa em processos judiciais ou administrativos</li>
        </ul>
    </div>

    <div class="section">
        <h2>4. COMPARTILHAMENTO DE DADOS</h2>
        <p>Seus dados pessoais podem ser compartilhados com:</p>
        <ul>
            <li><strong>Prestadores de serviços:</strong> empresas que nos auxiliam na prestação de serviços (hospedagem, pagamento, logística)</li>
            <li><strong>Autoridades públicas:</strong> quando exigido por lei ou ordem judicial</li>
            <li><strong>Parceiros comerciais:</strong> apenas com seu consentimento expresso</li>
        </ul>
        <p>
            <strong>Não vendemos seus dados pessoais para terceiros.</strong> Todos os compartilhamentos são realizados com base em contratos que garantem a proteção adequada dos seus dados.
        </p>
    </div>

    <div class="section">
        <h2>5. ARMAZENAMENTO E SEGURANÇA</h2>
        
        <h3>5.1. Localização dos Dados</h3>
        <p>Seus dados são armazenados em servidores localizados no Brasil e/ou em países com nível adequado de proteção de dados.</p>
        
        <h3>5.2. Medidas de Segurança</h3>
        <ul>
            <li>Criptografia de dados em trânsito e em repouso</li>
            <li>Controle de acesso restrito (autenticação e autorização)</li>
            <li>Firewalls e sistemas de detecção de intrusão</li>
            <li>Backup regular de dados</li>
            <li>Monitoramento contínuo de vulnerabilidades</li>
            <li>Treinamento de colaboradores em proteção de dados</li>
        </ul>
        
        <h3>5.3. Prazo de Retenção</h3>
        <p>Mantemos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades descritas nesta política ou conforme exigido por lei.</p>
    </div>

    <div class="section">
        <h2>6. SEUS DIREITOS (Art. 18 da LGPD)</h2>
        <p>Como titular de dados pessoais, você possui os seguintes direitos:</p>
        
        <table>
            <tr>
                <th>Direito</th>
                <th>Descrição</th>
            </tr>
            <tr>
                <td><strong>Confirmação e Acesso</strong></td>
                <td>Confirmar se tratamos seus dados e obter acesso a eles</td>
            </tr>
            <tr>
                <td><strong>Correção</strong></td>
                <td>Corrigir dados incompletos, inexatos ou desatualizados</td>
            </tr>
            <tr>
                <td><strong>Anonimização ou Exclusão</strong></td>
                <td>Solicitar anonimização ou eliminação de dados desnecessários</td>
            </tr>
            <tr>
                <td><strong>Portabilidade</strong></td>
                <td>Receber seus dados em formato estruturado e interoperável</td>
            </tr>
            <tr>
                <td><strong>Eliminação (Direito ao Esquecimento)</strong></td>
                <td>Solicitar exclusão de dados tratados com consentimento</td>
            </tr>
            <tr>
                <td><strong>Informação sobre Compartilhamento</strong></td>
                <td>Saber com quem compartilhamos seus dados</td>
            </tr>
            <tr>
                <td><strong>Revogação do Consentimento</strong></td>
                <td>Revogar consentimento a qualquer momento</td>
            </tr>
            <tr>
                <td><strong>Oposição</strong></td>
                <td>Opor-se ao tratamento realizado sem consentimento</td>
            </tr>
        </table>
        
        <div class="highlight-box">
            <strong>📧 Como exercer seus direitos:</strong><br>
            @if($company->email)
                E-mail: {{ $company->email }}<br>
            @endif
            @if($company->telefone)
                Telefone: {{ $company->telefone }}<br>
            @endif
            Portal DSAR: {{ config('app.url') }}/dsar?company={{ $company->slug }}<br>
            <em>Prazo de resposta: até 15 dias úteis (conforme Art. 19 da LGPD)</em>
        </div>
    </div>

    <div class="section">
        <h2>7. COOKIES</h2>
        <p>
            Utilizamos cookies e tecnologias similares para melhorar sua experiência. 
            Para mais informações, consulte nossa <strong>Política de Cookies</strong> disponível em nosso site.
        </p>
        
        @if($company->cookies->count() > 0)
            <p><strong>Cookies ativos:</strong> {{ $company->cookies->count() }} tipos de cookies catalogados</p>
        @endif
    </div>

    <div class="section">
        <h2>8. ENCARREGADO DE PROTEÇÃO DE DADOS (DPO)</h2>
        <p>Para questões relacionadas à proteção de dados pessoais, entre em contato com nosso Encarregado:</p>
        <ul>
            @if($company->dpo_name)
                <li><strong>Nome:</strong> {{ $company->dpo_name }}</li>
            @endif
            @if($company->dpo_email)
                <li><strong>E-mail:</strong> {{ $company->dpo_email }}</li>
            @endif
            @if($company->dpo_phone)
                <li><strong>Telefone:</strong> {{ $company->dpo_phone }}</li>
            @endif
        </ul>
    </div>

    <div class="section">
        <h2>9. ALTERAÇÕES NESTA POLÍTICA</h2>
        <p>
            Podemos atualizar esta Política de Privacidade periodicamente. Recomendamos que você revise regularmente para estar ciente de quaisquer mudanças. 
            Alterações significativas serão comunicadas através de nossos canais oficiais.
        </p>
        <p><strong>Última atualização:</strong> {{ $generated_at }}</p>
    </div>

    <div class="section">
        <h2>10. LEGISLAÇÃO APLICÁVEL</h2>
        <p>
            Esta Política de Privacidade é regida pela legislação brasileira, especialmente:
        </p>
        <ul>
            <li>Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018)</li>
            <li>Marco Civil da Internet (Lei nº 12.965/2014)</li>
            <li>Código de Defesa do Consumidor (Lei nº 8.078/1990)</li>
        </ul>
    </div>

    <div class="footer">
        {{ $company->name }} | Política de Privacidade | Gerado pelo sistema LGPDGo em {{ $generated_at }}
    </div>
</body>
</html>
