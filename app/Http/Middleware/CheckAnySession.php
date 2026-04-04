<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAnySession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() || Auth::guard('athlete')->check()) {
            return $next($request);
        }

        if ($request->is('aluno/*') || $request->routeIs('aluno.login*')) {
            return redirect()->route('aluno.login');
        }

        return redirect()->route('login');
    }
}
