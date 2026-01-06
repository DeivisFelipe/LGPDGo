<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\ComplianceScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ComplianceBadgeController extends Controller
{
    protected $complianceService;

    public function __construct(ComplianceScoreService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    /**
     * Gera selo SVG de conformidade LGPD
     */
    public function badge($companySlug)
    {
        $company = Company::where('slug', $companySlug)->firstOrFail();
        $complianceData = $this->complianceService->calculateScore($company);
        
        $score = $complianceData['score'];
        $level = $this->getComplianceLevel($score);
        
        $svg = $this->generateBadgeSVG($company, $score, $level);
        
        return Response::make($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Gera widget HTML embedável
     */
    public function widget($companySlug)
    {
        $company = Company::where('slug', $companySlug)->firstOrFail();
        $complianceData = $this->complianceService->calculateScore($company);
        
        $score = $complianceData['score'];
        $level = $this->getComplianceLevel($score);
        
        $html = view('widgets.compliance-badge', [
            'company' => $company,
            'score' => $score,
            'level' => $level,
            'details' => $complianceData['details'],
        ])->render();
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-cache',
        ]);
    }

    /**
     * Retorna dados JSON do selo
     */
    public function json($companySlug)
    {
        $company = Company::where('slug', $companySlug)->firstOrFail();
        $complianceData = $this->complianceService->calculateScore($company);
        
        return response()->json([
            'company' => [
                'name' => $company->name,
                'slug' => $company->slug,
            ],
            'compliance' => [
                'score' => $complianceData['score'],
                'level' => $this->getComplianceLevel($complianceData['score']),
                'details' => $complianceData['details'],
                'next_steps' => $complianceData['next_steps'],
            ],
            'verified_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Determina nível de conformidade baseado no score
     */
    private function getComplianceLevel(int $score): array
    {
        if ($score >= 90) {
            return [
                'name' => 'Excelente',
                'color' => '#10b981',
                'emoji' => '🏆',
                'description' => 'Alta conformidade com a LGPD',
            ];
        } elseif ($score >= 75) {
            return [
                'name' => 'Bom',
                'color' => '#3b82f6',
                'emoji' => '✓',
                'description' => 'Boa conformidade com a LGPD',
            ];
        } elseif ($score >= 50) {
            return [
                'name' => 'Regular',
                'color' => '#f59e0b',
                'emoji' => '⚠️',
                'description' => 'Conformidade parcial - melhorias necessárias',
            ];
        } else {
            return [
                'name' => 'Crítico',
                'color' => '#ef4444',
                'emoji' => '⚠️',
                'description' => 'Baixa conformidade - ação urgente necessária',
            ];
        }
    }

    /**
     * Gera SVG do selo de conformidade
     */
    private function generateBadgeSVG(Company $company, int $score, array $level): string
    {
        $color = $level['color'];
        $emoji = $level['emoji'];
        $levelName = $level['name'];
        
        return <<<SVG
<svg width="200" height="240" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="badge-gradient" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" style="stop-color:{$color};stop-opacity:1" />
            <stop offset="100%" style="stop-color:{$color};stop-opacity:0.8" />
        </linearGradient>
        <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
            <feOffset dx="0" dy="2" result="offsetblur"/>
            <feComponentTransfer>
                <feFuncA type="linear" slope="0.3"/>
            </feComponentTransfer>
            <feMerge>
                <feMergeNode/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>
    </defs>
    
    <!-- Background -->
    <rect width="200" height="240" rx="12" fill="white" filter="url(#shadow)"/>
    
    <!-- Header -->
    <rect width="200" height="60" rx="12" fill="url(#badge-gradient)"/>
    
    <!-- LGPD Shield Icon -->
    <g transform="translate(70, 15)">
        <path d="M30,5 L45,10 L45,25 Q45,35 30,40 Q15,35 15,25 L15,10 Z" 
              fill="white" opacity="0.9"/>
        <text x="30" y="30" font-size="16" fill="{$color}" text-anchor="middle" font-weight="bold">✓</text>
    </g>
    
    <!-- Score Circle -->
    <circle cx="100" cy="110" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
    <circle cx="100" cy="110" r="45" fill="none" stroke="{$color}" stroke-width="8"
            stroke-dasharray="282.6" stroke-dashoffset="{(282.6 * (100 - $score) / 100)}"
            stroke-linecap="round" transform="rotate(-90 100 110)"/>
    
    <!-- Score Text -->
    <text x="100" y="105" font-size="32" font-weight="bold" fill="{$color}" text-anchor="middle">{$score}</text>
    <text x="100" y="125" font-size="12" fill="#6b7280" text-anchor="middle">pontos</text>
    
    <!-- Level Badge -->
    <rect x="50" y="165" width="100" height="28" rx="14" fill="{$color}"/>
    <text x="100" y="183" font-size="13" font-weight="bold" fill="white" text-anchor="middle">{$levelName}</text>
    
    <!-- LGPD Compliance Text -->
    <text x="100" y="210" font-size="11" fill="#374151" text-anchor="middle" font-weight="600">Conformidade LGPD</text>
    <text x="100" y="226" font-size="9" fill="#9ca3af" text-anchor="middle">Verificado em {(new \DateTime())->format('d/m/Y')}</text>
</svg>
SVG;
    }
}
