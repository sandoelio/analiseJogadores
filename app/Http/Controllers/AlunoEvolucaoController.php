<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AlunoEvolucaoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->get(['id', 'nome']);

        return view('evolucao.index', compact('instituicoes'));
    }

    public function mostrar(string $matricula): JsonResponse
    {
        $aluno = $this->obterAlunoAutorizadoPorMatricula($matricula, true);

        $analises = $aluno->analises()
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $analiseAtual = $analises->get(0);
        $analiseAnterior = $analises->get(1);

        abort_unless($analiseAtual, 404, 'Nenhuma analise encontrada para este atleta.');

        $campos = $this->obterCamposTecnicos();
        $evolucao = $this->montarEvolucao($campos, $analiseAtual, $analiseAnterior);
        $resumo = $this->montarResumo($evolucao);
        $fortes = $this->montarFortes($evolucao);
        $desenvolver = $this->montarDesenvolver($evolucao);

        return response()->json([
            'identificacao' => [
                'nome' => $aluno->nome,
                'matricula' => $aluno->matricula,
                'idade' => $aluno->idade,
                'sexo' => $aluno->sexo,
                'instituicao' => $aluno->instituicao?->nome,
                'ultima_analise' => $analiseAtual->created_at?->toDateString(),
                'analise_anterior' => $analiseAnterior?->created_at?->toDateString(),
                'total_analises' => $aluno->analises()->count(),
            ],
            'tem_comparacao' => $analiseAnterior !== null,
            'resumo' => $resumo,
            'evolucao' => $evolucao->values(),
            'pontos_fortes' => $fortes->values(),
            'pontos_desenvolver' => $desenvolver->values(),
        ]);
    }

    private function obterCamposTecnicos(): Collection
    {
        return collect([
            ['campo' => 'arremesso', 'label' => 'Arremesso'],
            ['campo' => 'passe', 'label' => 'Passe'],
            ['campo' => 'marcacao', 'label' => 'Marcacao'],
            ['campo' => 'bandeja', 'label' => 'Bandeja'],
            ['campo' => 'rebote', 'label' => 'Rebote'],
            ['campo' => 'dominio', 'label' => 'Dominio de Bola'],
        ]);
    }

    private function montarEvolucao(Collection $campos, $atual, $anterior): Collection
    {
        return $campos->map(function (array $item) use ($atual, $anterior) {
            $campo = $item['campo'];
            $valorAtual = $atual?->{$campo};
            $valorAnterior = $anterior?->{$campo};

            if ($valorAnterior === null) {
                $status = 'sem_base';
                $delta = null;
            } else {
                $delta = (float) $valorAtual - (float) $valorAnterior;
                $status = $delta > 0 ? 'subiu' : ($delta < 0 ? 'caiu' : 'manteve');
            }

            return [
                'campo' => $campo,
                'label' => $item['label'],
                'atual' => $valorAtual,
                'anterior' => $valorAnterior,
                'delta' => $delta,
                'status' => $status,
                'icone' => $this->obterIconeStatus($status),
                'texto_curto' => $this->obterTextoCurto($status),
            ];
        });
    }

    private function montarResumo(Collection $evolucao): array
    {
        $subiu = $evolucao->where('status', 'subiu')->count();
        $caiu = $evolucao->where('status', 'caiu')->count();
        $manteve = $evolucao->where('status', 'manteve')->count();
        $semBase = $evolucao->where('status', 'sem_base')->count();

        $maiorAlta = $evolucao
            ->filter(fn($item) => $item['delta'] !== null && $item['delta'] > 0)
            ->sortByDesc('delta')
            ->first();

        $maiorQueda = $evolucao
            ->filter(fn($item) => $item['delta'] !== null && $item['delta'] < 0)
            ->sortBy('delta')
            ->first();

        return [
            'subiu' => $subiu,
            'caiu' => $caiu,
            'manteve' => $manteve,
            'sem_base' => $semBase,
            'maior_alta' => $maiorAlta ? $maiorAlta['label'] . ' +' . $this->formatarDelta($maiorAlta['delta']) : '--',
            'maior_queda' => $maiorQueda ? $maiorQueda['label'] . ' ' . $this->formatarDelta($maiorQueda['delta']) : '--',
            'mensagem_atleta' => $semBase === $evolucao->count()
                ? 'Esta e a primeira analise registrada. Os proximos resultados mostrarao sua evolucao.'
                : "Voce evoluiu em {$subiu} fundamento(s), manteve {$manteve} e caiu em {$caiu}.",
            'mensagem_tecnico' => $semBase === $evolucao->count()
                ? 'Atleta com apenas uma analise registrada. Ainda nao existe base comparativa anterior.'
                : "Ultima analise comparada com a anterior: {$subiu} evolucoes, {$manteve} manutencoes e {$caiu} quedas.",
        ];
    }

    private function montarFortes(Collection $evolucao): Collection
    {
        return $evolucao
            ->sortByDesc('atual')
            ->take(3)
            ->map(function ($item) {
                return [
                    'label' => $item['label'],
                    'valor' => $item['atual'],
                    'status' => $item['texto_curto'],
                ];
            });
    }

    private function montarDesenvolver(Collection $evolucao): Collection
    {
        return $evolucao
            ->sortBy('atual')
            ->take(3)
            ->map(function ($item) {
                return [
                    'label' => $item['label'],
                    'valor' => $item['atual'],
                    'status' => $item['texto_curto'],
                ];
            });
    }

    private function obterIconeStatus(string $status): string
    {
        return match ($status) {
            'subiu' => 'bi-arrow-up-right',
            'caiu' => 'bi-arrow-down-right',
            'manteve' => 'bi-dash-lg',
            default => 'bi-dot',
        };
    }

    private function obterTextoCurto(string $status): string
    {
        return match ($status) {
            'subiu' => 'Subiu',
            'caiu' => 'Caiu',
            'manteve' => 'Manteve',
            default => 'Sem base',
        };
    }

    private function formatarDelta(?float $delta): string
    {
        if ($delta === null) {
            return '--';
        }

        return number_format($delta, 1, ',', '.');
    }

    private function obterAlunoAutorizadoPorMatricula(string $matricula, bool $carregarRelacoes = false): Aluno
    {
        $query = Aluno::query()->where('matricula', $matricula);

        if ($carregarRelacoes) {
            $query->with(['instituicao:id,nome']);
        }

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->firstOrFail();
        }

        $instituicaoId = Auth::guard('athlete')->id();

        if (! $instituicaoId && Auth::check()) {
            $instituicaoId = Auth::user()->instituicao_id;
        }

        abort_unless($instituicaoId, 403, 'Acesso nao autorizado.');

        return $query
            ->where('instituicao_id', $instituicaoId)
            ->firstOrFail();
    }
}
