<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class CookieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        $query = Cookie::where('company_id', $company->id);

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'true');
        }

        // Busca textual
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('fornecedor', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $cookies = $query->paginate(15)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => Cookie::where('company_id', $company->id)->count(),
            'by_categoria' => Cookie::where('company_id', $company->id)
                ->selectRaw('categoria, COUNT(*) as count')
                ->groupBy('categoria')
                ->get()
                ->pluck('count', 'categoria'),
            'active' => Cookie::where('company_id', $company->id)
                ->where('is_active', true)
                ->count(),
        ];

        return Inertia::render('Cookies/Index', [
            'cookies' => $cookies,
            'filters' => $request->only(['categoria', 'is_active', 'search', 'sort_by', 'sort_order']),
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Cookies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria' => 'required|in:necessarios,funcionais,analytics,marketing',
            'fornecedor' => 'required|string|max:255',
            'duracao' => 'required|string|max:255',
            'finalidade' => 'required|string',
            'tipo_cookie' => 'required|in:first-party,third-party',
            'is_active' => 'boolean',
        ]);

        $user = auth()->user();
        $validated['company_id'] = $user->company_id;
        $validated['is_active'] = $validated['is_active'] ?? true;

        Cookie::create($validated);

        return Redirect::route('cookies.index')
            ->with('success', 'Cookie registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cookie $cookie)
    {
        // Verificar se pertence à empresa do usuário
        if ($cookie->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        return Inertia::render('Cookies/Show', [
            'cookie' => $cookie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cookie $cookie)
    {
        // Verificar se pertence à empresa do usuário
        if ($cookie->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        return Inertia::render('Cookies/Edit', [
            'cookie' => $cookie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cookie $cookie)
    {
        // Verificar se pertence à empresa do usuário
        if ($cookie->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria' => 'required|in:necessarios,funcionais,analytics,marketing',
            'fornecedor' => 'required|string|max:255',
            'duracao' => 'required|string|max:255',
            'finalidade' => 'required|string',
            'tipo_cookie' => 'required|in:first-party,third-party',
            'is_active' => 'boolean',
        ]);

        $cookie->update($validated);

        return Redirect::route('cookies.show', $cookie->id)
            ->with('success', 'Cookie atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cookie $cookie)
    {
        // Verificar se pertence à empresa do usuário
        if ($cookie->company_id !== auth()->user()->company_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $cookie->delete();

        return Redirect::route('cookies.index')
            ->with('success', 'Cookie excluído com sucesso!');
    }

    /**
     * Scan cookies automatically from browser
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'cookies' => 'required|array',
            'cookies.*.name' => 'required|string',
            'cookies.*.value' => 'nullable|string',
            'cookies.*.domain' => 'nullable|string',
            'cookies.*.path' => 'nullable|string',
            'cookies.*.expires' => 'nullable|string',
        ]);

        $user = auth()->user();
        $company = $user->company;
        $created = 0;

        foreach ($validated['cookies'] as $cookieData) {
            // Verificar se já existe
            $exists = Cookie::where('company_id', $company->id)
                ->where('nome', $cookieData['name'])
                ->exists();

            if (!$exists) {
                Cookie::create([
                    'company_id' => $company->id,
                    'nome' => $cookieData['name'],
                    'descricao' => 'Cookie detectado automaticamente - necessita classificação',
                    'categoria' => 'necessarios', // Default, precisa revisão
                    'fornecedor' => $cookieData['domain'] ?? 'Desconhecido',
                    'duracao' => $cookieData['expires'] ?? 'Sessão',
                    'finalidade' => 'Pendente de classificação',
                    'tipo_cookie' => 'first-party',
                    'is_active' => true,
                ]);
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'message' => "$created novos cookies foram detectados e registrados.",
        ]);
    }

    /**
     * Export cookies as JSON
     */
    public function export()
    {
        $user = auth()->user();
        $company = $user->company;

        $cookies = Cookie::where('company_id', $company->id)
            ->get();

        $filename = 'cookies_' . $company->name . '_' . now()->format('Y-m-d_His') . '.json';

        return response()->json($cookies)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
