<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\Request;

class ComparativoGraficoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::with(['alunos' => function ($q) {
            $q->orderByRaw('idade IS NULL') 
                ->orderBy('idade', 'asc');
        }])
            ->orderBy('nome')
            ->get();

        return view('comparar.index-grafico', compact('instituicoes'));
    }

    public function comparar(Request $request)
    {
        $dados = $request->validate([
            'aluno1_id' => 'required|exists:alunos,id',
            'aluno2_id' => 'required|exists:alunos,id|different:aluno1_id',
        ]);

        $a1 = Aluno::findOrFail($dados['aluno1_id']);
        $a2 = Aluno::findOrFail($dados['aluno2_id']);

        $est1 = optional($a1->analises()->latest()->first())
            ->only(config('comparativo.campos'))
            ?? array_fill_keys(config('comparativo.campos'), 0);

        $est2 = optional($a2->analises()->latest()->first())
            ->only(config('comparativo.campos'))
            ?? array_fill_keys(config('comparativo.campos'), 0);

        $labels  = config('comparativo.campos');
        $values1 = array_map(fn($c) => $est1[$c], $labels);
        $values2 = array_map(fn($c) => $est2[$c], $labels);

        return response()->json([
            'labels'  => $labels,
            'aluno1'  => $a1->nome,
            'aluno2'  => $a2->nome,
            'values1' => $values1,
            'values2' => $values2,
        ]);
    }
}
