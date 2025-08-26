<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAnySession
{
    public function handle(Request $request, Closure $next)
    {
        // 1) admin ou técnico logado via guard web?
        if (Auth::check()) {
            return $next($request);
        }

        // 2) atleta “logado” via sessão?
        if ($request->session()->has('aluno_instituicao_id')) {
            return $next($request);
        }

        // 3) se não for nenhum, redireciona
        //    dependendo do prefixo da rota, manda para o login certo
        if ($request->is('aluno/*') || $request->routeIs('aluno.login*')) {
            return redirect()->route('aluno.login');
        }
        return redirect()->route('login');
    }
}
