<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório DSAR - {{ $company->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #ec4899;
        }
        .header h1 {
            color: #ec4899;
            font-size: 18pt;
            margin: 0 0 8px 0;
        }
        .header .company-name {
            font-size: 12pt;
            color: #64748b;
            margin: 5px 0;
        }
        .header .date {
            font-size: 9pt;
            color: #94a3b8;
        }
        .header .period {
            font-size: 10pt;
            color: #475569;
            font-weight: bold;
            margin-top: 8px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-card {
            display: table-cell;
            background-color: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 0 5px;
        }
        .stat-card .label {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .stat-card .value {
            font-size: 20pt;
            font-weight: bold;
            color: #ec4899;
        }
        .stat-card .subtitle {
            font-size: 8pt;
            color: #94a3b8;
            margin-top: 5px;
        }
        .dsar-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 8pt;
        }
        .dsar-table th {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 8pt;
            font-weight: bold;
        }
        .dsar-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }
        .dsar-table tr:nth-child(even) {
            background-color: #fdf4ff;
        }
        .dsar-table tr:hover {
            background-color: #fae8ff;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pendente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-em_andamento {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-concluido {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-rejeitado {
            background-color: #fecaca;
            color: #991b1b;
        }
        .tipo-badge {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 7pt;
            font-weight: bold;
        }
        .priority-high {
            color: #dc2626;
            font-weight: bold;
        }
        .priority-normal {
            color: #64748b;
        }
        .highlight-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 15px 0;
            font-size: 8pt;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .summary-box h3 {
            color: #334155;
            font-size: 11pt;
            margin: 0 0 10px 0;
        }
        .summary-item {
            padding: 8px;
            margin: 5px 0;
            background-color: white;
            border-left: 3px solid #ec4899;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #94a3b8;
            padding: 8px;
            border-top: 1px solid #e2e8f0;
            background-color: white;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ec4899 0%, #f472b6 100%);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Relatório de Solicitações DSAR</h1>
        <div class="company-name">{{ $company->name }}</div>
        <div class="date">Relatório gerado em {{ now()->format('d/m/Y H:i') }}</div>
        @if(isset($start_date) && isset($end_date))
            <div class="period">
                Período: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} até 
                {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
            </div>
        @endif
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Total de Solicitações</div>
            <div class="value">{{ $requests->count() }}</div>
            <div class="subtitle">Registradas no período</div>
        </div>
        
        <div class="stat-card">
            <div class="label">Taxa de Conclusão</div>
            <div class="value">{{ $requests->count() > 0 ? round(($stats['by_status']['concluido'] ?? 0) / $requests->count() * 100) : 0 }}%</div>
            <div class="subtitle">{{ $stats['by_status']['concluido'] ?? 0 }} concluídas</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $requests->count() > 0 ? round(($stats['by_status']['concluido'] ?? 0) / $requests->count() * 100) : 0 }}%;"></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="label">Tempo Médio de Resposta</div>
            <div class="value" style="font-size: 16pt;">{{ number_format($stats['avg_response_time'], 1) }}</div>
            <div class="subtitle">dias úteis</div>
        </div>
        
        <div class="stat-card">
            <div class="label">Pendentes</div>
            <div class="value" style="color: #f59e0b;">{{ $stats['by_status']['pendente'] ?? 0 }}</div>
            <div class="subtitle">Aguardando ação</div>
        </div>
    </div>

    <div class="highlight-box">
        <strong>⚠️ Prazo Legal:</strong> De acordo com o Art. 18 da LGPD, as solicitações devem ser respondidas 
        em até <strong>15 dias corridos</strong>. Solicitações marcadas em vermelho ultrapassaram este prazo.
    </div>

    @if($stats['by_status'])
        <div class="summary-box">
            <h3>📊 Solicitações por Status</h3>
            @foreach($stats['by_status'] as $status => $count)
                <div class="summary-item">
                    <span class="badge badge-{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    <strong style="margin-left: 15px;">{{ $count }}</strong> solicitações 
                    <span style="color: #64748b;">
                        ({{ $requests->count() > 0 ? round($count / $requests->count() * 100, 1) : 0 }}%)
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    @if($stats['by_type'])
        <div class="summary-box">
            <h3>🎯 Solicitações por Tipo</h3>
            @foreach($stats['by_type'] as $tipo => $count)
                <div class="summary-item">
                    <span class="tipo-badge">{{ ucfirst(str_replace('_', ' ', $tipo)) }}</span>
                    <strong style="margin-left: 15px;">{{ $count }}</strong> solicitações
                    <span style="color: #64748b;">
                        ({{ $requests->count() > 0 ? round($count / $requests->count() * 100, 1) : 0 }}%)
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    @if($requests->count() > 0)
        <h2 style="color: #ec4899; font-size: 14pt; margin-top: 30px; border-bottom: 2px solid #fce7f3; padding-bottom: 10px;">
            📋 Detalhamento das Solicitações
        </h2>

        <table class="dsar-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Protocolo</th>
                    <th style="width: 12%;">Tipo</th>
                    <th style="width: 15%;">Titular</th>
                    <th style="width: 12%;">E-mail</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 10%;">Data</th>
                    <th style="width: 8%;">Prazo</th>
                    <th style="width: 8%;">Dias Úteis</th>
                    <th style="width: 17%;">Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    @php
                        $prazo_dias = \Carbon\Carbon::parse($request->prazo_resposta)->diffInDays(now());
                        $prazo_vencido = \Carbon\Carbon::parse($request->prazo_resposta)->isPast() && $request->status !== 'concluido';
                        $dias_uteis = \Carbon\Carbon::parse($request->created_at)->diffInDaysFiltered(function($date) {
                            return !$date->isWeekend();
                        }, $request->data_conclusao ? \Carbon\Carbon::parse($request->data_conclusao) : now());
                    @endphp
                    <tr style="{{ $prazo_vencido ? 'background-color: #fef2f2;' : '' }}">
                        <td>
                            <strong>{{ $request->protocolo }}</strong>
                        </td>
                        
                        <td>
                            <span class="tipo-badge">
                                {{ ucfirst(str_replace('_', ' ', $request->tipo)) }}
                            </span>
                        </td>
                        
                        <td><strong>{{ $request->nome_titular }}</strong></td>
                        
                        <td style="font-size: 7pt;">{{ $request->email_titular }}</td>
                        
                        <td style="text-align: center;">
                            <span class="badge badge-{{ $request->status }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                        
                        <td class="{{ $prazo_vencido ? 'priority-high' : 'priority-normal' }}">
                            {{ \Carbon\Carbon::parse($request->prazo_resposta)->format('d/m/Y') }}
                            @if($prazo_vencido)
                                <br><strong>⚠️ VENCIDO</strong>
                            @endif
                        </td>
                        
                        <td style="text-align: center;">
                            <strong>{{ $dias_uteis }}</strong> dias
                        </td>
                        
                        <td style="font-size: 7pt;">
                            {{ Str::limit($request->descricao ?? 'Sem descrição', 80) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #94a3b8;">
            <p style="font-size: 14pt;">📭 Nenhuma solicitação encontrada</p>
            <p style="font-size: 10pt;">Não há solicitações DSAR registradas no período selecionado.</p>
        </div>
    @endif

    @if($requests->count() > 0)
        <div style="margin-top: 30px; page-break-before: always;">
            <h2 style="color: #ec4899; font-size: 14pt; border-bottom: 2px solid #fce7f3; padding-bottom: 10px;">
                📈 Análise de Desempenho
            </h2>

            <div class="summary-box">
                <h3>⏱️ Cumprimento de Prazos</h3>
                @php
                    $within_deadline = $requests->filter(function($r) {
                        return $r->status === 'concluido' && 
                               ($r->data_conclusao ? \Carbon\Carbon::parse($r->data_conclusao)->lte(\Carbon\Carbon::parse($r->prazo_resposta)) : false);
                    })->count();
                    
                    $concluidas = $requests->where('status', 'concluido')->count();
                    $taxa_cumprimento = $concluidas > 0 ? round($within_deadline / $concluidas * 100, 1) : 0;
                @endphp
                
                <div class="summary-item">
                    <strong>Solicitações Concluídas Dentro do Prazo:</strong> 
                    {{ $within_deadline }} de {{ $concluidas }} 
                    <span style="color: {{ $taxa_cumprimento >= 80 ? '#10b981' : ($taxa_cumprimento >= 50 ? '#f59e0b' : '#dc2626') }};">
                        ({{ $taxa_cumprimento }}%)
                    </span>
                </div>

                @php
                    $pendentes_vencidas = $requests->filter(function($r) {
                        return $r->status !== 'concluido' && \Carbon\Carbon::parse($r->prazo_resposta)->isPast();
                    })->count();
                @endphp
                
                @if($pendentes_vencidas > 0)
                    <div class="summary-item" style="border-left-color: #dc2626; background-color: #fef2f2;">
                        <strong style="color: #dc2626;">⚠️ ATENÇÃO:</strong> 
                        {{ $pendentes_vencidas }} solicitação(ões) pendente(s) com prazo vencido
                    </div>
                @endif
            </div>

            <div class="summary-box">
                <h3>💡 Recomendações</h3>
                @if($taxa_cumprimento >= 80)
                    <div class="summary-item" style="border-left-color: #10b981;">
                        ✅ <strong>Excelente desempenho!</strong> A equipe está cumprindo os prazos legais adequadamente.
                    </div>
                @elseif($taxa_cumprimento >= 50)
                    <div class="summary-item" style="border-left-color: #f59e0b;">
                        ⚠️ <strong>Atenção necessária:</strong> Alguns prazos não estão sendo cumpridos. Considere otimizar o processo de resposta.
                    </div>
                @else
                    <div class="summary-item" style="border-left-color: #dc2626;">
                        ⛔ <strong>Ação urgente necessária:</strong> Alto índice de descumprimento de prazos. Revise o fluxo de trabalho imediatamente.
                    </div>
                @endif

                @if($stats['by_status']['pendente'] ?? 0 > 5)
                    <div class="summary-item" style="border-left-color: #f59e0b;">
                        📌 Alto volume de solicitações pendentes. Considere alocar mais recursos para processamento.
                    </div>
                @endif

                @if($stats['avg_response_time'] > 10)
                    <div class="summary-item" style="border-left-color: #f59e0b;">
                        ⏰ Tempo médio de resposta acima de 10 dias. Busque formas de agilizar o atendimento.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="footer">
        {{ $company->name }} - Relatório DSAR (Data Subject Access Requests) - 
        Gerado em {{ now()->format('d/m/Y H:i') }} - 
        Página <span class="pageNumber"></span>
    </div>
</body>
</html>
