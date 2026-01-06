<?php

namespace App\Http\Controllers;

use App\Models\DataInventory;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class DataInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $query = DataInventory::with(['department', 'company'])
            ->where('company_id', $company->id);

        // Filtro por departamento
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filtro por base legal
        if ($request->filled('base_legal')) {
            $query->where('base_legal', $request->base_legal);
        }

        // Filtro por transferência internacional
        if ($request->filled('transferencia_internacional')) {
            $query->where('transferencia_internacional', $request->transferencia_internacional === 'true');
        }

        // Busca textual
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome_processo', 'like', "%{$search}%")
                  ->orWhere('finalidade', 'like', "%{$search}%")
                  ->orWhere('uuid', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $inventories = $query->paginate(15)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => DataInventory::where('company_id', $company->id)->count(),
            'by_base_legal' => DataInventory::where('company_id', $company->id)
                ->selectRaw('base_legal, COUNT(*) as count')
                ->groupBy('base_legal')
                ->get()
                ->pluck('count', 'base_legal'),
            'with_international_transfer' => DataInventory::where('company_id', $company->id)
                ->where('transferencia_internacional', true)
                ->count(),
        ];

        return Inertia::render('DataInventories/Index', [
            'inventories' => $inventories,
            'departments' => Department::where('company_id', $company->id)
                ->where('is_active', true)
                ->get(['id', 'name']),
            'filters' => $request->only(['department_id', 'base_legal', 'transferencia_internacional', 'search', 'sort_by', 'sort_order']),
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        return Inertia::render('DataInventories/Create', [
            'departments' => Department::where('company_id', $company->id)
                ->where('is_active', true)
                ->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_processo' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'finalidade' => 'required|string',
            'base_legal' => 'required|in:consentimento,contrato,obrigacao_legal,protecao_da_vida,exercicio_regular_direitos,legitimo_interesse',
            'categoria_dados' => 'required|array|min:1',
            'categoria_dados.*' => 'string',
            'tempo_retencao' => 'required|string|max:255',
            'quem_acessa' => 'nullable|array',
            'quem_acessa.*' => 'string',
            'medidas_seguranca' => 'required|string',
            'origem_dados' => 'nullable|array',
            'origem_dados.*' => 'string',
            'destinatarios_dados' => 'nullable|array',
            'destinatarios_dados.*' => 'string',
            'transferencia_internacional' => 'boolean',
            'pais_destino' => 'nullable|required_if:transferencia_internacional,true|string|max:255',
        ]);

        $user = auth()->user();
        $validated['company_id'] = $user->company_id;
        $validated['uuid'] = (string) \Illuminate\Support\Str::uuid();

        $inventory = DataInventory::create($validated);

        return Redirect::route('data-inventories.show', $inventory->id)
            ->with('success', 'Inventário de dados criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataInventory $dataInventory)
    {
        // Verificar se pertence à empresa do usuário
        if ($dataInventory->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $dataInventory->load(['department', 'company']);

        return Inertia::render('DataInventories/Show', [
            'inventory' => $dataInventory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataInventory $dataInventory)
    {
        // Verificar se pertence à empresa do usuário
        if ($dataInventory->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $user = auth()->user();
        $company = $user->company;

        return Inertia::render('DataInventories/Edit', [
            'inventory' => $dataInventory,
            'departments' => Department::where('company_id', $company->id)
                ->where('is_active', true)
                ->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataInventory $dataInventory)
    {
        // Verificar se pertence à empresa do usuário
        if ($dataInventory->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $validated = $request->validate([
            'nome_processo' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'finalidade' => 'required|string',
            'base_legal' => 'required|in:consentimento,contrato,obrigacao_legal,protecao_da_vida,exercicio_regular_direitos,legitimo_interesse',
            'categoria_dados' => 'required|array|min:1',
            'categoria_dados.*' => 'string',
            'tempo_retencao' => 'required|string|max:255',
            'quem_acessa' => 'nullable|array',
            'quem_acessa.*' => 'string',
            'medidas_seguranca' => 'required|string',
            'origem_dados' => 'nullable|array',
            'origem_dados.*' => 'string',
            'destinatarios_dados' => 'nullable|array',
            'destinatarios_dados.*' => 'string',
            'transferencia_internacional' => 'boolean',
            'pais_destino' => 'nullable|required_if:transferencia_internacional,true|string|max:255',
        ]);

        $dataInventory->update($validated);

        return Redirect::route('data-inventories.show', $dataInventory->id)
            ->with('success', 'Inventário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataInventory $dataInventory)
    {
        // Verificar se pertence à empresa do usuário
        if ($dataInventory->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $dataInventory->delete();

        return Redirect::route('data-inventories.index')
            ->with('success', 'Inventário excluído com sucesso!');
    }

    /**
     * Exportar inventários em formato JSON
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $inventories = DataInventory::with(['department'])
            ->where('company_id', $company->id)
            ->get();

        $filename = 'ropa_' . $company->name . '_' . now()->format('Y-m-d') . '.json';

        return response()->json($inventories, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Verificar completude do inventário
     */
    public function checkCompleteness(DataInventory $dataInventory)
    {
        // Verificar se pertence à empresa do usuário
        if ($dataInventory->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $requiredFields = [
            'nome_processo',
            'finalidade',
            'base_legal',
            'categoria_dados',
            'tempo_retencao',
            'medidas_seguranca',
        ];

        $missingFields = [];
        $completeness = 0;
        $totalFields = count($requiredFields);

        foreach ($requiredFields as $field) {
            if (empty($dataInventory->$field) || (is_array($dataInventory->$field) && count($dataInventory->$field) === 0)) {
                $missingFields[] = $field;
            } else {
                $completeness++;
            }
        }

        $percentage = ($completeness / $totalFields) * 100;

        return response()->json([
            'complete' => count($missingFields) === 0,
            'percentage' => round($percentage),
            'missing_fields' => $missingFields,
        ]);
    }
}
