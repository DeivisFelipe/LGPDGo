<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiskController extends Controller
{
    public function index()
    {
        $risks = Risk::where('company_id', auth()->user()->company_id)
            ->latest()
            ->get();

        return Inertia::render('Risks/Index', [
            'risks' => $risks
        ]);
    }

    public function create()
    {
        return Inertia::render('Risks/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'probabilidade' => 'required|in:muito_baixa,baixa,media,alta,muito_alta',
            'impacto' => 'required|in:muito_baixo,baixo,medio,alto,muito_alto',
            'plano_acao' => 'nullable|string',
            'status' => 'nullable|in:identificado,em_tratamento,mitigado,aceito',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_revisao' => 'nullable|date'
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        // Calcular nível de risco automaticamente
        $validated['nivel_risco'] = $this->calculateRiskLevel($validated['probabilidade'], $validated['impacto']);

        Risk::create($validated);

        return redirect()->route('risks.index')
            ->with('success', 'Risco criado com sucesso!');
    }
    
    private function calculateRiskLevel($probabilidade, $impacto)
    {
        $matrix = [
            'muito_baixa' => 1, 'baixa' => 2, 'media' => 3, 'alta' => 4, 'muito_alta' => 5
        ];
        
        $score = $matrix[$probabilidade] * $matrix[$impacto];
        
        if ($score <= 6) return 'baixo';
        if ($score <= 12) return 'medio';
        if ($score <= 16) return 'alto';
        return 'critico';
    }

    public function edit(Risk $risk)
    {
        return Inertia::render('Risks/Edit', [
            'risk' => $risk
        ]);
    }

    public function update(Request $request, Risk $risk)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'probabilidade' => 'required|in:muito_baixa,baixa,media,alta,muito_alta',
            'impacto' => 'required|in:muito_baixo,baixo,medio,alto,muito_alto',
            'plano_acao' => 'nullable|string',
            'status' => 'nullable|in:identificado,em_tratamento,mitigado,aceito',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_revisao' => 'nullable|date'
        ]);
        
        // Recalcular nível de risco
        $validated['nivel_risco'] = $this->calculateRiskLevel($validated['probabilidade'], $validated['impacto']);

        $risk->update($validated);

        return redirect()->route('risks.index')
            ->with('success', 'Risco atualizado com sucesso!');
    }

    public function destroy(Risk $risk)
    {
        $risk->delete();

        return redirect()->route('risks.index')
            ->with('success', 'Risco removido com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
