<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComparativoGraficoController extends Controller
{
    public function index()
    {
        $instituicoes = $this->consultarInstituicoesVisiveis();

        return view('comparar.index-grafico', compact('instituicoes'));
    }

    public function comparar(Request $request)
    {
        $dados = $request->validate([
            'aluno1_id' => 'required|exists:alunos,id',
            'aluno2_id' => 'required|exists:alunos,id|different:aluno1_id',
        ]);

        $a1 = $this->obterAlunoAutorizadoPorId((int) $dados['aluno1_id']);
        $a2 = $this->obterAlunoAutorizadoPorId((int) $dados['aluno2_id']);

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

    private function consultarInstituicoesVisiveis()
    {
        $query = Instituicao::with(['alunos' => function ($q) {
            $q->orderByRaw('idade IS NULL')
                ->orderBy('idade', 'asc');
        }])->orderBy('nome');

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->get();
        }

        return $query
            ->whereKey($this->obterInstituicaoEfetiva())
            ->get();
    }

    private function obterAlunoAutorizadoPorId(int $alunoId): Aluno
    {
        $query = Aluno::query()->whereKey($alunoId);

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->firstOrFail();
        }

        return $query
            ->where('instituicao_id', $this->obterInstituicaoEfetiva())
            ->firstOrFail();
    }

    private function obterInstituicaoEfetiva(): int
    {
        if (Auth::guard('athlete')->check()) {
            return (int) Auth::guard('athlete')->id();
        }

        if (Auth::check()) {
            return (int) Auth::user()->instituicao_id;
        }

        abort(403, 'Acesso nao autorizado.');
    }
}
