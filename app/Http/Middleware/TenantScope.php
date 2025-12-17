<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        // Superusuário pode ver dados de todas as empresas
        if (auth()->user()->is_super_user) {
            return $next($request);
        }

        // Garantir que o usuário tenha uma empresa associada
        if (!auth()->user()->company_id) {
            abort(403, 'Usuário não está associado a nenhuma empresa.');
        }

        return $next($request);
    }
}
