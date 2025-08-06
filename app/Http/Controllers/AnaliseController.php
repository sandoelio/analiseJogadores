<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UsuarioController extends Controller
{
    /**
     * Lista todos os usuários (somente admin).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $usuarios = User::where('is_admin', false)->with('instituicao')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Exibe o formulário de criação de usuário.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $instituicoes = Instituicao::all();
        
        return view('usuarios.create', compact('instituicoes'));
    }

    /**
     * Armazena um novo usuário ligado a uma instituição.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
            'instituicao_id' => 'required|exists:instituicoes,id',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'is_admin'       => false,
            'instituicao_id' => $request->instituicao_id,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }

    /**
     * Garante que apenas admin acesse estas rotas.
     */
    private function authorizeAdmin(): void
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            abort(403, 'Acesso não autorizado.');
        }
    }
}
