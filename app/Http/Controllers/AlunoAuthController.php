<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoAuthLoginRequest;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlunoAuthController extends Controller
{
    public function showLogin()
    {
        return view('aluno.login');
    }

    public function login(AlunoAuthLoginRequest $request)
    {
        $data = $request->validated();

        $inst = Instituicao::where('athlete_email', $data['email'])->first();

        if (!$inst || !Hash::check($data['password'], $inst->athlete_password)) {
            return back()
                ->withErrors(['email' => 'Credenciais invalidas'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('aluno_instituicao_id', $inst->id);

        return redirect()->route('public.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('aluno_instituicao_id');

        return redirect()->route('public.home');
    }
}
