<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            abort(403, 'Usuário não autenticado.');
        }

        if (!$request->user()->isSuperUser()) {
            abort(403, 'Acesso negado. Somente superusuários podem acessar este recurso.');
        }

        return $next($request);
    }
}
