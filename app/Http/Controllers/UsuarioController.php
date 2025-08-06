<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\CheckSession::class);
        $this->middleware(\App\Http\Middleware\CheckAdmin::class);
    }

    public function index()
    {
        $usuarios = User::where('is_admin', false)
                        ->with('instituicao')
                        ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $instituicoes = Instituicao::orderBy('nome')->get();

        return view('usuarios.create', compact('instituicoes'));
    }

    public function store(Request $request)
    {
       // 1. Validação: instituicao_nome agora é obrigatória
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6|confirmed',
            'instituicao_nome' => 'required|string|max:255',
        ]);

        // 2. Cria ou recupera a instituição a partir do nome
        $instituicao = Instituicao::firstOrCreate([
            'nome' => $data['instituicao_nome']
        ]);

        // 3. Cria o usuário vinculado à instituição
        User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'is_admin'       => false,
            'instituicao_id' => $instituicao->id,
        ]);

        return redirect()
            ->route('usuarios.create')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }
}
