<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;

        return Inertia::render('Settings/Index', [
            'company' => $company
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dpo_name' => 'nullable|string|max:255',
            'dpo_email' => 'nullable|email|max:255',
            'dpo_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        auth()->user()->company->update($validated);

        return redirect()->route('settings.index');
    }
}
