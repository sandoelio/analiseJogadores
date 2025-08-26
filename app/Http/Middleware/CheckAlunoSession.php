<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAlunoSession
{
    /**
     * Se não existir instituição de aluno na sessão,
     * redireciona para o login de aluno.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('aluno_instituicao_id')) {
            return redirect()->route('aluno.login');
        }
        return $next($request);
    }
}
