<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selo de Conformidade LGPD - {{ $company->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .widget-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .widget-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            text-align: center;
        }
        
        .widget-header h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .score-display {
            background: white;
            color: #1e293b;
            margin: 16px auto 0;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .score-number {
            font-size: 36px;
            font-weight: 700;
            line-height: 1;
        }
        
        .score-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .level-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }
        
        .level-excelente {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .level-bom {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .level-regular {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .level-critico {
            background-color: #fecaca;
            color: #991b1b;
        }
        
        .widget-body {
            padding: 24px;
        }
        
        .pillars-section {
            margin-top: 0;
        }
        
        .pillars-section h3 {
            font-size: 14px;
            color: #334155;
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .pillar-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .pillar-item:last-child {
            border-bottom: none;
        }
        
        .pillar-info {
            flex: 1;
        }
        
        .pillar-name {
            font-size: 13px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .pillar-progress {
            width: 100%;
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .pillar-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .pillar-score {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
            margin-left: 12px;
            min-width: 50px;
            text-align: right;
        }
        
        .widget-footer {
            background-color: #f8fafc;
            padding: 16px 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .verification-date {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        
        .powered-by {
            font-size: 10px;
            color: #cbd5e1;
            margin-top: 8px;
        }
        
        .powered-by a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .powered-by a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .widget-container {
                border-radius: 0;
            }
            
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="widget-container">
        <div class="widget-header">
            <h2>🔒 Conformidade LGPD</h2>
            <div class="company-name">{{ $company->name }}</div>
            
            <div class="score-display">
                <div class="score-number">{{ $compliance['score'] }}</div>
                <div class="score-label">Pontuação</div>
            </div>
            
            @php
                $score = $compliance['score'];
                $level_class = $score >= 90 ? 'excelente' : ($score >= 75 ? 'bom' : ($score >= 50 ? 'regular' : 'critico'));
                $level_text = $score >= 90 ? '🏆 Excelente' : ($score >= 75 ? '✓ Bom' : ($score >= 50 ? '⚠️ Regular' : '⚠️ Crítico'));
            @endphp
            
            <div class="level-badge level-{{ $level_class }}">
                {{ $level_text }}
            </div>
        </div>
        
        <div class="widget-body">
            <div class="pillars-section">
                <h3>📊 Detalhamento por Pilar</h3>
                
                @foreach($compliance['details'] as $pilar => $info)
                    @php
                        $percentage = $info['max_score'] > 0 ? round(($info['score'] / $info['max_score']) * 100) : 0;
                    @endphp
                    
                    <div class="pillar-item">
                        <div class="pillar-info">
                            <div class="pillar-name">{{ $info['name'] }}</div>
                            <div class="pillar-progress">
                                <div class="pillar-progress-bar" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </div>
                        <div class="pillar-score">
                            {{ $info['score'] }}/{{ $info['max_score'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="widget-footer">
            <div class="verification-date">
                ✓ Verificado em {{ now()->format('d/m/Y H:i') }}
            </div>
            <div class="powered-by">
                Powered by <a href="javascript:void(0)">LGPDGo</a>
            </div>
        </div>
    </div>
</body>
</html>
