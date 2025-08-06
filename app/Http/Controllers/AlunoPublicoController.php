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

        // Envia sempre 'analises' em vez de 'atual' / 'anterior' separados
        return view('aluno.publico', compact('aluno', 'analises'));
    }
}
