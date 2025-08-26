<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instituicao;
use Illuminate\Support\Facades\Hash;

class AlunoAuthController extends Controller
{
    public function showLogin()
    {
        return view('aluno.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $inst = Instituicao::where('athlete_email', $data['email'])->first();

        if (! $inst || ! Hash::check($data['password'], $inst->athlete_password)) {
            return back()
                ->withErrors(['email' => 'Credenciais inválidas'])
                ->withInput();
        }

        // força um novo session id e guarda o ID da instituição
        $request->session()->regenerate();
        $request->session()->put('aluno_instituicao_id', $inst->id);

        return redirect()->route('aluno.dashboard');
    }

    public function logout(Request $request)
    {
        // limpa só a sessão de atleta
        $request->session()->forget('aluno_instituicao_id');
        return redirect()->route('aluno.login');
    }
}
