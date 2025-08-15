<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login de admin ou usuário.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Formato de e-mail inválido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'E-mail ou senha inválidos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Usuário comum deve pertencer a uma instituição
        if (! $user->is_admin && ! $user->instituicao_id) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Seu usuário ainda não foi vinculado a nenhuma instituição.'
            ]);
        }

        // Redireciona admin ou usuário
        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('aluno.dashboard');
    }

    /**
     * Efetua o logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('analise.index');
    }
}
