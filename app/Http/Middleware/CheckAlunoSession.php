<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAlunoSession
{
    /**
     * Se nao existir autenticacao do guard athlete,
     * redireciona para o login de aluno.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('athlete')->check()) {
            return redirect()->route('aluno.login');
        }

        return $next($request);
    }
}
