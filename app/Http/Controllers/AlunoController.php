<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * Cria ou recupera o Aluno e registra uma nova Análise.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'matricula'   => 'required|string|max:50',
            'arremesso'   => 'required|integer|between:0,100',
            'passe'       => 'required|integer|between:0,100',
            'marcacao'    => 'required|integer|between:0,100',
            'finalizacao' => 'required|integer|between:0,100',
            'jogada'      => 'required|integer|between:0,100',
            'dominio'     => 'required|integer|between:0,100',
        ]);

        // recupera direto do guard
        $userId        = Auth::id();
        $instituicaoId = Auth::user()->instituicao_id;

        // Cria ou recupera o aluno pela matrícula
        $aluno = Aluno::firstOrCreate(
            ['matricula'     => $data['matricula']],
            [
                'nome'           => $data['nome'],
                'user_id'        => $userId,
                'instituicao_id' => $instituicaoId,
            ]
        );

        // Cria análise via relacionamento
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
            ->with('success', "Análise registrada para {$aluno->nome}.");
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
