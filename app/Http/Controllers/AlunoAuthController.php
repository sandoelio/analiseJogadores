<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoAuthLoginRequest;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        Auth::guard('athlete')->login($inst);
        $request->session()->regenerate();

        return redirect()->route('public.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('athlete')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('public.home');
    }
}
