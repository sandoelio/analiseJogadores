<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Exibe o formulario de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login de admin ou usuario.
     */
    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'E-mail ou senha invalidos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if (!$user->is_admin && !$user->instituicao_id) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Seu usuario ainda nao foi vinculado a nenhuma instituicao.'
            ]);
        }

        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('tecnico.dashboard');
    }

    /**
     * Efetua o logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.home');
    }
}
