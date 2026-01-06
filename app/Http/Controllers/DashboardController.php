<?php

namespace App\Http\Controllers;

use App\Services\ComplianceScoreService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Request as DSARRequest;
use App\Models\DataInventory;

class DashboardController extends Controller
{
    protected ComplianceScoreService $complianceService;

    public function __construct(ComplianceScoreService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return Inertia::render('Dashboard', [
                'error' => 'Empresa não encontrada. Entre em contato com o administrador.'
            ]);
        }

        // Calcular score de adequação
        $complianceData = $this->complianceService->calculateScore($company);

        // DSAR Pendentes
        $dsarPending = DSARRequest::where('company_id', $company->id)
            ->where('status', 'pendente')
            ->orderBy('prazo_legal', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($dsar) {
                $daysRemaining = now()->diffInDays($dsar->prazo_legal, false);
                return [
                    'id' => $dsar->id,
                    'protocolo' => $dsar->protocolo,
                    'tipo' => $dsar->tipo,
                    'nome_titular' => $dsar->nome_titular,
                    'prazo_legal' => $dsar->prazo_legal->format('d/m/Y'),
                    'days_remaining' => round($daysRemaining),
                    'is_overdue' => $daysRemaining < 0,
                    'urgency' => $this->getUrgency($daysRemaining),
                ];
            });

        // Estatísticas Rápidas
        $stats = [
            'total_inventories' => DataInventory::where('company_id', $company->id)->count(),
            'total_dsar' => DSARRequest::where('company_id', $company->id)->count(),
            'pending_dsar' => DSARRequest::where('company_id', $company->id)->where('status', 'pendente')->count(),
            'overdue_dsar' => DSARRequest::where('company_id', $company->id)
                ->where('status', 'pendente')
                ->where('prazo_legal', '<', now())
                ->count(),
        ];

        return Inertia::render('Dashboard', [
            'compliance' => $complianceData,
            'dsarPending' => $dsarPending,
            'stats' => $stats,
        ]);
    }

    /**
     * Determina a urgência com base nos dias restantes
     */
    private function getUrgency(float $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'overdue'; // Atrasado
        } elseif ($daysRemaining <= 3) {
            return 'critical'; // Crítico (3 dias ou menos)
        } elseif ($daysRemaining <= 7) {
            return 'high'; // Alto (1 semana)
        } else {
            return 'normal'; // Normal
        }
    }
}
