<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Conformidade LGPD - {{ $company->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }
        h1 { font-size: 20pt; margin: 0 0 10px 0; }
        h2 { color: #4f46e5; font-size: 14pt; margin-top: 25px; border-bottom: 2px solid #4f46e5; padding-bottom: 5px; }
        h3 { color: #1e293b; font-size: 12pt; margin-top: 15px; }
        .score-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .score-big {
            font-size: 48pt;
            font-weight: bold;
            color: #4f46e5;
            margin: 10px 0;
        }
        .level-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
            margin-top: 10px;
        }
        .level-excellent { background: #10b981; }
        .level-good { background: #3b82f6; }
        .level-regular { background: #f59e0b; }
        .level-critical { background: #ef4444; }
        .pillar-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .pillar-row {
            display: table-row;
        }
        .pillar-cell {
            display: table-cell;
            padding: 15px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .pillar-header {
            background: #4f46e5;
            color: white;
            font-weight: bold;
        }
        .pillar-score {
            font-size: 18pt;
            font-weight: bold;
            color: #4f46e5;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
            transition: width 0.3s;
        }
        .next-steps {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
        .next-steps ul {
            margin: 10px 0 0 20px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9pt;
            color: #94a3b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
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
        <h1>RELATÓRIO DE CONFORMIDADE LGPD</h1>
        <div>{{ $company->name }}</div>
        <div style="font-size: 10pt; margin-top: 5px;">Gerado em: {{ $generated_at }}</div>
    </div>

    <div class="score-card">
        <div style="font-size: 14pt; color: #64748b; margin-bottom: 10px;">SCORE GERAL DE CONFORMIDADE</div>
        <div class="score-big">{{ $compliance['score'] }}</div>
        <div style="font-size: 12pt; color: #64748b;">de 100 pontos</div>
        
        @php
            $level = $compliance['score'] >= 90 ? 'excellent' : ($compliance['score'] >= 75 ? 'good' : ($compliance['score'] >= 50 ? 'regular' : 'critical'));
            $levelText = $compliance['score'] >= 90 ? 'Excelente' : ($compliance['score'] >= 75 ? 'Bom' : ($compliance['score'] >= 50 ? 'Regular' : 'Crítico'));
        @endphp
        
        <div class="level-badge level-{{ $level }}">{{ $levelText }}</div>
        
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $compliance['score'] }}%"></div>
        </div>
    </div>

    <h2>📊 AVALIAÇÃO POR PILAR</h2>
    
    <table>
        <thead>
            <tr>
                <th>Pilar</th>
                <th style="text-align: center;">Pontuação</th>
                <th style="text-align: center;">Máximo</th>
                <th style="text-align: center;">%</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>🗂️ ROPA - Inventário de Dados</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['ropa']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['ropa']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['ropa']['score'] / $compliance['details']['ropa']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['ropa']['status'] }}</td>
            </tr>
            <tr>
                <td><strong>📨 DSAR - Solicitações de Titulares</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['dsar']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['dsar']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['dsar']['score'] / $compliance['details']['dsar']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['dsar']['status'] }}</td>
            </tr>
            <tr>
                <td><strong>⚠️ Gestão de Riscos</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['risks']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['risks']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['risks']['score'] / $compliance['details']['risks']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['risks']['status'] }}</td>
            </tr>
            <tr>
                <td><strong>🍪 Cookies e Consentimento</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['cookies']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['cookies']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['cookies']['score'] / $compliance['details']['cookies']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['cookies']['status'] }}</td>
            </tr>
            <tr>
                <td><strong>📚 Treinamentos</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['training']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['training']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['training']['score'] / $compliance['details']['training']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['training']['status'] }}</td>
            </tr>
            <tr>
                <td><strong>🏢 Estrutura Organizacional</strong></td>
                <td style="text-align: center;">{{ $compliance['details']['organization']['score'] }}</td>
                <td style="text-align: center;">{{ $compliance['details']['organization']['max_score'] }}</td>
                <td style="text-align: center;">{{ round(($compliance['details']['organization']['score'] / $compliance['details']['organization']['max_score']) * 100) }}%</td>
                <td>{{ $compliance['details']['organization']['status'] }}</td>
            </tr>
        </tbody>
    </table>

    <h2>🎯 DETALHAMENTO DOS PILARES</h2>

    <h3>1. ROPA - Inventário de Dados ({{ $compliance['details']['ropa']['score'] }}/{{ $compliance['details']['ropa']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['ropa']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    <h3>2. DSAR - Solicitações de Titulares ({{ $compliance['details']['dsar']['score'] }}/{{ $compliance['details']['dsar']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['dsar']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    <h3>3. Gestão de Riscos ({{ $compliance['details']['risks']['score'] }}/{{ $compliance['details']['risks']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['risks']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    <h3>4. Cookies e Consentimento ({{ $compliance['details']['cookies']['score'] }}/{{ $compliance['details']['cookies']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['cookies']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    <h3>5. Treinamentos ({{ $compliance['details']['training']['score'] }}/{{ $compliance['details']['training']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['training']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    <h3>6. Estrutura Organizacional ({{ $compliance['details']['organization']['score'] }}/{{ $compliance['details']['organization']['max_score'] }} pontos)</h3>
    <ul>
        @foreach($compliance['details']['organization']['details'] as $detail)
            <li>{{ $detail }}</li>
        @endforeach
    </ul>

    @if(count($compliance['next_steps']) > 0)
        <div class="next-steps">
            <h3 style="margin-top: 0;">🚀 PRÓXIMOS PASSOS RECOMENDADOS</h3>
            <ul>
                @foreach($compliance['next_steps'] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>📋 RESUMO EXECUTIVO</h2>
    <p>
        A <strong>{{ $company->name }}</strong> apresenta um score de conformidade de <strong>{{ $compliance['score'] }} pontos</strong>,
        classificado como <strong>{{ $levelText }}</strong>.
    </p>
    
    @if($compliance['score'] >= 75)
        <p>
            A empresa demonstra boa adequação à LGPD, com processos estabelecidos nos principais pilares de conformidade.
            Recomenda-se manter as boas práticas e buscar melhorias contínuas.
        </p>
    @else
        <p>
            É recomendado priorizar as ações listadas em "Próximos Passos" para elevar o nível de conformidade e 
            reduzir riscos de não-conformidade com a legislação.
        </p>
    @endif

    <h2>⚖️ BASE LEGAL</h2>
    <p>Este relatório foi elaborado com base nos requisitos da:</p>
    <ul>
        <li><strong>Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018)</strong></li>
        <li>Guia de Boas Práticas da ANPD (Autoridade Nacional de Proteção de Dados)</li>
        <li>Normas ISO/IEC 27001 e 27701 (gestão de segurança da informação e privacidade)</li>
    </ul>

    <div class="footer">
        <strong>{{ $company->name }}</strong><br>
        Relatório de Conformidade LGPD<br>
        Gerado automaticamente pelo sistema LGPDGo em {{ $generated_at }}<br>
        <em>Este documento é confidencial e destinado exclusivamente ao uso interno da empresa.</em>
    </div>
</body>
</html>
