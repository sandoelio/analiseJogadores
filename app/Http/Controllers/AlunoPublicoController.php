<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use App\Http\Controllers\Controller;


class AlunoPublicoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('analise.index', compact('instituicoes'));
    }

    public function listarPorInstituicao($instituicaoId)
    {
        $alunos = Aluno::where('instituicao_id', $instituicaoId)
                    ->select('nome', 'matricula')
                    ->orderBy('nome')
                    ->paginate(10);

        return response()->json($alunos);
    }

    public function mostrar($matricula)
    {
        $aluno = Aluno::where('matricula', $matricula)->firstOrFail();
        $analises = $aluno->analises()
                          ->orderBy('created_at', 'desc')
                          ->take(2)
                          ->get();

        if ($analises->count() < 2) {
            return view('aluno.publico', [
                'aluno'    => $aluno,
                'mensagem' => 'Este aluno ainda não possui comparações.'
            ]);
        }

        return view('aluno.publico', [
            'aluno'    => $aluno,
            'atual'    => $analises[0],
            'anterior' => $analises[1],
        ]);
    }
}
