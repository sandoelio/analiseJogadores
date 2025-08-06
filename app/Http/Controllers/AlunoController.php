<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AlunoController extends Controller
{
    public function __construct()
    {
        // Aplica checagem de sessão em todas as routes deste controller
        $this->middleware(\App\Http\Middleware\CheckSession::class);
    }

    /**
     * Exibe formulário para inserir aluno + análise.
     */
    public function create()
    {
        return view('aluno.create');
    }

        /**
     * Cria ou recupera o Aluno (gerando matrícula só se for novo)
     * e registra uma nova Análise.
     */
    public function store(Request $request)
    {
        // 1. validação (sem 'matricula')
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'arremesso'   => 'required|integer|between:0,100',
            'passe'       => 'required|integer|between:0,100',
            'marcacao'    => 'required|integer|between:0,100',
            'finalizacao' => 'required|integer|between:0,100',
            'jogada'      => 'required|integer|between:0,100',
            'dominio'     => 'required|integer|between:0,100',
        ]);

        // 2. dados do usuário e instituição
        $user          = Auth::user();
        $userId        = $user->id;
        $instituicaoId = $user->instituicao_id;

        // 3. gera matrícula só para novos alunos
        $sigla     = strtoupper(substr($user->instituicao->nome, 0, 3));
        $uid       = Str::random(7);
        $matricula = "{$sigla}-{$uid}";

        // 4. firstOrCreate: busca pelo aluno já existente
        $aluno = Aluno::firstOrCreate(
            [
                'nome'           => $data['nome'],
                'user_id'        => $userId,
                'instituicao_id' => $instituicaoId,
            ],
            [
                'matricula'      => $matricula,
            ]
        );

        // 5. registra a nova análise
        $aluno->analises()->create([
            'arremesso'   => $data['arremesso'],
            'passe'       => $data['passe'],
            'marcacao'    => $data['marcacao'],
            'finalizacao' => $data['finalizacao'],
            'jogada'      => $data['jogada'],
            'dominio'     => $data['dominio'],
        ]);

        return redirect()
            ->route('aluno.create')
            ->with('success', "Análise registrada para {$aluno->nome} (Matrícula: {$aluno->matricula}).");
    }

    /**
     * Compara as duas últimas análises do aluno.
     */
    public function showComparativo(int $id)
    {
        $userId = session('user_id');

        $aluno = Aluno::where('id', $id)
                      ->where('user_id', $userId)
                      ->firstOrFail();

        $analises = $aluno->analises()
                          ->latest()
                          ->take(2)
                          ->get();

        if ($analises->count() < 2) {
            return back()
                ->with('warning', 'Este aluno ainda não possui duas análises para comparação.');
        }

        return view('aluno.comparativo', [
            'aluno'    => $aluno,
            'atual'    => $analises[0],
            'anterior' => $analises[1],
        ]);
    }
}
