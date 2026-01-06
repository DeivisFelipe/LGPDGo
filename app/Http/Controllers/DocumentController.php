<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DataInventory;
use App\Models\Cookie;
use App\Models\Request as DsarRequest;
use App\Services\ComplianceScoreService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
{
    protected $complianceService;

    public function __construct(ComplianceScoreService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    /**
     * Display document generation page
     */
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        $stats = [
            'ropa_count' => DataInventory::where('company_id', $company->id)->count(),
            'cookies_count' => Cookie::where('company_id', $company->id)->count(),
            'dsar_count' => DsarRequest::where('company_id', $company->id)->count(),
            'compliance_score' => $this->complianceService->calculateScore($company),
        ];

        return Inertia::render('Documents/Index', [
            'company' => $company,
            'stats' => $stats,
        ]);
    }

    /**
     * Gera Política de Privacidade em PDF
     */
    public function privacyPolicy()
    {
        $user = auth()->user();
        $company = $user->company->load(['dataInventories', 'cookies']);

        $pdf = Pdf::loadView('documents.privacy-policy', [
            'company' => $company,
            'data_categories' => $this->getDataCategories($company),
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download("politica-privacidade-{$company->slug}.pdf");
    }

    /**
     * Gera Termos de Consentimento em PDF
     */
    public function consentTerms()
    {
        $user = auth()->user();
        $company = $user->company->load(['dataInventories', 'cookies']);

        $pdf = Pdf::loadView('documents.consent-terms', [
            'company' => $company,
            'cookies' => $company->cookies,
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download("termos-consentimento-{$company->slug}.pdf");
    }

    /**
     * Gera Relatório ROPA (Record of Processing Activities) em PDF
     */
    public function ropaReport()
    {
        $user = auth()->user();
        $company = $user->company;

        $inventories = DataInventory::where('company_id', $company->id)
            ->with(['department'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('documents.ropa-report', [
            'company' => $company,
            'inventories' => $inventories,
            'total_count' => $inventories->count(),
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("relatorio-ropa-{$company->slug}.pdf");
    }

    /**
     * Gera Relatório de Conformidade LGPD em PDF
     */
    public function complianceReport()
    {
        $user = auth()->user();
        $company = $user->company;

        $complianceData = $this->complianceService->calculateScore($company);

        $pdf = Pdf::loadView('documents.compliance-report', [
            'company' => $company,
            'compliance' => $complianceData,
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download("relatorio-conformidade-{$company->slug}.pdf");
    }

    /**
     * Gera Política de Cookies em PDF
     */
    public function cookiePolicy()
    {
        $user = auth()->user();
        $company = $user->company;

        $cookies = Cookie::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('categoria')
            ->get()
            ->groupBy('categoria');

        $pdf = Pdf::loadView('documents.cookie-policy', [
            'company' => $company,
            'cookies_by_category' => $cookies,
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download("politica-cookies-{$company->slug}.pdf");
    }

    /**
     * Gera Relatório de Solicitações DSAR em PDF
     */
    public function dsarReport(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $query = DsarRequest::where('company_id', $company->id);

        // Filtros opcionais
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $requests->count(),
            'by_status' => $requests->groupBy('status')->map->count(),
            'by_type' => $requests->groupBy('tipo_solicitacao')->map->count(),
            'avg_response_time' => $requests->where('status', 'concluida')
                ->avg(function ($r) {
                    return $r->created_at->diffInDays($r->updated_at);
                }),
        ];

        $pdf = Pdf::loadView('documents.dsar-report', [
            'company' => $company,
            'requests' => $requests,
            'stats' => $stats,
            'filters' => $request->only(['start_date', 'end_date', 'status']),
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("relatorio-dsar-{$company->slug}.pdf");
    }

    /**
     * Helper: Extrai categorias de dados processados
     */
    private function getDataCategories(Company $company): array
    {
        $inventories = DataInventory::where('company_id', $company->id)->get();
        $categories = [];

        foreach ($inventories as $inventory) {
            if (is_array($inventory->categoria_dados)) {
                foreach ($inventory->categoria_dados as $cat) {
                    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
                }
            }
        }

        return $categories;
    }
}
