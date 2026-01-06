<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::where('company_id', auth()->user()->company_id)
            ->orderBy('titulo')
            ->get();

        return Inertia::render('Trainings/Index', [
            'trainings' => $trainings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'obrigatorio' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Training::create($validated);

        return redirect()->route('trainings.index');
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'obrigatorio' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $training->update($validated);

        return redirect()->route('trainings.index');
    }

    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('trainings.index');
    }
}
