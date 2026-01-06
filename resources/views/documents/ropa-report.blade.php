<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório ROPA - {{ $company->name }}</title>
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
            border-bottom: 3px solid #6366f1;
        }
        .header h1 {
            color: #6366f1;
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
        .summary-box {
            background-color: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box h3 {
            color: #334155;
            font-size: 11pt;
            margin: 0 0 10px 0;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            background-color: white;
            border: 1px solid #e2e8f0;
        }
        .summary-item .label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .summary-item .value {
            font-size: 16pt;
            font-weight: bold;
            color: #6366f1;
            margin-top: 5px;
        }
        .ropa-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
        }
        .ropa-table th {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 8pt;
            font-weight: bold;
        }
        .ropa-table td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            vertical-align: top;
        }
        .ropa-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .ropa-table tr:hover {
            background-color: #f1f5f9;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: bold;
            margin: 2px;
        }
        .badge-high {
            background-color: #fecaca;
            color: #991b1b;
        }
        .badge-medium {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-low {
            background-color: #d1fae5;
            color: #065f46;
        }
        .legal-basis {
            font-size: 7pt;
            color: #475569;
            font-style: italic;
        }
        .department-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7pt;
            font-weight: bold;
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
        .highlight-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin: 15px 0;
            font-size: 8pt;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Record of Processing Activities (ROPA)</h1>
        <div class="company-name">{{ $company->name }}</div>
        <div class="date">Relatório gerado em {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary-box">
        <h3>📈 Resumo do Inventário de Dados</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total de Processos</div>
                <div class="value">{{ $inventories->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Departamentos</div>
                <div class="value">{{ $departments->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Risco Alto</div>
                <div class="value" style="color: #dc2626;">{{ $inventories->where('nivel_risco', 'alto')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Risco Médio</div>
                <div class="value" style="color: #f59e0b;">{{ $inventories->where('nivel_risco', 'medio')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Risco Baixo</div>
                <div class="value" style="color: #10b981;">{{ $inventories->where('nivel_risco', 'baixo')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="highlight-box">
        <strong>⚠️ Importante:</strong> Este relatório documenta as atividades de processamento de dados pessoais 
        conforme exigido pelo Art. 37 da LGPD (Lei 13.709/2018). Mantenha este documento atualizado e disponível 
        para auditorias da ANPD.
    </div>

    @if($inventories->count() > 0)
        <table class="ropa-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Processo/<br>Atividade</th>
                    <th style="width: 12%;">Finalidade</th>
                    <th style="width: 10%;">Base Legal<br>(LGPD)</th>
                    <th style="width: 13%;">Categorias de<br>Dados</th>
                    <th style="width: 10%;">Categorias de<br>Titulares</th>
                    <th style="width: 8%;">Tempo de<br>Retenção</th>
                    <th style="width: 13%;">Medidas de<br>Segurança</th>
                    <th style="width: 12%;">Compartilhamento<br>de Dados</th>
                    <th style="width: 5%;">Risco</th>
                    <th style="width: 5%;">Depto.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                    <tr>
                        <td><strong>{{ $inventory->nome_processo }}</strong></td>
                        
                        <td>{{ $inventory->finalidade }}</td>
                        
                        <td>
                            <strong>{{ ucfirst(str_replace('_', ' ', $inventory->base_legal)) }}</strong>
                            <div class="legal-basis">
                                @switch($inventory->base_legal)
                                    @case('consentimento')
                                        Art. 7º, I
                                        @break
                                    @case('execucao_contrato')
                                        Art. 7º, V
                                        @break
                                    @case('obrigacao_legal')
                                        Art. 7º, II
                                        @break
                                    @case('legitimo_interesse')
                                        Art. 7º, IX
                                        @break
                                    @default
                                        LGPD
                                @endswitch
                            </div>
                        </td>
                        
                        <td>
                            @if($inventory->categoria_dados)
                                @php
                                    $categorias = is_string($inventory->categoria_dados) 
                                        ? json_decode($inventory->categoria_dados, true) 
                                        : $inventory->categoria_dados;
                                @endphp
                                @if(is_array($categorias))
                                    @foreach($categorias as $categoria)
                                        <span class="badge badge-low">{{ $categoria }}</span>
                                    @endforeach
                                @else
                                    {{ $inventory->categoria_dados }}
                                @endif
                            @endif
                        </td>
                        
                        <td>
                            @if($inventory->categoria_titular)
                                @php
                                    $titulares = is_string($inventory->categoria_titular) 
                                        ? json_decode($inventory->categoria_titular, true) 
                                        : $inventory->categoria_titular;
                                @endphp
                                @if(is_array($titulares))
                                    {{ implode(', ', $titulares) }}
                                @else
                                    {{ $inventory->categoria_titular }}
                                @endif
                            @endif
                        </td>
                        
                        <td><strong>{{ $inventory->tempo_retencao ?? 'Não definido' }}</strong></td>
                        
                        <td>
                            @if($inventory->medidas_seguranca)
                                @php
                                    $medidas = is_string($inventory->medidas_seguranca) 
                                        ? json_decode($inventory->medidas_seguranca, true) 
                                        : $inventory->medidas_seguranca;
                                @endphp
                                @if(is_array($medidas))
                                    <ul style="margin: 0; padding-left: 15px; font-size: 7pt;">
                                        @foreach($medidas as $medida)
                                            <li>{{ $medida }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $inventory->medidas_seguranca }}
                                @endif
                            @endif
                        </td>
                        
                        <td>
                            @if($inventory->destinatarios)
                                @php
                                    $destinatarios = is_string($inventory->destinatarios) 
                                        ? json_decode($inventory->destinatarios, true) 
                                        : $inventory->destinatarios;
                                @endphp
                                @if(is_array($destinatarios))
                                    {{ implode(', ', $destinatarios) }}
                                @else
                                    {{ $inventory->destinatarios }}
                                @endif
                            @else
                                <em style="color: #94a3b8;">Nenhum</em>
                            @endif
                        </td>
                        
                        <td style="text-align: center;">
                            @php
                                $risco = $inventory->nivel_risco ?? 'baixo';
                                $badgeClass = $risco === 'alto' ? 'badge-high' : ($risco === 'medio' ? 'badge-medium' : 'badge-low');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ strtoupper($risco) }}</span>
                        </td>
                        
                        <td style="text-align: center;">
                            @if($inventory->department)
                                <span class="department-badge">{{ $inventory->department->name }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #94a3b8;">
            <p style="font-size: 14pt;">📭 Nenhum processo de tratamento de dados cadastrado</p>
            <p style="font-size: 10pt;">Comece criando seu primeiro inventário de dados no sistema.</p>
        </div>
    @endif

    @if($departments->count() > 0)
        <div style="margin-top: 30px; page-break-before: always;">
            <h2 style="color: #6366f1; font-size: 14pt; border-bottom: 2px solid #e0e7ff; padding-bottom: 10px;">
                🏢 Processos por Departamento
            </h2>
            
            @foreach($departments as $department)
                @php
                    $dept_inventories = $inventories->where('department_id', $department->id);
                @endphp
                
                @if($dept_inventories->count() > 0)
                    <div style="margin: 20px 0; padding: 15px; background-color: #f8fafc; border-left: 4px solid #6366f1;">
                        <h3 style="color: #334155; margin: 0 0 10px 0;">
                            {{ $department->name }}
                            <span style="color: #64748b; font-weight: normal; font-size: 9pt;">
                                ({{ $dept_inventories->count() }} processo(s))
                            </span>
                        </h3>
                        
                        <table class="ropa-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Processo</th>
                                    <th style="width: 30%;">Finalidade</th>
                                    <th style="width: 15%;">Base Legal</th>
                                    <th style="width: 20%;">Categorias de Dados</th>
                                    <th style="width: 10%;">Nível de Risco</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dept_inventories as $inv)
                                    <tr>
                                        <td><strong>{{ $inv->nome_processo }}</strong></td>
                                        <td>{{ $inv->finalidade }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $inv->base_legal)) }}</td>
                                        <td>
                                            @if($inv->categoria_dados)
                                                @php
                                                    $cats = is_string($inv->categoria_dados) 
                                                        ? json_decode($inv->categoria_dados, true) 
                                                        : $inv->categoria_dados;
                                                @endphp
                                                @if(is_array($cats))
                                                    {{ implode(', ', $cats) }}
                                                @else
                                                    {{ $inv->categoria_dados }}
                                                @endif
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @php
                                                $r = $inv->nivel_risco ?? 'baixo';
                                                $bc = $r === 'alto' ? 'badge-high' : ($r === 'medio' ? 'badge-medium' : 'badge-low');
                                            @endphp
                                            <span class="badge {{ $bc }}">{{ strtoupper($r) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="footer">
        {{ $company->name }} - Relatório ROPA (Record of Processing Activities) - 
        Gerado em {{ now()->format('d/m/Y H:i') }} - 
        Página <span class="pageNumber"></span>
    </div>
</body>
</html>
