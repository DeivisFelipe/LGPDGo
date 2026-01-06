<?php

namespace App\Http\Controllers;

use App\Models\Request as DsarRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource (Admin view)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $query = DsarRequest::with(['company'])
            ->where('company_id', $company->id);

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por tipo
        if ($request->filled('tipo_solicitacao')) {
            $query->where('tipo_solicitacao', $request->tipo_solicitacao);
        }

        // Filtro por urgência
        if ($request->filled('urgency')) {
            $urgency = $request->urgency;
            $query->where(function ($q) use ($urgency) {
                switch ($urgency) {
                    case 'overdue':
                        $q->where('prazo_resposta', '<', now())
                          ->where('status', '!=', 'concluida');
                        break;
                    case 'critical':
                        $q->whereBetween('prazo_resposta', [now(), now()->addDays(3)])
                          ->where('status', '!=', 'concluida');
                        break;
                    case 'high':
                        $q->whereBetween('prazo_resposta', [now()->addDays(3), now()->addDays(7)])
                          ->where('status', '!=', 'concluida');
                        break;
                    case 'normal':
                        $q->where('prazo_resposta', '>', now()->addDays(7))
                          ->where('status', '!=', 'concluida');
                        break;
                }
            });
        }

        // Busca textual
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome_titular', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('protocolo', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $requests = $query->paginate(15)->withQueryString();

        // Adicionar urgência calculada
        $requests->getCollection()->transform(function ($item) {
            $item->urgency = $this->calculateUrgency($item);
            $item->days_remaining = now()->diffInDays($item->prazo_resposta, false);
            return $item;
        });

        // Estatísticas
        $stats = [
            'total' => DsarRequest::where('company_id', $company->id)->count(),
            'by_status' => DsarRequest::where('company_id', $company->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
            'overdue' => DsarRequest::where('company_id', $company->id)
                ->where('prazo_resposta', '<', now())
                ->where('status', '!=', 'concluida')
                ->count(),
            'critical' => DsarRequest::where('company_id', $company->id)
                ->whereBetween('prazo_resposta', [now(), now()->addDays(3)])
                ->where('status', '!=', 'concluida')
                ->count(),
        ];

        return Inertia::render('Requests/Index', [
            'requests' => $requests,
            'filters' => $request->only(['status', 'tipo_solicitacao', 'urgency', 'search', 'sort_by', 'sort_order']),
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource (Public page)
     */
    public function create()
    {
        return Inertia::render('Public/DsarRequest');
    }

    /**
     * Store a newly created resource in storage (Public submission)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_slug' => 'required|exists:companies,slug',
            'nome_titular' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'tipo_solicitacao' => 'required|in:acesso,retificacao,exclusao,portabilidade,oposicao,informacao',
            'descricao' => 'required|string|min:10',
            'preferencia_contato' => 'required|in:email,telefone',
        ]);

        // Buscar company pelo slug
        $company = \App\Models\Company::where('slug', $validated['company_slug'])->firstOrFail();

        // Gerar protocolo único
        $validated['protocolo'] = 'DSAR-' . strtoupper(Str::random(8));
        $validated['company_id'] = $company->id;
        $validated['status'] = 'pendente';
        $validated['prazo_resposta'] = now()->addDays(15); // LGPD: 15 dias
        unset($validated['company_slug']);

        $dsarRequest = DsarRequest::create($validated);

        // TODO: Enviar e-mail de confirmação para o titular
        // TODO: Notificar DPO/administradores

        return Inertia::render('Public/DsarSuccess', [
            'protocolo' => $dsarRequest->protocolo,
            'prazo_resposta' => $dsarRequest->prazo_resposta->format('d/m/Y'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DsarRequest $request)
    {
        // Verificar se pertence à empresa do usuário
        if ($request->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->load(['company']);
        $request->urgency = $this->calculateUrgency($request);
        $request->days_remaining = now()->diffInDays($request->prazo_resposta, false);

        return Inertia::render('Requests/Show', [
            'request' => $request,
        ]);
    }

    /**
     * Update the specified resource in storage (Admin actions)
     */
    public function update(Request $httpRequest, DsarRequest $request)
    {
        // Verificar se pertence à empresa do usuário
        if ($request->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $validated = $httpRequest->validate([
            'status' => 'required|in:pendente,em_andamento,concluida,rejeitada',
            'resposta' => 'nullable|string',
            'observacoes_internas' => 'nullable|string',
        ]);

        if ($validated['status'] === 'concluida' && !$validated['resposta']) {
            return back()->withErrors(['resposta' => 'É necessário fornecer uma resposta para concluir a solicitação.']);
        }

        $request->update($validated);

        // TODO: Enviar e-mail para o titular notificando mudança de status

        return Redirect::route('requests.show', $request->id)
            ->with('success', 'Solicitação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DsarRequest $request)
    {
        // Verificar se pertence à empresa do usuário
        if ($request->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Apenas permite deletar se rejeitada ou muito antiga
        if ($request->status !== 'rejeitada' && $request->created_at->diffInDays(now()) < 365) {
            return back()->withErrors(['error' => 'Não é possível excluir esta solicitação. Apenas solicitações rejeitadas ou com mais de 1 ano podem ser removidas.']);
        }

        $request->delete();

        return Redirect::route('requests.index')
            ->with('success', 'Solicitação excluída com sucesso!');
    }

    /**
     * Calculate urgency level based on deadline
     */
    private function calculateUrgency(DsarRequest $request): string
    {
        if ($request->status === 'concluida') {
            return 'completed';
        }

        $daysRemaining = now()->diffInDays($request->prazo_resposta, false);

        if ($daysRemaining < 0) {
            return 'overdue';
        } elseif ($daysRemaining <= 3) {
            return 'critical';
        } elseif ($daysRemaining <= 7) {
            return 'high';
        } else {
            return 'normal';
        }
    }
}
