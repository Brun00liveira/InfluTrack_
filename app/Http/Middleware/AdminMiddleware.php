<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário tem o papel 'admin'
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        // Caso contrário, retorna um erro de acesso negado
        return response()->json(['message' => 'Acesso negado'], 403);
    }
}
