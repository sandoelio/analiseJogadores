<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\JsonResponse;

class AlunoPublicoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('analise.index', compact('instituicoes'));
    }

    public function listarPorInstituicao($instituicaoId): JsonResponse
    {
        $alunos = Aluno::where('instituicao_id', $instituicaoId)
                    ->select('nome', 'matricula','idade')
                    ->orderBy('idade', 'asc')
                    ->get();
        return response()->json($alunos);
    }

    public function mostrar($matricula): JsonResponse
    {
        $aluno = Aluno::where('matricula', $matricula)->firstOrFail();

        // Pega as duas últimas análises: [0] = mais recente, [1] = anterior (se existir)
        $analises = $aluno->analises()
                          ->orderBy('created_at', 'desc')
                          ->take(2)
                          ->get();

        // Campos fixos da análise
        $campos = ['arremesso','passe','marcacao', 'bandeja','rebote','dominio'];

        // Extrai valores
        $atual    = $analises->get(0)?->only($campos)    ?? array_fill_keys($campos, 0);
        $anterior = $analises->get(1)?->only($campos)    ?? array_fill_keys($campos, 0);

        return response()->json([
            'labels'   => [
                'Arremesso','Passe','Marcação','Bandeja','Rebote','Domínio de Bola'
            ],
            'anterior' => array_values($anterior),
            'atual'    => array_values($atual),
        ]);
    }
}
