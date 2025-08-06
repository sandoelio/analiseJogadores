<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Garante que o usuário logado seja administrador.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->is_admin) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
